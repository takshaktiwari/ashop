<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Models\Shop\Category;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Actions\CategoryAction;
use Takshak\Ashop\Models\Shop\Attribute;
use Takshak\Ashop\Models\Shop\Brand;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->with('parent:id,name')
            ->withCount('children')
            ->when($request->get('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->get('search') . '%');
                    $query->orWhere('name', 'like', '%' . $request->get('search') . '%');
                    $query->orWhere('display_name', 'like', '%' . $request->get('search') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->get('search') . '%');
                });
            })
            ->when($request->get('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->get('category_id'));
            })
            ->when(in_array($request->get('status'), ['0', '1']), function ($query) use ($request) {
                $query->where('status', $request->get('status'));
            })
            ->when(in_array($request->get('featured'), ['0', '1']), function ($query) use ($request) {
                $query->where('featured', $request->get('featured'));
            })
            ->paginate(50)
            ->withQueryString();

        return View::first(['admin.shop.categories.index', 'ashop::admin.shop.categories.index'])
            ->with([
                'categories'   =>  $categories
            ]);
    }

    public function create()
    {
        $categories = Category::with('parent')->get();
        return View::first(['admin.shop.categories.create', 'ashop::admin.shop.categories.create'])
            ->with([
                'categories'   =>  $categories
            ]);
    }

    public function store(Request $request, CategoryAction $action)
    {
        $request->validate([
            'name'  =>  'required|unique:categories,name',
            'display_name'  =>  'required',
            'image_file'    =>  'required|image',
            'category_id'    =>  'nullable|numeric'
        ]);

        $category = new Category();
        $action->save($request, $category);

        return redirect()->route('admin.shop.categories.details', [$category])->withSuccess('SUCCESS !! New Model is successfully generated.');
    }

    public function edit(Category $category)
    {
        $categories = Category::with('parentCategory')->where('id', '!=', $category->id)->get();
        return View::first(['admin.shop.categories.edit', 'ashop::admin.shop.categories.edit'])
            ->with([
                'category'   =>  $category,
                'categories'   =>  $categories,
            ]);
    }

    public function update(Request $request, Category $category, CategoryAction $action)
    {
        $request->validate([
            'name'  =>  'required|unique:categories,name,' . $category->id,
            'display_name'  =>  'required',
            'image_file'    =>  'nullable|image',
            'category_id'    =>  'nullable|numeric'
        ]);

        $action->save($request, $category);

        return redirect()->route('admin.shop.categories.details', [$category])->withSuccess('SUCCESS !! New Model is successfully generated.');
    }

    public function details(Category $category)
    {
        return View::first(['admin.shop.categories.details', 'ashop::admin.shop.categories.details'])
            ->with([
                'category'   =>  $category
            ]);
    }

    public function detailsUpdate(Request $request, Category $category)
    {
        $request->validate([
            'metas' => 'required|array',
            'metas.min_order_qty' => 'nullable|numeric',
            'metas.cod' => 'nullable|numeric',
            'metas.cancellable' => 'nullable|numeric',
            'metas.cancel_within' => 'nullable|numeric',
            'metas.returnable' => 'nullable|numeric',
            'metas.return_within' => 'nullable|numeric',
            'metas.replaceable' => 'nullable|numeric',
            'metas.replace_within' => 'nullable|numeric',
            'metas.banner' => 'nullable|image',
        ]);

        foreach ($request->post('taxes') ?? [] as $tax => $value) {
            $category->metas()->create([
                'key' => 'taxes',
                'name' => $tax,
                'value' => $value,
            ]);
        }
        $category->metas()->whereNotIn('name', array_keys($request->post('taxes') ?? []))->delete();

        foreach ($request->post('metas') as $name => $meta) {
            $category->metas()->create([
                'key' => $name,
                'name' => $name,
                'value' => $meta,
            ]);
        }

        if ($request->file('metas')) {
            foreach ($request->file('metas') as $name => $meta) {
                $fileImage = $request->file('metas')[$name];
                $filePath = $fileImage->storeAs(
                    'metas/categories',
                    time() . rand() . '.' . $fileImage->extension(),
                    'public'
                );
                $category->metas()->updateOrCreate(
                    [
                        'name' => $name,
                        'key' => 'details',
                        'is_file' => true,
                    ],
                    [
                        'value' => $filePath,
                        'disk' => 'public'
                    ]
                );
            }
        }

        return redirect()->route('admin.shop.categories.brands', [$category])
            ->withSuccess('SUCCESS !! Category detail is successfully updated');
    }

    public function brands(Category $category)
    {
        $brands = Brand::orderBy('name')->get();
        return View::first(['admin.shop.categories.brands', 'ashop::admin.shop.categories.brands'])
            ->with([
                'category'   =>  $category,
                'brands'   =>  $brands,
            ]);
    }

    public function brandsUpdate(Request $request, Category $category)
    {
        $request->validate([
            'brands' => 'required'
        ]);

        $category->brands()->sync($request->post('brands'));
        return redirect()->route('admin.shop.categories.attributes', [$category])
            ->withSuccess('SUCCESS !! Category detail is successfully updated');
    }

    public function attributes(Category $category)
    {
        $attributes = Attribute::orderBy('name', 'ASC')->get();
        return View::first(['admin.shop.categories.attributes', 'ashop::admin.shop.categories.attributes'])
            ->with([
                'category'   =>  $category,
                'attributes'   =>  $attributes,
            ]);
    }

    public function attributesUpdate(Request $request, Category $category)
    {
        $request->validate([
            'attributes'    =>  'nullable|array'
        ]);

        $category->attributes()->sync($request->post('attributes'));

        return redirect()->route('admin.shop.categories.edit', [$category])
            ->withSuccess('SUCCESS !! Category attributes is successfully updated');
    }

    public function statusToggle(Category $category)
    {
        $category->update(['status' => $category->status ? false : true]);
        return back()->withSuccess('SUCCESS !! Category status is successfully updated');
    }

    public function featuredToggle(Category $category)
    {
        $category->update(['featured' => $category->featured ? false : true]);
        return back()->withSuccess('SUCCESS !! Category featured is successfully updated');
    }

    public function isTopToggle(Category $category)
    {
        $category->update(['is_top' => $category->is_top ? false : true]);
        return back()->withSuccess('SUCCESS !! Category featured is successfully updated');
    }
}
