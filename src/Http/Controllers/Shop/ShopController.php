<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Product;

class ShopController extends Controller
{
    public function index()
    {
        if (config('ashop.sections.home.featured_categories.status', true)) {
            $featuredCategories = Category::query()
                ->with(['products' => function ($query) {
                    $query->loadCardDetails()
                        ->limit(config('ashop.sections.home.featured_categories.products'));
                }])
                ->has('products')->active()->featured()
                ->limit(config('ashop.sections.home.featured_categories.items'))
                ->get();
        }

        if (config('ashop.sections.home.top_categories.status', true)) {
            $topCategories = Category::query()
                ->with(['products' => function ($query) {
                    $query->loadCardDetails()
                        ->limit(config('ashop.sections.home.top_categories.products'));
                }])
                ->has('products')->active()->isTop()
                ->limit(config('ashop.sections.home.top_categories.items'))
                ->get();
        }

        if (config('ashop.sections.home.popular.status', true)) {
            $popularProducts = Product::query()
                ->loadCardDetails()
                ->withCount('orderProduct')
                ->orderBy('order_product_count', 'DESC')
                ->limit(config('ashop.sections.home.popular.items'))
                ->get();
        }

        return View::first(['shop.index', 'ashop::shop.index'])->with([
            'featuredCategories' => isset($featuredCategories) ? $featuredCategories : [],
            'topCategories' => isset($topCategories) ? $topCategories : [],
            'popularProducts' => isset($popularProducts) ? $popularProducts : []
        ]);
    }
}
