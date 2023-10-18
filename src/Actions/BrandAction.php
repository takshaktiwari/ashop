<?php

namespace Takshak\Ashop\Actions;

use Illuminate\Support\Facades\Storage;
use Takshak\Imager\Facades\Imager;

class BrandAction
{
    public function save($request, $brand)
    {
        $brand->name    =  $request->post('brand');
        $brand->slug    = str()->of($request->post('brand'))->slug('-');
        $brand->user_id =  auth()->id();

        if ($request->file('image')) {
            $brand->image_lg = 'brands/' . $brand->slug . '.jpg';
            $brand->image_md = 'brands/md/' . $brand->slug . '.jpg';
            $brand->image_sm = 'brands/sm/' . $brand->slug . '.jpg';

            $imgWidth = config('ashop.brands.images.width', 800);
            $imgHeight = config('ashop.brands.images.height', 900);
            Imager::init($request->file('image'))
                ->resizeFit($imgWidth, $imgHeight)
                ->inCanvas('#fff')
                ->basePath(Storage::disk('public')->path('/'))
                ->save($brand->image_lg)
                ->save($brand->image_md, $imgWidth / 2)
                ->save($brand->image_sm, $imgWidth / 4);
        }

        $brand->save();

        return $brand;
    }
}
