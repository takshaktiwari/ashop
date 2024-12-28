<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
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
            'name' => $this->display_name ? $this->display_name : $this->name,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'image_sm' => storage($this->image_sm),
            'image_md' => storage($this->image_md),
            'image_lg' => storage($this->image_lg),
            'banner' => $this->banner(),
            'description' => $this->description,
            'status' => $this->status,
            'featured' => $this->featured,
            'is_top' => $this->is_top,
            'created_at' => $this->created_at,
            'children' => $this->when($this->relationLoaded('children'), CategoriesResource::collection($this->children)),
            'products' => $this->when($this->relationLoaded('products'), ProductsResource::collection($this->products)),
            'products_count' => $this->whenCounted('products', $this->products_count),
            'category_metas' => $this->when(
                $this->relationLoaded('metas'),
                $this->metas
            ),
        ];
    }
}
