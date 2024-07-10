<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\CartsResource;
use Takshak\Ashop\Models\Shop\Cart;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Services\CartService;

class CartController extends Controller
{
    public function index()
    {
        $carts = (new CartService)->items();
        return CartsResource::collection($carts);
    }

    public function store(Request $request) {
        $request->validate([
            'quantity' => 'nullable|numeric|min:1',
            'product_id' => 'required|numeric'
        ]);

        $product = Product::find($request->input('product_id'));

        if (!$product->status || !$product->stock) {
            return back()->withErrors('SORRY !! This product is not available or out of stock. Please try after sometime.');
        }

        $productQty = $request->input('quantity') ? $request->input('quantity') : 1;

        $cart = Cart::query()
            ->where(function ($query) {
                $query->where('user_id', auth()->id());
                $query->orWhere('user_ip', request()->ip());
            })
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {

            $cart->quantity = $cart->quantity + $productQty;
            $min_purchase = $cart->product->getDetail('min_purchase') ? $cart->product->getDetail('min_purchase') : 1;
            $max_purchase = $cart->product->getDetail('max_purchase') ? $cart->product->getDetail('max_purchase') : 10;

            if ($cart->quantity < $min_purchase) {
                $cart->quantity = $min_purchase;
            }

            if ($cart->quantity > $max_purchase) {
                $cart->quantity = $max_purchase;
            }

            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'user_ip' => $request->ip(),
                'quantity' => $productQty,
                'product_id' => $product->id
            ]);
        }

        return CartsResource::make($cart);
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required']);

        $quantity = $request->quantity;
        $min_purchase = $cart->product->getDetail('min_purchase') ? $cart->product->getDetail('min_purchase') : 1;
        $max_purchase = $cart->product->getDetail('max_purchase') ? $cart->product->getDetail('max_purchase') : 10;

        if ($quantity < $min_purchase) {
            $quantity = $min_purchase;
        }

        if ($quantity > $max_purchase) {
            $quantity = $max_purchase;
        }

        $cart->quantity = $quantity;
        $cart->save();

        return CartsResource::make($cart);
    }

    public function delete(Cart $cart)
    {
        $cart->delete();
        return response()->json(['data' => ['message' => 'Cart has been deleted']]);
    }
}
