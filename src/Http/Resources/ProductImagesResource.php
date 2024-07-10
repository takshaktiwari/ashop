<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImagesResource extends JsonResource
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
            'image_sm' => storage($this->image_sm),
            'image_md' => storage($this->image_md),
            'image_lg' => storage($this->image_lg),
            'title' => $this->title,
            'created_at' => $this->created_at
        ];
    }
}
