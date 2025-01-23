<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\ProductViewed;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $productQuery = Product::query()
            ->when($request->get('category'), function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('categories.slug', $request->category);
                    $query->orWhere('categories.name', $request->category);
                    $query->orWhere('categories.id', $request->category);
                });
            })
            ->when($request->get('brands_ids'), function ($query) use ($request) {
                $query->whereIn('brand_id', $request->get('brands_ids'));
            })
            ->when($request->get('brand_id'), function ($query) use ($request) {
                $query->where('brand_id', $request->get('brand_id'));
            })
            ->when($request->get('search'), function ($query) use ($request) {
                $query->where('search_tags', 'LIKE', '%' . $request->search . '%');
            })
            ->when($request->get('featured'), function ($query) {
                $query->featured();
            });

        $products = (clone $productQuery)->loadCardDetails()
            ->when(is_array($request->get('attributes')), function ($query) use ($request) {
                $query->whereHas('metas', function ($query) use ($request) {
                    $query->where('shop_metas.key', 'product_attributes');
                    foreach ($request->get('attributes') as $attribute) {
                        $query->where('shop_metas.value', 'LIKE', '%' . $attribute . '%');
                    }
                });
            })
            ->productsOrderBy($request->get('short_by'))
            ->paginate(config('ashop.sections.products.items', 24))
            ->withQueryString();

        $filterAttributes = [];
        if (config('ashop.sections.products.sidebar', true)) {
            $filterAttributes = (clone $productQuery)->select('id')
                ->with('attributes:metable_type,metable_id,name,key,value')
                ->get()
                ->pluck('attributes')
                ->collapse()
                ->map(function ($meta) {
                    return [
                        'name' => $meta->name,
                        'value' => json_decode($meta->value, true)
                    ];
                })
                ->sortBy('name')
                ->groupBy('name')
                ->map(function ($attribute) {
                    return collect($attribute)->pluck('value')->flatten()->unique()->values();
                })
                ->take(10);
        }

        $category = null;
        if ($request->get('category')) {
            $category = Category::query()
                ->active()
                ->with('metas')
                ->with('children')
                ->where(function ($query) use ($request) {
                    $query->where('id', $request->get('category'));
                    $query->orWhere('slug', $request->get('category'));
                })
                ->first();
        }

        return View::first(['shop.products.index', 'ashop::shop.products.index'])
            ->with([
                'products'    =>  $products,
                'category'    =>  $category,
                'filterAttributes' => $filterAttributes
            ]);
    }

    public function show(Request $request, Product $product)
    {
        if (!$product->status) {
            return redirect()->route('shop.products.index');
        }

        $product
            ->load('categories:id,name,slug,display_name,category_id')
            ->load('brand:id,name,slug')
            ->load('images')
            ->load('metas')
            ->loadCount('reviews');

        ProductViewed::query()
            ->where('product_id', $product->id)
            ->where(function ($query) {
                $query->where('user_id', auth()->id());
                $query->orWhere('user_ip', request()->ip());
            })
            ->delete();
        ProductViewed::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'user_ip' => $request->ip()
        ]);

        return View::first(['shop.products.show', 'ashop::shop.products.show'])
            ->with([
                'product'    =>  $product,
            ]);
    }

    public function reviews(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->loadCardDetails()->first();

        return View::first(['shop.products.reviews', 'ashop::shop.products.reviews'])
            ->with([
                'product'    =>  $product,
            ]);
    }
}
