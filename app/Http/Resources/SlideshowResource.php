<?php

namespace App\Http\Resources;

class SlideshowResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
