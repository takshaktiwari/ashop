<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\ProductsResource;
use Takshak\Ashop\Models\Shop\Cart;
use Takshak\Ashop\Models\Shop\CategoryProduct;
use Takshak\Ashop\Models\Shop\OrderProduct;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\ProductViewed;
use Takshak\Ashop\Models\Shop\WishlistItem;
use Takshak\Ashop\Traits\AshopProductTrait;

class ProductController extends Controller
{
    use AshopProductTrait;
    public function index(Request $request)
    {
        $validated = $request->validate([
            'featured' => 'nullable|boolean',
            'search' => 'nullable|string',
            'limit' => 'nullable|numeric',
            'category' => 'nullable|string',
            'order_by' => 'nullable|in:latest,oldest,price_asc,price_desc,name_asc,name_desc',
            'paginate' => 'nullable|boolean',
            'with_filter_attributes' => 'nullable|boolean',
        ]);

        $productsQuery = Product::query()
            ->when($request->input('category'), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('categories.id', request('category'));
                    $query->orWhere('categories.name', request('category'));
                    $query->orWhere('categories.slug', request('category'));
                    $query->orWhere('categories.display_name', request('category'));
                });
            })
            ->when($request->input('featured'), fn($q) => $q->featured())
            ->when($request->input('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('slug', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('subtitle', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('info', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('search_tags', 'LIKE', '%' . request('search') . '%');
                });
            });

        $products = (clone $productsQuery)->loadCardDetails()->productsOrderBy(request('order_by'));
        $products = request('paginate', true)
            ? (clone $products)->paginate(request('limit', 50))
            : (clone $products)->limit(request('limit', 50))->get();

        $filterAttributes = [];
        if ($request->boolean('with_filter_attributes')) {
            $filterAttributes = (clone $productsQuery)->select('id')
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

        return ProductsResource::collection($products)->additional([
            'filter_attributes' => $filterAttributes
        ]);
    }

    public function show(Request $request, $slugOrId)
    {
        $request->validate([
            'similar_products' => 'nullable|boolean',
            'similar_products_featured' => 'nullable|boolean',
            'similar_products_limit' => 'nullable|numeric',
            'similar_products_order_by' => 'nullable|in:latest,oldest,price_asc,price_desc,name_asc,name_desc,rand',
            'reviews_limit' => 'nullable|numeric',
        ]);

        $product = Product::query()
            ->where(function ($query) use ($slugOrId) {
                $query->where('slug', $slugOrId);
                $query->orWhere('id', $slugOrId);
            })
            ->with(['categories' => fn($q) => $q->active()])
            ->with('images')
            ->with('metas')
            ->with(['reviews' => fn($q) => $q->limit('5')])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('wishlistAuthUser:id,name')
            ->first();

        abort_if(!$product, 404, 'Product not found');

        $similarProducts = [];
        if ($request->boolean('similar_products')) {
            $similarProducts = Product::query()
                ->loadCardDetails()
                ->whereHas('categories', function ($query) use ($product) {
                    $query->whereIn('categories.id', $product->categories->pluck('id'));
                })
                ->when($request->boolean('similar_products_featured'), fn($q) => $q->featured())
                ->limit(request('similar_products_limit', 10))
                ->productsOrderBy($request->string('similar_products_order', 'latest'))
                ->get();
        }

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

        return ProductsResource::make($product)->additional([
            'similar' => ProductsResource::collection($similarProducts),
            'review_stats' => $this->reviewStats($product)
        ]);
    }

    public function popular(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|numeric',
            'category' => 'nullable|string'
        ]);

        $products = Product::query()
            ->loadCardDetails()
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

    public function similar(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*' => 'required|numeric',
            'limit' => 'nullable|numeric',
            'featured' => 'nullable|boolean',
            'order_by' => 'nullable|in:latest,oldest,price_asc,price_desc,name_asc,name_desc,rand',
        ]);

        $categoriesId = CategoryProduct::whereIn('product_id', $request->input('products'))->pluck('category_id');

        abort_if($categoriesId->isEmpty(), 404, 'Product not found');

        $similarProducts = Product::query()
            ->loadCardDetails()
            ->whereHas('categories', function ($query) use ($categoriesId) {
                $query->whereIn('categories.id', $categoriesId);
            })
            ->when($request->boolean('similar_products_featured'), fn($q) => $q->featured())
            ->limit(request('similar_products_limit', 10))
            ->productsOrderBy($request->string('similar_products_order', 'latest'))
            ->get();

        return ProductsResource::collection($similarProducts);
    }

    public function recommended(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|numeric',
            'featured' => 'nullable|boolean',
            'order_by' => 'nullable|in:latest,oldest,price_asc,price_desc,name_asc,name_desc,rand',
        ]);

        $cartProductsId = Cart::where('user_id', auth()->id())->pluck('product_id');
        $wishlistProductsId = WishlistItem::where('user_id', auth()->id())->pluck('product_id');
        $orderProductsId = OrderProduct::query()
            ->whereHas('order', fn($q) => $q->where('user_id', auth()->id()))
            ->pluck('product_id');

        $productIds = $cartProductsId->merge($wishlistProductsId)->merge($orderProductsId)->unique()->toArray();
        $categoriesId = CategoryProduct::whereIn('product_id', $productIds)->pluck('category_id');

        $products = Product::query()
            ->loadCardDetails()
            ->when(!$categoriesId->isEmpty(), function ($query) use ($categoriesId) {
                $query->whereHas('categories', function ($query) use ($categoriesId) {
                    $query->whereIn('categories.id', $categoriesId);
                });
            })
            ->when($request->boolean('featured') || $categoriesId->isEmpty(), fn($q) => $q->featured())
            ->productsOrderBy($request->string('order_by', 'latest'))
            ->limit(request('limit', 20))
            ->get();

        return ProductsResource::collection($products);
    }

    public function viewedHistory(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|numeric',
        ]);

        $productsIds = ProductViewed::query()
            ->where('user_id', auth()->id())
            ->orWhere('user_ip', request()->ip())
            ->pluck('product_id');

        $products = Product::query()
            ->select('id', 'name', 'slug', 'image_sm')
            ->whereIn('id', $productsIds)
            ->get()
            ->map(function ($product) {
                $product->image_sm = $product->image('sm');
                return $product;
            });

        return response()->json([
            'data' => $products
        ]);
    }
}
