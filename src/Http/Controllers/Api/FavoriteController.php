<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\ProductsResource;
use Takshak\Ashop\Models\Shop\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        $products = auth()->user()->favorites;
        return ProductsResource::collection($products);
    }

    public function itemToggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric'
        ]);

        $product = Product::find($request->input('product_id'));
        abort_if(!$product, 404, 'Product not found');

        if (auth()->user()->favorites?->pluck('id')->contains($product->id)) {
            auth()->user()->favorites()->detach($product->id);
            $message = 'SUCCESS !! Item is removed from your wish list.';
        } else {
            auth()->user()->favorites()->attach($product->id);
            $message = 'SUCCESS !! Item is added to your wish list.';
        }

        return response()->json(['data' => ['message' => $message]]);
    }
}
