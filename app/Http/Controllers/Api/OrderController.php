<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use App\Services\Order\OrderService;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Api\OrderRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function store(OrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = $this->orderService->store($request);

        return (new OrderResource($order))->response()->setStatusCode(HttpCodeEnum::HTTP_CODE_201);
    }

    public function show(Order $order): JsonResource
    {
        $this->authorize('own', $order);

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
        $this->authorize('own', $order);

        $order->delete();

        return response(null, HttpCodeEnum::HTTP_CODE_204);
    }
}
