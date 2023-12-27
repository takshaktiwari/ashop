<?php

namespace Takshak\Ashop\Actions;

use Illuminate\Support\Facades\Storage;
use Takshak\Imager\Facades\Imager;
use Takshak\Ashop\Models\Shop\Product;

class ProductAction
{
    public function save($request, $product)
    {
        $product->name          =  $request->post('name');
        $product->subtitle      =  $request->post('subtitle');
        $product->brand_id      =  $request->post('brand_id');
        $product->sku           =  $request->post('sku');
        $product->stock         =  $request->post('stock');
        $product->net_price     =  $request->post('net_price');
        $product->sell_price    =  $request->post('sell_price');
        $product->deal_price    =  $request->post('deal_price');
        $product->deal_expiry   =  $request->post('deal_expiry');
        $product->status        =  $request->post('status');
        $product->featured      =  $request->post('featured');
        $product->info          =  $request->post('info');
        $product->checkout_type =  $request->post('checkout_type');
        $product->external_url  =  $request->post('external_url');
        $product->user_id       =  auth()->id();

        $product->slug          = $product->slug
            ? $product->slug
            : str()->of($request->post('name'))->slug('-') . time();

        if ($request->file('image')) {
            $product->image_sm  = 'products/sm/' . $product->slug . '.jpg';
            $product->image_md  = 'products/md/' . $product->slug . '.jpg';
            $product->image_lg  = 'products/' . $product->slug . '.jpg';

            $imgWidth = config('ashop.products.images.width', 800);
            $imgHeight = config('ashop.products.images.height', 900);
            Imager::init($request->file('image'))
                ->resizeFit($imgWidth, $imgHeight)->inCanvas('#fff')
                ->basePath(Storage::disk('public')->path('/'))
                ->save($product->image_lg)
                ->save($product->image_md, $imgWidth / 2)
                ->save($product->image_sm, $imgWidth / 4);
        }

        $product->save();
        return $product;
    }

    public function filteredProducts($paginate = 50, $withQueryString = true, $with = [])
    {
        $query   = Product::with(['categories']);
        if (request()->get('search')) {
            $query->where(function ($query) {
                $query->where('tags', 'LIKE', '%' . request()->get('search') . '%');
                $query->orWhere('name', 'LIKE', '%' . request()->get('search') . '%');
                $query->orWhere('slug', 'LIKE', '%' . request()->get('search') . '%');
            });
        }
        if (request()->get('category')) {

            $query->whereHas('categories', function ($query) {
                $query->where('categories.id', request()->get('category'));
            });
        }
        if (request()->get('product_id')) {
            $query->where('product_id', request()->get('product_id'));
        }
        if (request()->get('brand_id')) {
            $query->where('brand_id', request()->get('brand_id'));
        }
        if (request()->get('min-net_price')) {
            $query->where('net_price', '>=', request()->get('min-net_price'));
        }
        if (request()->get('max-net_price')) {
            $query->where('net_price', '<=', request()->get('max-net_price'));
        }
        if (request()->get('min-sell_price')) {
            $query->where('sell_price', '>=', request()->get('min-sell_price'));
        }
        if (request()->get('max-sell_price')) {
            $query->where('sell_price', '<=', request()->get('max-sell_price'));
        }
        if (request()->get('min-stock')) {
            $query->where('stock', '>=', request()->get('min-stock'));
        }
        if (request()->get('max-stock')) {
            $query->where('stock', '<=', request()->get('max-stock'));
        }
        if (request()->get('status') != null) {
            $query->where('status', request()->get('status'));
        }
        if (request()->get('featured') != null) {
            $query->where('featured', request()->get('featured'));
        }
        if (request()->get('user_id')) {
            $query->where('user_id', request()->get('user_id'));
        }
        if ($with && count($with)) {
            $query->with($with);
        }

        if ($withQueryString) {
            return $query->orderBy('products.id', 'DESC')->paginate($paginate)->withQueryString();
        } else {
            return $query->orderBy('products.id', 'DESC')->paginate($paginate);
        }
    }

    public function productTags($product, $action = 'update')
    {
        $tags = [];
        $tags[] = $product->name;
        $tags[] = $product->slug;
        $tags[] = $product->subtitle;
        $tags[] = $product->slug;
        if ($product->brand) {
            $tags[] = $product->brand->name;
        }
        $tags = array_merge($tags, explode(',', $product->tags));
        $tags = array_merge($tags, $product->categories->pluck('name')->toArray());
        $tags = array_merge($tags, $product->categories->pluck('display_name')->toArray());

        if ($product?->attributes?->count()) {
            foreach ($product->attributes as $attribute) {
                $tags[] = $attribute->name;
                $tags[] = $attribute->option;
                $tags[] = $attribute->name . ' ' . $attribute->option;
            }
        }
        $tags = array_unique($tags);

        if ($action == 'update') {
            $product->tags = implode(',', $tags);
            $product->save();
        } else {
            return $tags;
        }
    }
}
