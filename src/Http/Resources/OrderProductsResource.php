<?php

namespace Takshak\Ashop\Http\Resources;

use App\Http\Resources\UsersResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductsResource extends JsonResource
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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'image' => storage($this->image),
            'quantity' => $this->quantity,
            'net_price' => $this->net_price,
            'price' => $this->price,
            'others' => $this->others,
            'product' => $this->when($this->relationLoaded('product'), new ProductsResource($this->product))
        ];
    }

}
