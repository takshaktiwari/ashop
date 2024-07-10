<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\CategoriesResource;
use Takshak\Ashop\Http\Resources\ProductsResource;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\OrderProduct;
use Takshak\Ashop\Models\Shop\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'featured' => 'nullable|boolean',
            'search' => 'nullable|string',
            'limit' => 'nullable|numeric',
            'category' => 'nullable|string',
            'order_by' => 'nullable|in:latest,oldest,price_asc,price_desc,name_asc,name_desc'
        ]);

        $products = Product::query()
            ->active()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('wishlistAuthUser:id,name')
            ->when($request->input('category'), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('categories.id', request('category'));
                    $query->orWhere('categories.name', request('category'));
                    $query->orWhere('categories.slug', request('category'));
                    $query->orWhere('categories.display_name', request('category'));
                });
            })
            ->when($request->input('featured'), fn ($q) => $q->featured())
            ->when($request->input('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('slug', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('subtitle', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('info', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('search_tags', 'LIKE', '%' . request('search') . '%');
                });
            })
            ->when(request('order_by') == 'latest', fn ($q) => $q->latest())
            ->when(request('order_by') == 'oldest', fn ($q) => $q->oldest())
            ->when(request('order_by') == 'price_asc', fn ($q) => $q->orderBy('sell_price', 'ASC'))
            ->when(request('order_by') == 'price_desc', fn ($q) => $q->orderBy('sell_price', 'DESC'))
            ->when(request('order_by') == 'name_asc', fn ($q) => $q->orderBy('name', 'ASC'))
            ->when(request('order_by') == 'name_desc', fn ($q) => $q->orderBy('name', 'DESC'))
            ->paginate(request('limit', 50));

        return ProductsResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['categories' => fn ($q) => $q->active()])
            ->load('images')
            ->load('metas')
            ->load('reviews')
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating')
            ->load('wishlistAuthUser:id,name');

        return ProductsResource::make($product);
    }

    public function popular(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|numeric',
            'category' => 'nullable|string'
        ]);

        $products = Product::query()
            ->active()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('wishlistAuthUser:id,name')
            ->when($request->input('category'), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('categories.id', request('category'));
                    $query->orWhere('categories.name', request('category'));
                    $query->orWhere('categories.slug', request('category'));
                    $query->orWhere('categories.display_name', request('category'));
                });
            })
            ->withCount('orderProduct')
            ->orderBy('order_product_count', 'DESC')
            ->limit(request('limit', 20))
            ->get();

        return ProductsResource::collection($products);
    }
}
