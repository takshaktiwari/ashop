<?php

namespace Takshak\Ashop\Services;

use Takshak\Ashop\Models\Shop\Cart;

class CartService
{
    public $carts;

    public function __construct()
    {
        $this->carts = Cart::query()
            ->with(['product' => function ($query) {
                $query->with('metas');
                $query->with('wishlistAuthUser');
                $query->with('categories:id');
            }])
            ->has('product')
            ->where('user_id', auth()->id())
            ->orWhere('user_ip', request()->ip())
            ->get();
    }

    public function coupon($param = null)
    {
        return $param ? session('coupon.' . $param) : session('coupon');
    }

    public function items()
    {
        return $this->carts;
    }

    public function count()
    {
        return $this->carts->count();
    }

    public function subtotal()
    {
        return $this->carts->sum('subtotal');
    }

    public function subtotalNetPrice()
    {
        return $this->carts->sum('subtotal_net_price');
    }

    public function discount($type = 'coupon')
    {
        if ($type == 'net_price') {
            return $this->carts->sum('discount_net_price');
        } elseif ($type == 'coupon') {
            return $this->coupon('discount') ? $this->coupon('discount') : 0;
        } elseif ($type == 'all') {
            return $this->coupon('discount') + $this->carts->sum('discount_net_price');
        }
    }

    public function shippingCharge()
    {
        if (config('ashop.order.default_shipping.type', 'percent') == 'percent') {
            return $this->subtotal() * config('ashop.order.default_shipping.amount', 10) / 100;
        } else {
            return config('ashop.order.default_shipping.amount', 99);
        }
    }

    public function total()
    {
        return $this->subtotal() - $this->discount();
    }

    public function empty(string $type = '')
    {
        if (!$type || $type == 'carts') {
            Cart::whereIn('id', $this->items()->pluck('id'))->delete();
        }
        if (!$type || $type == 'coupon') {
            request()->session()->forget('coupon');
        }
    }
}
