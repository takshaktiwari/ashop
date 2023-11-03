<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Imager\Facades\Picsum;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::whereNotNull('id')->delete();

        for ($i = 0; $i <= 25; $i++) {
            $name = fake()->company;
            $slug = str()->of($name)->slug('-');
            $image_lg = "brands/$slug.jpg";
            $image_md = "brands/md/$slug.jpg";
            $image_sm = "brands/sm/$slug.jpg";

            Picsum::dimensions(800, 900)
                ->basePath(Storage::disk('public')->path('/'))
                ->save($image_lg)
                ->save($image_md, 400)
                ->save($image_sm, 200)
                ->destroy();

            Brand::create([
                'name'  =>  $name,
                'slug'  =>  $slug,
                'image_lg'  =>  $image_lg,
                'image_sm'  =>  $image_sm,
                'user_id'   =>  User::inRandomOrder()->first()->id,
                'status'    =>  rand(0, 1),
            ]);
        }
    }
}
