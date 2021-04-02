<?php

namespace App\Services\Order;

use Carbon\Carbon;
use App\Models\Order;
use App\Jobs\CloseOrder;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class OrderService
{
    public function store(Request $request)
    {
        $user = $request->user();
        $order = \DB::transaction(function () use ($user, $request) {
            $address = UserAddress::find($request->input('address_id'));
            $address->update(['last_used_at' => Carbon::now()]);

            $order = new Order([
                'address'      => [ // 将地址信息放入订单中
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
            Gate::authorize('own', $order);
        } catch (AuthorizationException $e) {
            abort($e->getCode(), '权限不足');
        }
    }
}
