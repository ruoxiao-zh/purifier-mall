<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpCodeEnum;
use App\Models\Order;
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
        return new OrderResource($order);
    }
}
