<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'id'            => (int)$this->id,
//            'user_name'     => $request->user()->name,
            'province'      => $this->province,
            'city'          => $this->city,
            'district'      => $this->district,
            'address'       => $this->address,
            'full_address'  => $this->fullAddress,
            'zip'           => $this->zip,
            'contact_name'  => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'last_used_at'  => $this->last_used_at,
            'created_at'    => $this->created_at->toDateTimeString(),
            'updated_at'    => $this->updated_at->toDateTimeString(),
        ];
    }
}
