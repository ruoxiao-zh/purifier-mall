<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use App\Enums\OrderShipStatusEnum;
use App\Enums\OrderRefundStatusEnum;
use App\Services\Order\OrderService;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Requests\Api\ApplyRefundRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = $this->orderService->store($request);

        return (new OrderResource($order))->response()->setStatusCode(HttpCodeEnum::HTTP_CODE_201);
    }

    public function show(Order $order): JsonResource
    {
        $this->orderService->checkAuthorize($order);

        return new OrderResource($order->loadingWith());
    }

    public function index(Request $request)
    {
        return OrderResource::collection(Order::loadingWith()->where('user_id', $request->user()->id)
            ->recent()
            ->paginate());
    }

    public function destroy(Order $order)
    {
        $this->orderService->checkAuthorize($order);

        $order->delete();

        return response(null, HttpCodeEnum::HTTP_CODE_204);
    }

    public function received(Order $order, Request $request)
    {
        $this->orderService->checkAuthorize($order);

        if ($order->ship_status !== OrderShipStatusEnum::SHIP_STATUS_DELIVERED) {
            abort(HttpCodeEnum::HTTP_CODE_500, '发货状态不正确');
        }

        $order->update(['ship_status' => OrderShipStatusEnum::SHIP_STATUS_RECEIVED]);

        return response(null, HttpCodeEnum::HTTP_CODE_204);
    }

    public function applyRefund(Order $order, ApplyRefundRequest $request)
    {
        $this->orderService->checkAuthorize($order);

        if ( !$order->paid_at) {
            abort(HttpCodeEnum::HTTP_CODE_500, '该订单未支付，不可退款');
        }
        if ($order->refund_status !== OrderRefundStatusEnum::REFUND_STATUS_PENDING) {
            abort(HttpCodeEnum::HTTP_CODE_500, '该订单已经申请过退款，请勿重复申请');
        }

        $extra = $order->extra ?: [];
        $extra['refund_reason'] = $request->input('reason');
        $order->update([
            'refund_status' => OrderRefundStatusEnum::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);

        return $order;
    }
}
