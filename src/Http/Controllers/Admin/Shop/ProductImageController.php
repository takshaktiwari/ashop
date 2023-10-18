<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Models\Shop\Product;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\ProductImage;
use Takshak\Imager\Facades\Imager;

class ProductImageController extends Controller
{
    public function index(Product $product)
    {
        return View::first(['admin.shop.products.images', 'ashop::admin.shop.products.images'])
            ->with([
                'product'    =>  $product,
            ]);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'title'         =>  'required|max:250',
            'product_images'  =>  'required|array',
        ]);

        foreach ($request->file('product_images') as $image) {
            $productImage = new ProductImage();
            $productImage->product_id = $product->id;
            $productImage->title = $request->post('title');

            $fileName = str()->of(microtime())->slug('-') . '.' . $image->extension();
            $productImage->image_lg = 'products/' . $fileName;
            $productImage->image_md = 'products/md/' . $fileName;
            $productImage->image_sm = 'products/sm/' . $fileName;

            $imgWidth = config('shopze.products.images.width', 800);
            $imgHeight = config('shopze.products.images.height', 900);
            Imager::init($image)
                ->resizeFit($imgWidth, $imgHeight)->inCanvas('#fff')
                ->basePath(Storage::disk('public')->path('/'))
                ->save($productImage->image_lg)
                ->save($productImage->image_md, $imgWidth / 2)
                ->save($productImage->image_sm, $imgWidth / 4);

            $productImage->save();
        }

        return redirect()->route('admin.shop.products.images', [$product])->withSuccess('SUCCESS !! Images are successfully uploaded');
    }

    public function destroy(ProductImage $productImage)
    {
        $productId = $productImage->product_id;
        Storage::delete([
            $productImage->image_lg,
            $productImage->image_md,
            $productImage->image_sm,
        ]);
        $productImage->delete();

        return redirect()->route('admin.shop.products.images', [$productId])->withSuccess('Images are deleted');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*'  =>  'numeric'
        ]);

        $images = ProductImage::whereIn('id', $request->post('images'))->get();

        Storage::delete(
            $images->pluck('image_sm')
                ->merge($images->pluck('image_md'))
                ->merge($images->pluck('image_lg'))
                ->toArray()
        );

        ProductImage::whereIn('id', $request->post('images'))->delete();
        return back()->withErrors('SUCCESS !! Product images is successfully deleted.');
    }
}
