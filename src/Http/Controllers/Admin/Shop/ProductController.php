<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Actions\ProductAction;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\ProductImage;
use Takshak\Ashop\Models\Shop\ShopMeta;
use Maatwebsite\Excel\Facades\Excel;
use Takshak\Ashop\Exports\ProductsExport;

class ProductController extends Controller
{
    public function index(Request $request, ProductAction $action)
    {
        $products = $action->filteredProducts(with: [
            'productParent:id,product_id,name',
            'productChildren:id,product_id,name',
        ]);
        $categories = Category::with('parentCategory')->orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();
        $users      = User::orderBy('name')->get();
        return View::first(['admin.shop.products.index', 'ashop::admin.shop.products.index'])
            ->with([
                'products'      =>  $products,
                'categories'    =>  $categories,
                'brands'        =>  $brands,
                'users'         =>  $users,
            ]);
    }

    public function create()
    {
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::query()
            ->with('parent')
            ->orderBy('name', 'ASC')
            ->get();

        return View::first(['admin.shop.products.create', 'ashop::admin.shop.products.create'])
            ->with([
                'categories'    =>  $categories,
                'brands'        =>  $brands,
            ]);
    }

    public function store(Request $request, ProductAction $action)
    {

        $request->validate([
            'image' =>  'required|image',
            'name'          =>  'required|max:250',
            'subtitle'      =>  'nullable|max:254',
            'brand_id'      =>  'nullable|numeric',
            'sku'           =>  'nullable|max:150',
            'stock'         =>  'required|numeric',
            'net_price'     =>  'required|numeric|min:1',
            'sell_price'    =>  'required|numeric|min:1|max:' . $request->post('net_price'),
            'deal_price'    =>  'nullable|numeric|min:1|max:' . $request->post('net_price'),
            'deal_expiry'   =>  'nullable|date|after:today',
            'status'        =>  'required|boolean',
            'featured'      =>  'required|boolean',
            'categories'    =>  'required|array|min:1',
            'checkout_type'    =>  'required',
            'external_url'    =>  'nullable|required_if:checkout_type,external_url',
        ]);

        $product = $action->save($request, new Product());
        $product->categories()->sync($request->post('categories'));

        return redirect()->route('admin.shop.products.edit', [$product])->withSuccess('SUCCESS !! New Model is successfully generated.');
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::with('parent')->orderBy('name', 'ASC')->get();

        return View::first(['admin.shop.products.edit', 'ashop::admin.shop.products.edit'])
            ->with([
                'categories'    =>  $categories,
                'brands'        =>  $brands,
                'product'        =>  $product,
            ]);
    }

    public function update(Request $request, Product $product, ProductAction $action)
    {
        $request->validate([
            'image' =>  'nullable|image',
            'name'          =>  'required|max:250',
            'subtitle'      =>  'nullable|max:254',
            'brand_id'      =>  'nullable|numeric',
            'sku'           =>  'nullable|max:150',
            'stock'         =>  'required|numeric',
            'net_price'     =>  'required|numeric|min:1',
            'sell_price'    =>  'required|numeric|min:1|max:' . $request->post('net_price'),
            'deal_price'    =>  'nullable|numeric|min:1|max:' . $request->post('net_price'),
            'deal_expiry'   =>  'nullable|date|after:today',
            'status'        =>  'required|boolean',
            'featured'      =>  'required|boolean',
            'categories'    =>  'required|array|min:1',
            'checkout_type'    =>  'required',
            'external_url'    =>  'nullable|required_if:checkout_type,external_url',
        ]);

        $product = $action->save($request, $product);
        $product->categories()->sync($request->post('categories'));

        return redirect()->route('admin.shop.products.detail', [$product])->withSuccess('SUCCESS !! Product is successfully updated.');
    }

    public function detail(Product $product)
    {
        return View::first(['admin.shop.products.detail', 'ashop::admin.shop.products.detail'])
            ->with([
                'product'        =>  $product,
            ]);
    }

    public function detailUpdate(Request $request, Product $product)
    {

        $request->validate([
            'metas.cancellable'   =>  'nullable|boolean',
            'metas.cancel_within'   =>  'nullable|numeric',
            'metas.returnable'    =>  'nullable|boolean',
            'metas.return_within'    =>  'nullable|numeric',
            'metas.replaceable'   =>  'nullable|boolean',
            'metas.replace_within'   =>  'nullable|numeric',
            'metas.description'       =>  'nullable|string',
            'metas.m_title'       =>  'nullable|string|max:254',
            'metas.m_keywords'    =>  'nullable|string|max:254',
            'metas.m_description' =>  'nullable|string|max:254',
        ]);

        $product->details()->delete();

        foreach ($request->post('metas') as $name => $meta) {
            $product->details()->updateOrCreate(
                [
                    'key' => 'product_details',
                    'name' => $name
                ],
                ['value' => $meta]
            );
        }

        if ($request->file('metas')) {
            foreach ($request->file('metas') as $name => $meta) {
                $fileImage = $request->file('metas')[$name];
                $filePath = $fileImage->storeAs(
                    'metas/products/',
                    time() . rand() . '.' . $fileImage->extension(),
                    'public'
                );
                $product->metas()->updateOrCreate(
                    [
                        'key' => 'product_details',
                        'name' => $name
                    ],
                    ['value' => storage($filePath)]
                );
            }
        }

        return redirect()->route('admin.shop.products.attributes', [$product])->withSuccess('SUCCESS !! Product Details are successfully updated');
    }

