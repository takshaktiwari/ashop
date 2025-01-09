<?php

namespace Takshak\Ashop\Http\Resources;

use App\Http\Resources\UsersResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderUpdatesResource extends JsonResource
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
            'order_status' => $this->orderStatus(),
            'payment_status' => $this->paymentStatus(),
            'notes' => $this->notes,
        ];
    }

}
