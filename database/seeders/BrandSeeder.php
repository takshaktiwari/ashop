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

        foreach ($this->brands() as $brand) {
            $slug = str()->of($brand)->slug('-');
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
                'name'  =>  $brand,
                'slug'  =>  $slug,
                'image_lg'  =>  $image_lg,
                'image_sm'  =>  $image_sm,
                'user_id'   =>  User::inRandomOrder()->first()->id,
                'status'    =>  rand(0, 1),
            ]);
        }
    }

    public function brands()
    {
        return [
            'LEGO',
            'Hasbro',
            'Mattel',
            'Fisher-Price',
            'Nerf',
            'Hot Wheels',
            'Barbie',
            'Playmobil',
            'Melissa & Doug',
            'VTech',
            'LeapFrog',
            'Crayola',
            'Disney',
            'Mega Bloks',
            'Funko',
            'Little Tikes',
            'Transformers',
            'Paw Patrol',
            'Play-Doh',
            'Schleich',
            'Tonka',
            'Ty',
            'L.O.L. Surprise!',
            'American Girl',
            'Spin Master',
            'K’NEX',
            'Bandai',
            'Baby Alive',
            'Thomas & Friends',
            'KidKraft',
            'Radio Flyer',
            'Meccano',
            'Calico Critters',
            'Brio',
            'Hexbug',
            'Hape',
            'WowWee',
            'Step2',
            'TOMY',
            'Moose Toys',
            'Ravensburger',
            'Jakks Pacific',
            'Siku',
            'Sylvanian Families',
            'My Little Pony',
            'Magformers',
            'Ouaps',
            'Beyblade',
            'Peppa Pig'
        ];
    }
}
