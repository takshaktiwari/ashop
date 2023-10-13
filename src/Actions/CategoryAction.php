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

        	Imager::init($request->file('image_file'))
        	    ->resizeFit(800, 900)
        	    ->inCanvas('#fff')
        	    ->basePath(Storage::disk('public')->path('/'))
        	    ->save($category->image_lg)
        	    ->save($category->image_md, 800/2)
        	    ->save($category->image_sm, 800/4);
        }

        $category->save();

        return $category;
    }
}
