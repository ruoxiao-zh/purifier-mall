<?php

namespace App\Models;

class CartItem extends BaseModel
{
    protected $fillable = ['amount'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductSku::class);
    }

    public function scopeLoadingWith($query)
    {
        return $query->with(['user', 'productSku.product']);
    }
}
