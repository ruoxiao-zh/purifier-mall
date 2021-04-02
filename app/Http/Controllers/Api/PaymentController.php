<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderRefundStatusEnum;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Enums\HttpCodeEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class PaymentController extends Controller
{
    public function payByWechat(Order $order, Request $request)
    {
        try {
            $this->authorize('own', $order);
        } catch (AuthorizationException $e) {
            abort(HttpCodeEnum::HTTP_CODE_401, '权限不足');
        }

        if ($order->paid_at || $order->closed) {
            return response()->json(['message' => '订单状态不正确'], HttpCodeEnum::HTTP_CODE_500);
        }

        return app('wechat_pay')->miniapp([
            'out_trade_no' => $order->no,  // 商户订单流水号，与支付宝 out_trade_no 一样
            'total_fee'    => $order->total_amount * 100, // 与支付宝不同，微信支付的金额单位是分。
            'body'         => '支付订单：' . $order->no, // 订单描述
            'openid'       => 'openid',
        ]);
    }

    public function wechatNotify()
    {
        $data = app('wechat_pay')->verify();

        $order = Order::where('no', $data->out_trade_no)->first();
        if ( !$order) {
            return 'fail';
        }

        if ($order->paid_at) {
            // 告知微信支付此订单已处理
            return app('wechat_pay')->success();
        }

        $order->update([
            'paid_at'        => Carbon::now(),
            'payment_method' => 'wechat',
            'payment_no'     => $data->transaction_id,
        ]);

        $this->afterPaid($order);

        return app('wechat_pay')->success();
    }

    protected function afterPaid(Order $order): void
    {
        event(new OrderPaid($order));
    }

    public function wechatRefundNotify(Request $request)
    {
        // 给微信的失败响应
        $failXml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        $data = app('wechat_pay')->verify(null, true);

        if ( !$order = Order::where('no', $data['out_trade_no'])->first()) {
            return $failXml;
        }

        if ($data['refund_status'] === 'SUCCESS') {
            $order->update(['refund_status' => OrderRefundStatusEnum::REFUND_STATUS_SUCCESS]);
        } else {
            $extra = $order->extra;
            $extra['refund_failed_code'] = $data['refund_status'];
            $order->update([
                'refund_status' => OrderRefundStatusEnum::REFUND_STATUS_FAILED,
                'extra'         => $extra,
            ]);
        }

        return app('wechat_pay')->success();
    }
}
