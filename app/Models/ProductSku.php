<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
