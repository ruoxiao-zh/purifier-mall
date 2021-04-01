<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpCodeEnum;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return ProductResource::collection(Product::query()->onSale()->recent()->paginate());
    }

    public function show(Request $request, Product $product)
    {
        if ( !$product->on_sale) {
            return response()->json(['message' => '商品未上架!'], HttpCodeEnum::HTTP_CODE_500);
        }

        return (new ProductResource($product))->showSkus();
    }
}
