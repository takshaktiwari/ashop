<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Traits\ProductTrait;

class ProductController extends Controller
{
    use ProductTrait;

    public function index(Request $request)
    {
        $products = Product::query()
            ->with('wishlistAuthUser:id,name')
            ->when($request->get('category'), function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('categories.slug', $request->category);
                    $query->orWhere('categories.name', $request->category);
                    $query->orWhere('categories.id', $request->category);
                });
            })
            ->when($request->get('brands_ids'), function ($query) use ($request) {
                $query->whereIn('brand_id', $request->get('brands_ids'));
            })
            ->when($request->get('search'), function ($query) use ($request) {
                $query->where('search_tags', 'LIKE', '%' . $request->search . '%');
            })
            ->when(is_array($request->get('attributes')), function ($query) use ($request) {
                $query->whereHas('metas', function ($query) use ($request) {
                    $query->where('shop_metas.key', 'attributes');
                    foreach ($request->get('attributes') as $attribute) {
                        $query->where('shop_metas.value', 'LIKE', '%' . $attribute . '%');
                    }
                });
            })
            ->when($request->get('short_by') == 'latest', function ($query) {
                $query->orderBy('id', 'DESC');
            })
            ->when($request->get('short_by') == 'oldest', function ($query) {
                $query->orderBy('id', 'ASC');
            })
            ->when($request->get('short_by') == 'name_asc', function ($query) {
                $query->orderBy('name', 'ASC');
            })
            ->when($request->get('short_by') == 'name_desc', function ($query) {
                $query->orderBy('name', 'DESC');
            })
            ->when($request->get('short_by') == 'price_asc', function ($query) {
                $query->orderBy('sell_price', 'ASC');
            })
            ->when($request->get('short_by') == 'price_desc', function ($query) {
                $query->orderBy('sell_price', 'DESC');
            })
            ->active()
            ->paginate(24);

        return View::first(['shop.products.index', 'ashop::shop.products.index'])
            ->with([
                'products'    =>  $products,
            ]);
    }

    public function show(Request $request, Product $product)
    {
        if (!$product->status) {
            return redirect()->route('shop.products.index');
        }
        if (
            $request->get('variants') &&
            is_array($request->get('variants')) &&
            count($request->get('variants'))
        ) {
            Product::query()
            #->select('products.id', 'products.product_id', 'products.name')
            ->where('product_id', $product->product_id ? $product->product_id : $product->id)
            ->get();
            return $request->get('variants');
        }

        $product
            ->load('categories:id,name,slug,display_name,category_id')
            ->load('brand:id,name,slug')
            ->load('images')
            ->load(['variationProperties' => function ($query) {
                $query->with('variation:id,name,display_name');
                $query->with('variant:id,name');
            }])
            ->load('metas');

        if ($product->product_id) {
            $product->load(['productParent' => function ($query) {
                $query->select('id', 'name');
                $query->with(['productVariations' => function ($query) {
                    $query->with('variation:id,name,display_name');
                    $query->with('variant:id,name');
                }]);
            }]);
        } else {
            $product->load(['variationProperties' => function ($query) {
                $query->with('variation:id,name,display_name');
                $query->with('variant:id,name');
            }]);
        }

        $variations = $this->getVariations($product);

        return View::first(['shop.products.show', 'ashop::shop.products.show'])
            ->with([
                'product'    =>  $product,
                'variations'    =>  $variations,
            ]);
    }
}
