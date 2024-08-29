<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
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
            'email' => $this->email,
            'rating' => $this->rating,
            'title' => $this->title,
            'content' => $this->content,
            'reviewable_type' => $this->reviewable_type,
            'reviewable_id' => $this->reviewable_id,
            'created_at' => $this->created_at,
            'product' => $this->when($this->relationLoaded('reviewable'), ProductsResource::make($this->reviewable)),
        ];
    }
}
