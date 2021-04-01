<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpCodeEnum;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use App\Http\Requests\Api\AddCartRequest;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // return CartResource::collection(CartItem::query()->loadingWith()->recent()->paginate());
        return CartResource::collection($request->user()->cartItems()->loadingWith()->recent()->paginate());
    }

    public function store(AddCartRequest $request)
    {
        $user = $request->user();
        $skuId = $request->input('sku_id');
        $amount = $request->input('amount');

        if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            $cart->update(['amount' => $cart->amount + $amount]);
        } else {
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return (new CartResource($cart))->response()->setStatusCode(HttpCodeEnum::HTTP_CODE_201);
    }

    public function destory(ProductSku $productSku, Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id', $productSku->id)->delete();

        return response(null, HttpCodeEnum::HTTP_CODE_204);
    }
}
