<?php

namespace App\Http\Resources;

class CartResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'user'           => $this->user,
            'product'        => $this->productSku->product,
            'product_sku_id' => $this->product_sku_id,
            'product_sku'    => $this->productSku,
            'amount'         => $this->amount,
        ];
    }
}
