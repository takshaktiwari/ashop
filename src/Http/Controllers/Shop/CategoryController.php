<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->withCount('children')
            ->when($request->get('category_id'), function ($query) {
                $query->where('category_id', request('category_id'));
            })
            ->when(!$request->get('category_id'), function ($query) {
                $query->isParent();
            })
            ->paginate(24)
            ->withQueryString();

        return View::first(['shop.categories.index', 'ashop::shop.categories.index'])
            ->with([
                'categories'    =>  $categories,
            ]);
    }

    public function list(Request $request)
    {
        $categories = Category::query()
            ->with('children')
            ->isParent()
            ->get();

        return View::first(['shop.categories.list', 'ashop::shop.categories.list'])
            ->with([
                'categories'    =>  $categories,
            ]);
    }
}
