<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Product;

class WishlistController extends Controller
{
    public function items()
    {
        return View::first(['shop.user.wishlist.items', 'ashop::shop.user.wishlist.items']);
    }

    public function itemToggle(Product $product)
    {
        if (auth()->user()->wishlistItems?->pluck('id')->contains($product->id)) {
            auth()->user()->wishlistItems()->detach($product->id);
            return back()->withSuccess('SUCCESS !! Item is removed from your wish list.');
        } else {
            auth()->user()->wishlistItems()->attach($product->id);
            return back()->withSuccess('SUCCESS !! Item is added to your wish list.');
        }
    }
}
