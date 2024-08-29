<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'landmark' => $this->landmark,
            'city' => $this->city,
            'pincode' => $this->pincode,
            'state' => $this->state,
            'country' => $this->country,
            'default_addr' => $this->default_addr,
            'billing_addr' => $this->billing_addr,
            'user_ip' => $this->user_ip,
            'created_at' => $this->created_at,
        ];
    }
}
