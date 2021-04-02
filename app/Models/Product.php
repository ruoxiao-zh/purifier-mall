<?php

namespace App\Models;

use App\Enums\ProductOnSaleStatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function skus(): HasMany
    {
        return $this->hasMany(ProductSku::class)->recent();
    }

    public function scopeOnSale($query)
    {
        return $query->where('on_sale', ProductOnSaleStatusEnum::IS_ON_SALE);
    }
}
