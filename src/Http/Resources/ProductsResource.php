<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
            'product_id' => $this->product_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'image_sm' => storage($this->image_sm),
            'image_md' => storage($this->image_md),
            'image_lg' => storage($this->image_lg),
            'net_price' => $this->net_price,
            'sell_price' => $this->sell_price,
            'deal_price' => $this->deal_price,
            'deal_expiry' => $this->deal_expiry,
            'stock' => $this->stock,
            'brand_id' => $this->brand_id,
            'sku' => $this->sku,
            'featured' => $this->featured,
            'status' => $this->status,
            'info' => $this->info,
            'checkout_type' => $this->checkout_type,
            'external_url' => $this->external_url,
            'created_at' => $this->created_at,
        ];
    }
}
