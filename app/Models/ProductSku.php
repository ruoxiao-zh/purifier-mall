<?php

namespace App\Models;

use App\Enums\HttpCodeEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            return response()->json(['message' => '减库存不可小于0'], HttpCodeEnum::HTTP_CODE_422);
        }

        return self::query()->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }

    public function addStock($amount)
    {
        if ($amount < 0) {
            return response()->json(['message' => '加库存不可小于0'], HttpCodeEnum::HTTP_CODE_422);
        }

        $this->increment('stock', $amount);
    }
}
