<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartsResource extends JsonResource
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
            'user_ip' => $this->user_ip,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'subtotal_net_price' => $this->subtotal_net_price,
            'discount_net_price' => $this->discount_net_price,
            'created_at' => $this->created_at,
            'product' => $this->when($this->relationLoaded('product'), ProductsResource::make($this->product)),
        ];
    }
}
