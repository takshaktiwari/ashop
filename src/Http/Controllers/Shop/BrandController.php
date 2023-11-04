<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Models\Shop\Brand;
use Illuminate\Support\Facades\View;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query()
            ->active()
            ->when($request->get('category'), function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('categories.id', $request->get('category'));
                    $query->orWhere('categories.name', 'LIKE', '%' . $request->get('category') . '%');
                    $query->orWhere('categories.slug', 'LIKE', '%' . $request->get('category') . '%');
                });
            })
            ->when($request->get('order') == 'latest', fn ($q) => $q->latest())
            ->when($request->get('order') == 'oldest', fn ($q) => $q->oldest())
            ->when($request->get('order') == 'rand', fn ($q) => $q->inRandomOrder())
            ->when(!$request->get('order'), fn ($q) => $q->orderBy('name', 'ASC'))
            ->paginate(12);

        return View::first(['shop.brands.index', 'ashop::shop.brands.index'])
            ->with([
                'brands'    =>  $brands,
            ]);
    }
}
