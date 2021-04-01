<?php

namespace App\Http\Resources;

class ProductResource extends BaseResource
{
    protected $showSkus = false;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        if ($this->showSkus) {
            $data['skus'] = $this->skus;
        }

        return $data;
    }

    public function showSkus(): self
    {
        $this->showSkus = true;

        return $this;
    }
}
