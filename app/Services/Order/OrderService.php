<?php

namespace App\Services\Order;

use Carbon\Carbon;
use App\Models\Order;
use App\Jobs\CloseOrder;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use App\Enums\OrderRefundStatusEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderService
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $user = $request->user();
        $order = \DB::transaction(function () use ($user, $request) {
            $address = UserAddress::find($request->input('address_id'));
            $address->update(['last_used_at' => Carbon::now()]);

            $order = new Order([
                'address'      => [
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $request->input('remark'),
                'total_amount' => 0,
            ]);
            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;
            $items = $request->input('items');
            foreach ($items as $data) {
                $sku = ProductSku::find($data['sku_id']);
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    return response()->json(['message' => '该商品库存不足'], HttpCodeEnum::HTTP_CODE_500);
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id');
            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();

            dispatch(new CloseOrder($order, env('ORDER_TTL')));

            return $order;
        });

        return $order;
    }

    public function checkAuthorize(Order $order): void
    {
        try {
            $this->authorize('own', $order);
        } catch (AuthorizationException $e) {
            abort(HttpCodeEnum::HTTP_CODE_403, '权限不足');
        }
    }

    public function refundOrder(Order $order): void
    {
        switch ($order->payment_method) {
            case 'wechat':
                $refundNo = Order::getAvailableRefundNo();

                app('wechat_pay')->refund([
                    'out_trade_no'  => $order->no,
                    'total_fee'     => $order->total_amount * 100,
                    'refund_fee'    => $order->total_amount * 100,
                    'out_refund_no' => $refundNo,
                    'notify_url'    => env('WECHAT_PAY_NOTIFY_URL'),
                ]);

                $order->update([
                    'refund_no'     => $refundNo,
                    'refund_status' => OrderRefundStatusEnum::REFUND_STATUS_PROCESSING,
                ]);

                break;
            case 'alipay':
                // todo
                break;
            default:
                abort(HttpCodeEnum::HTTP_CODE_500, '未知订单支付方式');
        }
    }

    public function getAll(Request $request)
    {
        return Order::query()
            ->filter($request->all())
            ->loadingWith()
            // ->where('user_id', $request->user()->id)
            ->where('user_id', 1)
            ->recent()
            ->paginate();
    }
}