    public function attributes(Product $product)
    {
        $attributes = Category::query()
            ->with('attributes')
            ->whereHas('products', function ($query) use ($product) {
                $query->where('products.id', $product->id);
            })
            ->get()
            ->pluck('attributes')
            ->collapse();

        return View::first(['admin.shop.products.attributes', 'ashop::admin.shop.products.attributes'])
            ->with([
                'product'        =>  $product,
                'attributes'        =>  $attributes,
            ]);
    }

    public function attributesUpdate(Request $request, Product $product)
    {
        $request->validate([
            'metas'    =>  'required|array',
            'metas.*'    =>  'nullable|array'
        ]);

        $product->attributes()->delete();
        foreach ($request->post('metas') as $name => $meta) {
            $product->metas()->updateOrCreate(
                [
                    'key' => 'product_attributes',
                    'name' => $name
                ],
                [
                    'value' => is_array($meta) ? json_encode($meta) : $meta
                ]
            );
        }

        return redirect()->route('admin.shop.products.images', [$product])->withSuccess('Product attributes are updated');
    }

    public function selectedFeatured(Request $request, $value)
    {
        $request->validate([
            'products'  =>  'required|array|min:1'
        ]);

        Product::whereIn('id', $request->post('products'))
            ->update([
                'featured' => $value ? true : false
            ]);
        return back()->withSuccess('SUCCESS !! Featured product list is updated');
    }

    public function selectedDelete(Request $request)
    {
        $request->validate([
            'products'  =>  'required|array|min:1'
        ]);

        return ShopMeta::whereIn('metable_id', $request->products)->get();

        $products = Product::whereIn('id', $request->post('products'))->get();
        $images = ProductImage::whereIn('product_id', $request->post('products'))->get();

        Storage::delete(
            $products->pluck('image_sm')
                ->merge($products->pluck('image_md'))
                ->merge($products->pluck('image_lg'))
                ->merge($images->pluck('image_sm'))
                ->merge($images->pluck('image_md'))
                ->merge($images->pluck('image_lg'))
                ->toArray()
        );

        Product::whereIn('id', $request->post('products'))->delete();

        return redirect()->route('admin.shop.products.index')->withSuccess('SUCCESS !! Products are successfully deleted');
    }

    public function copy(Product $product)
    {
        $newProduct = $product->replicate()->fill([
            'name'  =>  $product->name . ' - copy',
            'slug'  =>  str()->of($product->name)->slug('-')->append('-' . time()),
            'image_sm'  =>  str()->of($product->name)->slug('-')->prepend('products/sm/')->append('-' . time() . '.jpg'),
            'image_md'  =>  str()->of($product->name)->slug('-')->prepend('products/md/')->append('-' . time() . '.jpg'),
            'image_lg'  =>  str()->of($product->name)->slug('-')->prepend('products/')->append('-' . time() . '.jpg'),
        ]);
        $newProduct->save();

        Storage::disk('public')->copy($product->image_sm, $newProduct->image_sm);
        Storage::disk('public')->copy($product->image_md, $newProduct->image_md);
        Storage::disk('public')->copy($product->image_lg, $newProduct->image_lg);

        $newProduct->categories()->sync($product->categories?->pluck('id')?->toArray());

        foreach ($product->details() as $meta) {
            $newProduct->metas()->create([
                'key' => $meta->key,
                'name' => $meta->name,
                'value' => $meta->value,
                'remarks' => $meta->remarks,
            ]);
        }

        foreach ($product->images as $key => $image) {
            $newImage = $image->replicate()->fill([
                'product_id' => $newProduct->id,
                'image_sm'  =>  str()->of($newProduct->slug . '-' . $key)->prepend('products/sm/')->append('.jpg'),
                'image_md'  =>  str()->of($newProduct->slug . '-' . $key)->prepend('products/md/')->append('.jpg'),
                'image_lg'  =>  str()->of($newProduct->slug . '-' . $key)->prepend('products/')->append('.jpg'),
            ]);
            $newImage->save();

            Storage::disk('public')->copy($image->image_sm, $newImage->image_sm->__toString());
            Storage::disk('public')->copy($image->image_md, $newImage->image_md->__toString());
            Storage::disk('public')->copy($image->image_lg, $newImage->image_lg->__toString());
        }

        return redirect()->route('admin.shop.products.edit', [$newProduct])->withSuccess('SUCCESS !! New product has been created');
    }

    public function exportExcel(Request $request)
    {
        $fileName = str()->of('products-' . now())->slug('-')->prepend('exports/')->append('.xlsx');
        Excel::store(new ProductsExport(), $fileName, 'public');
        return Storage::disk('public')->download($fileName);
    }

    public function delete(Product $product)
    {
        Storage::disk('public')->delete($product->image_sm);
        Storage::disk('public')->delete($product->image_md);
        Storage::disk('public')->delete($product->image_lg);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_sm);
            Storage::disk('public')->delete($image->image_md);
            Storage::disk('public')->delete($image->image_lg);
        }

        $product->delete();

        return back()->withSuccess('SUCCESS !! Product has been successfully deleted');
    }
}
