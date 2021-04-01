<?php

namespace App\Http\Resources;

class OrderResource extends BaseResource
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
            $this->merge([
                'order_items' => $this->items,
            ]),
        ];
    }
}
