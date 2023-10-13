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

            Imager::init($request->file('image'))
                ->resizeFit(800, 900)
                ->inCanvas('#fff')
                ->basePath(Storage::disk('public')->path('/'))
                ->save($brand->image_lg)
                ->save($brand->image_md, 800 / 2)
                ->save($brand->image_sm, 800 / 4);
        }

        $brand->save();

        return $brand;
    }
}
