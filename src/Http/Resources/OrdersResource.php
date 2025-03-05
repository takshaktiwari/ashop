<?php

namespace Takshak\Ashop\Http\Resources;

use App\Http\Resources\UsersResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            'order_no' => $this->order_no,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'landmark' => $this->landmark,
            'city' => $this->city,
            'pincode' => $this->pincode,
            'state' => $this->state,
            'country' => $this->country,
            'full_address' => $this->address(),
            'subtotal' => $this->subtotal,
            'coupon_code' => $this->coupon_code,
            'discount' => $this->discount,
            'shipping_charge' => $this->shipping_charge,
            'total_amount' => $this->total_amount,
            'order_status' => $this->orderStatus(),
            'payment_mode' => $this->paymentMode(),
            'payment_status' => $this->paymentStatus(),
            'user_id' => $this->user_id,
            'user_ip' => $this->user_ip,
            'images' => $this->when($this->images, $this->images),
            'user' => $this->when($this->relationLoaded('user'), UsersResource::make($this->user)),
            'order_products' => $this->when($this->relationLoaded('orderProducts'), OrderProductsResource::collection($this->orderProducts)),
            'order_updates' => $this->when($this->relationLoaded('orderUpdates'), OrderUpdatesResource::collection($this->orderUpdates)),
            'order_products_count' => $this->when($this->order_products_count, $this->order_products_count),
            'created_at' => $this->created_at
        ];
    }

}
