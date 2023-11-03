<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Actions\BrandAction;
use Takshak\Ashop\Models\Shop\Brand;
use Illuminate\Support\Facades\View;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query()
            ->with('categories:id,name')
            ->with('user:id,name')
            ->paginate(50);
        return View::first(['admin.shop.brands.index', 'ashop::admin.shop.brands.index'])
            ->with([
                'brands'   =>  $brands
            ]);
    }

    public function create()
    {
        return View::first(['admin.shop.brands.create', 'ashop::admin.shop.brands.create']);
    }

    public function store(Request $request, BrandAction $action)
    {
        $request->validate([
            'image' => 'nullable|image',
            'brand' => 'required|unique:brands,name'
        ]);

        $action->save($request, new Brand());
        return redirect()->route('admin.shop.brands.index')->withSuccess('SUCCESS !! New Model is successfully generated.');
    }

    public function edit(Brand $brand)
    {
        return View::first(['admin.shop.brands.edit', 'ashop::admin.shop.brands.edit'])->with([
            'brand' => $brand
        ]);
    }

    public function update(Request $request, Brand $brand, BrandAction $action)
    {
        $action->save($request, $brand);
        return redirect()->route('admin.shop.brands.index')->withSuccess('SUCCESS !! Brand is successfully updated.');
    }

    public function statusToggle(Brand $brand)
    {
        $brand->update(['status' => $brand->status ? false : true]);
        return back()->withSuccess('SUCCESS !! Brand is successfully updated');
    }

    public function featuredToggle(Brand $brand)
    {
        $brand->update(['featured' => $brand->featured ? false : true]);
        return back()->withSuccess('SUCCESS !! Brand is successfully updated');
    }

    public function destroy(Brand $brand)
    {
        try {
            Storage::disk('public')->delete([
                $brand->image_lg,
                $brand->image_md,
                $brand->image_sm,
            ]);
            $brand->delete();
            return redirect()->route('admin.shop.brands.index')->withSuccess('SUCCESS !! Brand is successfully deleted.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.shop.brands.index')->withErrors('Unable to delete brand. Some products may contain this brand. Remove this brand from all products and try again.');
        }
    }
}
