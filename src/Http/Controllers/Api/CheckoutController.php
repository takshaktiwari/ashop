<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\CouponsResource;
use Takshak\Ashop\Http\Resources\OrdersResource;
use Takshak\Ashop\Models\Shop\Address;
use Takshak\Ashop\Models\Shop\Coupon;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\OrderProduct;
use Takshak\Ashop\Services\CartService;

class CheckoutController extends Controller
{
    public $cartService;
    public function __construct()
    {
        $this->cartService = (new CartService());
    }

    public function address(Request $request)
    {
        abort_if(!$this->cartService->count(), 403, 'No items in cart. Please add items to cart.');
        if (!$request->integer('address_id') || !$request->user()) {
            $request->validate([
                'name' => 'required|max:200',
                'mobile' => 'required|max:50',
                'address_line_1' => 'required|max:255',
                'address_line_2' => 'required|max:255',
                'landmark' => 'required|max:255',
                'city' => 'required|max:200',
                'pincode' => 'required|max:10',
                'state' => 'required|max:200',
                'country' => 'required|max:200',
            ]);
        }


        $carts = $this->cartService->items();

        $order = new Order();
        $order->user_id = auth()->id();
        $order->user_ip = $request->ip();
        $order->subtotal = $carts->sum('subtotal');

        $address = null;
        if ($request->user()) {
            if ($request->integer('address_id')) {
                $address = Address::where('user_id', auth()->id())->find($request->input('address_id'));
                abort_if(!$address, 403, 'Address not found');
            } else {
                $address = Address::create([
                    'name' => $request->input('name'),
                    'mobile' => $request->input('mobile'),
                    'address_line_1' => $request->input('address_line_1'),
                    'address_line_2' => $request->input('address_line_2'),
                    'landmark' => $request->input('landmark'),
                    'city' => $request->input('city'),
                    'pincode' => $request->input('pincode'),
                    'state' => $request->input('state'),
                    'country' => $request->input('country'),
                    'user_id' => auth()->id(),
                    'user_ip' => $request->ip(),
                ]);
            }
        }

        $order->name = $address ? $address->name : $request->input('name');
        $order->mobile = $address ? $address->mobile : $request->input('mobile');
        $order->address_line_1 = $address ? $address->address_line_1 : $request->input('address_line_1');
        $order->address_line_2 = $address ? $address->address_line_2 : $request->input('address_line_2');
        $order->landmark = $address ? $address->landmark : $request->input('landmark');
        $order->city = $address ? $address->city : $request->input('city');
        $order->pincode = $address ? $address->pincode : $request->input('pincode');
        $order->state = $address ? $address->state : $request->input('state');
        $order->country = $address ? $address->country : $request->input('country');
        $order->shipping_charge = $this->cartService->shippingCharge();
        $order->save();

        foreach ($carts as $cart) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'name' => $cart->product->name,
                'image' => $cart->product->image_sm,
                'net_price' => $cart->product->net_price,
                'price' => $cart->product->price,
                'quantity' => $cart->quantity,
                'others' => []
            ]);
        }

        $order->load('orderProducts.product');

        return OrdersResource::make($order);
    }

    public function coupons()
    {
        $coupons = Coupon::query()
            ->active()
            ->where(function ($query) {
                $query->whereNull('min_purchase');
                $query->orWhere('min_purchase', '<=', $this->cartService->subtotal());
            })
            ->whereDoesntHave('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at');
                $query->orWhere('expires_at', '>', now());
            })
            ->get();

        return CouponsResource::collection($coupons);
    }

    public function couponsApply(Request $request)
    {
        $request->validate([
            'coupon' => 'required|string',
            'order_id' => 'required|numeric'
        ]);

        $order = Order::query()
            ->withCount('orderProducts')
            ->where(function ($query) use ($request) {
                $query->where('user_id', $request->user()?->id);
                $query->orWhere('user_ip', $request->ip());
            })
            ->find($request->input('order_id'));

        abort_if(!$order, 403, 'Order not found');
        abort_if(!$order->order_products_count, 403, 'This order is empty. Please add some products to checkout.');

        $coupon = Coupon::query()
            ->active()
            ->where('code', $request->input('coupon'))
            ->where(function ($query) {
                $query->whereNull('min_purchase');
                $query->orWhere('min_purchase', '<=', $this->cartService->subtotal());
            })
            ->whereDoesntHave('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at');
                $query->orWhere('expires_at', '>', now());
            })
            ->first();

        abort_if(!$coupon, 403, 'Coupon not found or not valid');

        if ($coupon->max_usable) {
            abort_if($coupon->users->count() >= $coupon->max_usable, 403, 'Coupon is not valid or already used');
        }

        $this->cartService->setCoupon($coupon);
        $order->coupon_code = $coupon->code;
        $order->discount = $this->cartService->discount('coupon');
        $order->save();

        return OrdersResource::make($order);
    }

    public function couponsRemove(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric'
        ]);

        $order = Order::query()
            ->where(function ($query) use ($request) {
                $query->where('user_id', $request->user()?->id);
                $query->orWhere('user_ip', $request->ip());
            })
            ->find($request->input('order_id'));
        abort_if(!$order, 403, 'Order not found');

        $order->coupon_code = null;
        $order->discount = null;
        $order->save();

        return OrdersResource::make($order);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric',
            'payment_mode' => 'required|in:cod,online'
        ]);

        $order = Order::query()
            ->withCount('orderProducts')
            ->where(function ($query) use ($request) {
                $query->where('user_id', $request->user()?->id);
                $query->orWhere('user_ip', $request->ip());
            })
            ->find($request->input('order_id'));

        abort_if(!$order, 403, 'Order not found');
        abort_if(!$order->order_products_count, 403, 'This order is empty. Please add some products to checkout.');
        abort_if($order->order_status, 403, 'Order is already completed, current status is: ' . $order->orderStatus());

        $order->payment_mode = $request->input('payment_mode');
        $order->order_status = config('ashop.order.initial_status');
        $order->save();

        $this->cartService->empty();

        $order->load('orderProducts.product');

        return OrdersResource::make($order);
    }
}
