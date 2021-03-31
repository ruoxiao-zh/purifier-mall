<?php

namespace App\Models;

use App\Enums\ProductOnSaleStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'image', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price',
    ];

    protected $casts = [
        'on_sale' => 'boolean',
    ];

    public function skus()
    {
        return $this->hasMany(ProductSku::class)->recent();
    }

    public function scopeOnSale($query)
    {
        return $query->where('on_sale', ProductOnSaleStatus::IS_ON_SALE);
    }
}
