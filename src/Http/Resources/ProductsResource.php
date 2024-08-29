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
        $arr = [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'reviews_count' => $this->reviews_count,
            'is_favorite' => $this->wishlistAuthUser->count(),
            'rating' => $this->rating,
            'image_sm' => storage($this->image_sm),
            'image_md' => storage($this->image_md),
            'image_lg' => storage($this->image_lg),
            'price' => $this->price,
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
            'reviews' => $this->when(
                $this->relationLoaded('reviews'),
                $this->reviews
            ),
            'images' => $this->when(
                $this->relationLoaded('images'),
                ProductImagesResource::collection($this->images)
            ),
        ];

        if($this->relationLoaded('categories')){
            $arr['categories'] = CategoriesResource::collection($this->categories);
        }

        // two condition with same relation doesn't work, so keeping it out of the $arr in a single condition
        if($this->relationLoaded('metas')){
            $arr['product_details'] = $this->productDetails($this->metas, 'product_details');
            $arr['product_attributes'] = $this->productDetails($this->metas, 'product_attributes');
        }

        return $arr;
    }

    public function productDetails($metas, $key)
    {
        $details = [];
        foreach ($metas->where('key', $key) as $item) {
            $details[$item->name] = ($key == 'product_attributes') ? json_decode($item->value) : $item->value;
        }

        return $details;
    }
}
