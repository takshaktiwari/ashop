<?php

namespace Takshak\Ashop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponsResource extends JsonResource
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
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'percent' => $this->percent,
            'amount' => $this->amount,
            'min_purchase' => $this->min_purchase,
            'max_discount' => $this->max_discount,
            'expires_at' => $this->expires_at,
            'max_usable' => $this->max_usable,
            'status' => $this->status,
            'featured' => $this->featured,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }
}
