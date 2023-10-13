<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Variant;
use Takshak\Ashop\Models\Shop\Variation;

class VariationController extends Controller
{
    public function index(Request $request)
    {
        $variations = Variation::withCount('variants')->latest()->paginate(50);
        return View::first(['admin.shop.variations.index', 'ashop::admin.shop.variations.index'])
            ->with([
                'variations'   =>  $variations
            ]);
    }

    public function create()
    {
        return View::first(['admin.shop.variations.create', 'ashop::admin.shop.variations.create']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  =>  'required',
            'display_name' => 'required',
            'remarks'   =>  'nullable|max:255',
            'variants' => 'required|array',
            'variants.name' => 'required|array',
            'variants.key'  =>  'required|array',
            'variants.remarks' => 'required|array'
        ]);

        $variation = new Variation();
        $variation->name = $request->name;
        $variation->slug = str()->of($variation->name);
        $variation->display_name = $request->display_name;
        $variation->remarks = $request->input('remarks');
        $variation->save();

        Variant::query()
            ->where('variation_id', $variation->id)
            ->whereNotIn('name', $request->variants['name'])
            ->delete();

        foreach ($request->variants['name'] as $key => $name) {
            Variant::updateOrCreate(
                [
                    'variation_id' => $variation->id,
                    'name'  =>  $name,
                    'slug' => str()->of($name)->slug()
                ],
                [
                    'key' => $request->variants['key'][$key],
                    'remarks' => $request->variants['remarks'][$key]
                ]
            );
        }

        return redirect()->route('admin.shop.variations.show', [$variation])->withSuccess('SUCCESS !! New variation is successfully added.');
    }

    public function show(Variation $variation)
    {
        return View::first(['admin.shop.variations.show', 'ashop::admin.shop.variations.show'])
            ->with([
                'variation'   =>  $variation
            ]);
    }

    public function edit(Variation $variation)
    {
        return View::first(['admin.shop.variations.edit', 'ashop::admin.shop.variations.edit'])->with([
            'variation' => $variation
        ]);
    }

    public function update(Request $request, Variation $variation)
    {
        $request->validate([
            'name'  =>  'required',
            'display_name' => 'required',
            'remarks'   =>  'nullable|max:255',
            'variants' => 'required|array',
            'variants.name' => 'required|array',
            'variants.key'  =>  'required|array',
            'variants.remarks' => 'required|array'
        ]);

        $variation->name = $request->name;
        $variation->slug = str()->of($variation->name);
        $variation->display_name = $request->display_name;
        $variation->remarks = $request->input('remarks');
        $variation->save();

        Variant::query()
            ->where('variation_id', $variation->id)
            ->whereNotIn('name', $request->variants['name'])
            ->delete();

        foreach ($request->variants['name'] as $key => $name) {
            if (!$name) {
                continue;
            }
            Variant::updateOrCreate(
                [
                    'variation_id' => $variation->id,
                    'name'  =>  $name,
                    'slug' => str()->of($name)->slug()
                ],
                [
                    'key' => $request->variants['key'][$key],
                    'remarks' => $request->variants['remarks'][$key]
                ]
            );
        }

        return redirect()->route('admin.shop.variations.show', [$variation])->withSuccess('SUCCESS !! Variation is successfully updated.');
    }

    public function destroy(Variation $variation)
    {
        Variant::where('variation_id', $variation->id)->delete();
        $variation->delete();
        return redirect()->route('admin.shop.variations.index')->withSuccess('SUCCESS !! Variation is successfully seleted.');
    }

    public function variantsDelete(Variant $variant)
    {
        $variant->delete();
        return back()->withSuccess('SUCCESS !! Variant is successfully deleted.');
    }
}
