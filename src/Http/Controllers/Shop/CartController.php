<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Cart;
use Takshak\Ashop\Models\Shop\Product;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::query()
            ->with(['product' => function ($query) {
                $query->with('metas');
                $query->with('wishlistAuthUser');
                $query->with('categories:id');
            }])
            ->has('product')
            ->where('user_id', auth()->id())
            ->orWhere('user_ip', request()->ip())
            ->get();

        return View::first(['shop.carts.index', 'ashop::shop.carts.index'])
            ->with([
                'carts'    =>  $carts
            ]);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|numeric|min:1'
        ]);

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
            Cart::create([
                'user_id' => auth()->id(),
                'user_ip' => $request->ip(),
                'quantity' => $productQty,
                'product_id' => $product->id
            ]);
        }

        $alertMessage = View::first(['shop._alerts.shop-cart-added', 'ashop::shop._alerts.shop-cart-added'])
            ->with([
                'product'    =>  $product
            ])
            ->render();

        return back()->withTitle('Product is added to your cart.')->withSuccess($alertMessage);
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

        return back();
    }

    public function delete(Cart $cart)
    {
        $cart->delete();
        return to_route('shop.carts.index');
    }
}
