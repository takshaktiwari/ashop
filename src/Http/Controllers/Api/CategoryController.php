<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\CategoriesResource;
use Takshak\Ashop\Models\Shop\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search'    => 'nullable',
            'category_id' => 'nullable|numeric',
            'limit'     =>  'nullable|numeric',
            'count_products' => 'nullable|boolean',
            'with_children' => 'nullable|boolean',
        ]);

        $categories = Category::query()
            ->active()
            ->when($request->input('with_children'), function ($query) {
                $query->active()->with(['children' => function ($query) {
                    $query->active()->with(['children' => function ($query) {
                        $query->active()->with(['children' => function ($query) {
                            $query->active()->with('children')->withCount('products');
                        }]);
                        $query->withCount('products');
                    }]);
                    $query->withCount('products');
                }]);
            })
            ->when($request->input('count_products'), function ($query) {
                $query->withCount(['products' => function ($query) {
                    $query->active()->when(request('featured_products'), fn($q) => $q->featured());
                }]);
            })
            ->when($request->get('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('id', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('name', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('slug', 'LIKE', '%' . request('search') . '%');
                    $query->orWhere('description', 'LIKE', '%' . request('search') . '%');
                });
            })
            ->when($request->get('category_id'), function ($query) {
                $query->where('category_id', request('category_id'));
            })
            ->when(!$request->get('category_id'), function ($query) {
                $query->isParent();
            })
            ->when($request->get('featured'), fn($q) => $q->where('featured', true))
            ->when($request->get('is_top'), fn($q) => $q->where('is_top', true))
            ->when($request->get('featured') == '0', fn($q) => $q->where('featured', false))
            ->when($request->get('is_top') == '0', fn($q) => $q->where('is_top', false))
            ->paginate(request('limit', 50));

        return CategoriesResource::collection($categories);
    }

    public function show(Request $request, Category $category)
    {
        $request->validate([
            'count_products' => 'nullable|boolean',
            'with_children' => 'nullable|boolean',
            'with_products' => 'nullable|boolean',
            'products_featured' => 'nullable|boolean',
            'products_limit' => 'nullable|numeric',
        ]);

        abort_if(!$category->status, 404, 'Category is not active');

        if (request('with_products')) {
            $category->load(['products' => function ($query) {
                $query->active()
                    ->when(request('products_featured'), fn($q) => $q->featured())
                    ->limit(request('products_limit', 10));
            }]);
        }

        if (request('count_products')) {
            $category->loadCount(['products' => function ($query) {
                $query->active()
                    ->when(request('products_featured'), fn($q) => $q->featured());
            }]);
        }

        if (request('with_children')) {
            $category->load(['children' => function ($query) {
                $query->active()->with(['children' => function ($query) {
                    $query->active()->with(['children' => function ($query) {
                        $query->active()->with(['children' => function ($query) {
                            $query->active()
                                ->with('children')
                                ->when(request('count_products'), fn($q) => $q->withCount('products'));
                        }]);
                        $query->when(request('count_products'), fn($q) => $q->withCount('products'));
                    }]);
                    $query->when(request('count_products'), fn($q) => $q->withCount('products'));
                }]);
                $query->when(request('count_products'), fn($q) => $q->withCount('products'));
            }]);
        }

        return CategoriesResource::make($category);
    }

    public function withProducts(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'featured' => 'nullable|boolean',
            'is_top' => 'nullable|boolean',
            'limit' => 'nullable|numeric',
            'products' => 'nullable|array',
            'products.limit' => 'nullable|numeric',
            'products.featured' => 'nullable|boolean',
            'products.order_by' => 'nullable|in:latest,oldest,price_asc,price_desc,name_asc,name_desc',
        ]);

        $categories = Category::query()
            ->active()
            ->when($request->boolean('is_top'), fn($q) => $q->where('is_top', true))
            ->when(!$request->boolean('is_top'), fn($q) => $q->where('is_top', false))
            ->when($request->boolean('featured'), fn($q) => $q->where('featured', true))
            ->when(!$request->boolean('featured'), fn($q) => $q->where('featured', false))
            ->when($request->input('categories'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->whereIn('id', $request->input('categories'));
                    $query->orWhereIn('slug', $request->input('categories'));
                });
            })
            ->with(['products' => function ($query) use ($request) {
                $query->loadCardDetails()
                    ->when($request->input('products.featured'), fn($q) => $q->featured())
                    ->when($request->input('products.order_by') == 'latest', fn($q) => $q->latest())
                    ->when($request->input('products.order_by') == 'oldest', fn($q) => $q->oldest())
                    ->when($request->input('products.order_by') == 'price_asc', fn($q) => $q->orderBy('sell_price', 'ASC'))
                    ->when($request->input('products.order_by') == 'price_desc', fn($q) => $q->orderBy('sell_price', 'DESC'))
                    ->when($request->input('products.order_by') == 'name_asc', fn($q) => $q->orderBy('name', 'ASC'))
                    ->when($request->input('products.order_by') == 'name_desc', fn($q) => $q->orderBy('name', 'DESC'))
                    ->limit(request('products.limit', 10));
            }])
            ->limit(request('limit', 5))
            ->get();

        return CategoriesResource::collection($categories);
    }
}
