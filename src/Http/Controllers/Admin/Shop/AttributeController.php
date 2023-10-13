<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Takshak\Ashop\Models\Shop\Attribute;
use Illuminate\Support\Facades\View;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        $attributes = Attribute::paginate(50);
        return View::first(['admin.shop.attributes.index', 'ashop::admin.shop.attributes.index'])
            ->with([
                'attributes'   =>  $attributes
            ]);
    }

    public function create()
    {
        return View::first(['admin.shop.attributes.create', 'ashop::admin.shop.attributes.create']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  =>  'required|unique:attributes,name',
            'display_name' => 'required',
            'remarks'   =>  'nullable|max:255',
            'options' => 'required|array'
        ]);

        $options = collect($request->options)->filter(fn ($i) => $i);

        $attribute = new Attribute();
        $attribute->name = $request->name;
        $attribute->slug = str()->of($attribute->name);
        $attribute->display_name = $request->display_name;
        $attribute->remarks = $request->input('remarks');
        $attribute->options = $options;
        $attribute->save();

        return redirect()->route('admin.shop.attributes.show', [$attribute])->withSuccess('SUCCESS !! New attribute is successfully added.');
    }

    public function show(Attribute $attribute)
    {
        return View::first(['admin.shop.attributes.show', 'ashop::admin.shop.attributes.show'])
            ->with([
                'attribute'   =>  $attribute
            ]);
    }

    public function edit(Attribute $attribute)
    {
        return View::first(['admin.shop.attributes.edit', 'ashop::admin.shop.attributes.edit'])
            ->with([
                'attribute'   =>  $attribute
            ]);
    }

    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name'  =>  'required|unique:attributes,name,' . $attribute->id,
            'display_name' => 'required',
            'remarks'   =>  'nullable|max:255',
            'options' => 'required|array'
        ]);

        $options = collect($request->options)->filter(fn ($i) => $i);

        $attribute->name = $request->name;
        $attribute->display_name = $request->display_name;
        $attribute->options = $options;
        $attribute->remarks = $request->input('remarks');
        $attribute->save();

        return redirect()->route('admin.shop.attributes.show', [$attribute])->withSuccess('SUCCESS !! Attribute is successfully updated.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.shop.attributes.index')->withSuccess('SUCCESS !! Attribute is successfully deleted.');
    }
}
