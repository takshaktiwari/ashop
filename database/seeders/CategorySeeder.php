<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Models\Shop\Attribute;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Variation;
use Takshak\Imager\Facades\Picsum;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 15; $i++) {
            $category = $this->createCategory();
            for ($j = 0; $j < rand(0, 10); $j++) {
                $this->createCategory($category);
            }
        }
    }

    public function createCategory($parentCategory = null)
    {

        $category = new Category();
        $category->name = fake()->name();
        $category->slug = str()->of($category->name)->slug();
        $category->display_name = $category->name;
        $category->category_id = $parentCategory?->id;
        $category->description = fake()->realText(rand(100, 500), 2);
        $category->status = true;
        $category->featured = rand(0, 1);
        $category->is_top = rand(0, 1);
        $category->image_sm = 'categories/sm/' . time() . rand() . '.jpg';
        $category->image_md = 'categories/md/' . time() . rand() . '.jpg';
        $category->image_lg = 'categories/' . time() . rand() . '.jpg';
        $category->save();

        Picsum::dimensions(800, 900)
            ->save(Storage::disk('public')->path($category->image_lg))
            ->save(Storage::disk('public')->path($category->image_md), 400)
            ->save(Storage::disk('public')->path($category->image_sm), 200)
            ->destroy();

        $attributes = Attribute::inRandomOrder()->limit(rand(0, 6))->pluck('id');
        $category->attributes()->sync($attributes);

        $variations = Variation::inRandomOrder()->limit(rand(0, 6))->pluck('id');
        $category->variations()->sync($variations);

        $category->brands()->sync(Brand::inRandomOrder()->limit(rand(2, 8))->pluck('id'));

        return $category;
    }
}
