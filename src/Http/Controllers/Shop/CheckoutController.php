<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Mail\OrderConfirmationMail;
use Takshak\Ashop\Models\Shop\Address;
use Takshak\Ashop\Models\Shop\Cart;
use Takshak\Ashop\Models\Shop\Coupon;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\OrderProduct;
use Takshak\Ashop\Models\Shop\OrderUpdate;
use Takshak\Ashop\Services\CartService;

class CheckoutController extends Controller
{
    public $cartService;
    public function __construct()
    {
        $this->cartService = (new CartService());
        $carts = $this->cartService->count();

        if (!$carts) {
            return to_route('shop.carts.index');
        }
    }

    public function index()
    {
        $newAddress = true;
        if (auth()->user() && auth()->user()->addresses->count()) {
            $newAddress = false;
        }

        return View::first(['shop.checkout.index', 'ashop::shop.checkout.index'])->with([
            'newAddress' => $newAddress
        ]);
    }

    public function address(Request $request)
    {
        $request->validate([
            'address_id' => 'nullable|numeric',
            'name' => 'nullable|required_if:address_id,0|max:200',
            'mobile' => 'nullable|required_if:address_id,0|max:50',
            'address_line_1' => 'nullable|required_if:address_id,0|max:255',
            'address_line_2' => 'nullable|required_if:address_id,0|max:255',
            'landmark' => 'nullable|required_if:address_id,0|max:255',
            'city' => 'nullable|required_if:address_id,0|max:200',
            'pincode' => 'nullable|required_if:address_id,0|max:10',
            'state' => 'nullable|required_if:address_id,0|max:200',
            'country' => 'nullable|required_if:address_id,0|max:200',
        ]);

        $carts = $this->cartService->items();

        $order = new Order();
        $order->user_id = auth()->id();
        $order->user_ip = $request->ip();
        $order->subtotal = $carts->sum('subtotal');

        $address = null;
        if (auth()->check()) {
            if ($request->input('address_id') == '0') {
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
            } else {
                $address = Address::where('user_id', auth()->id())->find($request->input('address_id'));
                if (!$address) {
                    return back()->withErrors('Sorry !! Address not found');
                }
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

        session(['order_id' => $order->id]);

        return to_route('shop.checkout.summary');
    }

    public function summary()
    {
        $order = Order::query()->find(session('order_id'));
        if (!$order) {
            return to_route('shop.carts.index')->withErrors('Sorry! Something not right, please try to checkout again');
        }

        $coupons = Coupon::query()
            ->available()
            ->where(function ($query) {
                $query->whereNull('min_purchase');
                $query->orWhere('min_purchase', '<=', $this->cartService->subtotal());
            })
            ->whereDoesntHave('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();

        return View::first(['shop.checkout.summary', 'ashop::shop.checkout.summary'])->with([
            'order' => $order,
            'coupons' => $coupons,
            'cartService' => $this->cartService
        ]);
    }

    public function coupon(Request $request)
    {
        $request->validate([
            'coupon' => 'required'
        ]);

        $coupon = Coupon::where('code', $request->post('coupon'))
            ->available()
            ->where(function ($query) {
                $query->whereNull('min_purchase');
                $query->orWhere('min_purchase', '<=', $this->cartService->subtotal());
            })
            ->whereDoesntHave('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->first();

        if (!$coupon) {
            return back()->withErrors('Coupon is not valid');
        }

        $discount = 0;
        if ($coupon->discount_type == "amount") {
            $discount = $coupon->amount;
        } else {
            $discount = $this->cartService->subtotal() * ($coupon->percent / 100);
            if ($discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        }

        session(['coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => round($discount, 2),
        ]]);

        return back();
    }

    public function couponRemove(Request $request)
    {
        $request->session()->forget('coupon');
        return back();
    }

    public function payment(Request $request)
    {
        $request->validate([
            'payment_mode' => 'required|in:cod,online'
        ]);

        $order = Order::query()->find(session('order_id'));
        if (!$order) {
            return to_route('shop.carts.index')->withErrors('Sorry! Something not right, please try to checkout again');
        }

        $order->shipping_charge = $this->cartService->shippingCharge();
        $order->coupon_code = $this->cartService->coupon('code');
        $order->discount = $this->cartService->discount('coupon');
        $order->payment_mode = $request->post('payment_mode');
        $order->save();

        if ($order->payment_mode == 'cod') {
            return to_route('shop.checkout.place.order');
        }
    }

    public function placeOrder()
    {
        $order = Order::query()->find(session('order_id'));
        if (!$order) {
            return to_route('shop.carts.index')->withErrors('Sorry! Something not right, please try to checkout again');
        }
        $order->order_status = config('ashop.order.initial_status', 'order_placed');
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status ?? false,
            'notes' => 'Order has been successfully placed.',
        ]);

        Coupon::find(session('coupon.id'))->users()->attach(auth()->id());

        if ($order->user?->email) {
            dispatch(function () use ($order) {
                Mail::to($order->user?->email)->send(new OrderConfirmationMail($order));
            })->onQueue(config('ashop.queues.emails'))->delay(now()->addMinute());
        }

        $this->cartService->empty();
        return to_route('shop.checkout.confirmation');
    }

    public function confirmation($orderId = null)
    {
        $order = Order::query()->find($orderId ? base64_decode($orderId) : session('order_id'));
        if (!$order) {
            return to_route('shop.carts.index')->withErrors('Sorry! Something not right, please try to checkout again');
        }

        $order->load(['orderProducts' => function ($query) {
            $query->with(['product' => function ($query) {
                $query->with('categories:id,name');
            }]);
        }]);

        return View::first(['shop.checkout.confirmation', 'ashop::shop.checkout.confirmation'])->with([
            'order' => $order,
        ]);
    }
}
