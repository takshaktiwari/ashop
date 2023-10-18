<?php

namespace Takshak\Ashop\Actions;

use Illuminate\Support\Facades\Storage;
use Takshak\Imager\Facades\Imager;

class CategoryAction
{
    public function save($request, $category)
    {
        $category->name         =  $request->post('name');
        $category->slug         =  str()->of($request->post('name'))->slug('-').'-'.rand();
        $category->display_name =  $request->post('display_name');
        $category->category_id  =  $request->post('category_id');
        $category->description  =  $request->post('description');
        $category->featured     =  $request->boolean('featured');
        $category->status       =  $request->boolean('status');
        $category->is_top       =  $request->boolean('is_top');

        if ($request->file('image_file')){
        	$category->image_lg     = 'categories/'.$category->slug.'.jpg';
        	$category->image_md     = 'categories/md/'.$category->slug.'.jpg';
        	$category->image_sm     = 'categories/sm/'.$category->slug.'.jpg';

            $imgWidth = config('ashop.categories.images.width', 800);
            $imgHeight = config('ashop.categories.images.height', 900);
        	Imager::init($request->file('image_file'))
        	    ->resizeFit($imgWidth, $imgHeight)
        	    ->inCanvas('#fff')
        	    ->basePath(Storage::disk('public')->path('/'))
        	    ->save($category->image_lg)
        	    ->save($category->image_md, $imgWidth/2)
        	    ->save($category->image_sm, $imgWidth/4);
        }

        $category->save();

        return $category;
    }
}
