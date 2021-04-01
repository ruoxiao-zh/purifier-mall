<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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

    public function showSkus(): JsonResource
    {
        $this->showSkus = true;

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\JsonResponse $response
     */
    public function withResponse($request, $response)
    {
        $response->header('X-Value', 'True');
    }
}
