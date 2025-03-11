<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Models\Shop\Attribute;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Imager\Facades\Picsum;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, count($this->categories()));
        $progressBar->start();

        foreach ($this->categories() as $key => $item) {
            if (is_array($item)) {
                $category = $this->createCategory($key);
                foreach ($item as $subKey => $subItem) {
                    if (is_array($subItem)) {
                        $subCategory = $this->createCategory($subKey, $category);
                        foreach ($subItem as $subSubKey => $subSubItem) {
                            if (is_array($subSubItem)) {
                                $anotherCategory = $this->createCategory($subSubKey, $subCategory);
                                foreach ($subSubItem as  $subSubSubItem) {
                                    $this->createCategory($subSubSubItem, $anotherCategory);
                                }
                            } else {
                                $this->createCategory($subSubItem, $subCategory);
                            }
                        }
                    } else {
                        $this->createCategory($subItem, $category);
                    }
                }
            } else {
                $this->createCategory($item);
            }

            $progressBar->advance();
        }
        $progressBar->finish();
        $output->writeln('');
    }

    public function createCategory($name, $parentCategory = null)
    {

        $category = new Category();
        $category->name = $name;
        $category->slug = str()->of($category->name . '-' . rand(1, 99))->slug();
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

        $category->brands()->sync(Brand::inRandomOrder()->limit(rand(2, 8))->pluck('id'));

        return $category;
    }

    public function categories()
    {
        return [
            'By Age Group' => [
                'Infants & Toddlers (0-2 years)' => [
                    'Soft Toys',
                    'Teething Toys',
                    'Rattles & Musical Toys',
                    'Activity Gyms & Playmats',
                    'Push & Pull Toys'
                ],
                'Preschool (3-5 years)' => [
                    'Educational Toys',
                    'Building Blocks',
                    'Action Figures & Playsets',
                    'Arts & Crafts Kits',
                    'Puzzles'
                ],
                'Kids (6-8 years)' => [
                    'Board Games',
                    'Remote Control Toys',
                    'Dolls & Accessories',
                    'Science Kits',
                    'Vehicles & Race Tracks'
                ],
                'Tweens (9-12 years)' => [
                    'Robotics Kits',
                    'Strategy Board Games',
                    'Building & Construction Sets',
                    'Outdoor & Sports Toys',
                    'Arts & Craft Kits'
                ],
                'Teens (13+ years)' => [
                    'Drones & Tech Toys',
                    'Collectible Toys & Action Figures',
                    'Strategy Card Games',
                    'Video Game Accessories'
                ]
            ],
            'By Type of Toy' => [
                'Educational Toys' => [
                    'STEM Toys',
                    'Language Learning Toys',
                    'Coding Kits',
                    'Math Games',
                    'Memory & Cognitive Games'
                ],
                'Building & Construction Toys' => [
                    'LEGO & Building Sets',
                    'Magnetic Construction Toys',
                    'Model Kits',
                    'Gears & Connectors Sets'
                ],
                'Dolls & Plush' => [
                    'Baby Dolls',
                    'Fashion Dolls',
                    'Stuffed Animals',
                    'Dollhouses & Playsets',
                    'Accessories & Clothing for Dolls'
                ],
                'Action Figures' => [
                    'Superheroes',
                    'Movie & TV Characters',
                    'Military Figures',
                    'Dinosaurs & Animals',
                    'Robots & Aliens'
                ],
                'Vehicles & Remote Control' => [
                    'Cars, Trucks & Motorcycles',
                    'Boats & Planes',
                    'Remote Control Cars & Drones',
                    'Train Sets'
                ],
                'Arts & Crafts' => [
                    'Drawing & Painting Kits',
                    'DIY Craft Kits',
                    'Modeling Clay & Dough',
                    'Beading & Jewelry Making',
                    'Science & Art Fusion Kits'
                ],
                'Games & Puzzles' => [
                    'Board Games',
                    'Puzzle Games',
                    'Strategy Games',
                    'Card Games',
                    'Party Games'
                ],
                'Outdoor & Sports Toys' => [
                    'Ride-On Toys & Scooters',
                    'Bicycles & Tricycles',
                    'Water Toys & Pools',
                    'Sports Equipment',
                    'Playhouses & Tents'
                ]
            ],
            'By Theme/Franchise' => [
                'Superheroes',
                'Fantasy & Magic',
                'Space Exploration',
                'Animals & Nature',
                'Educational Shows & Movies',
                'Popular Movies & TV Shows',
                'Classic Cartoons & Characters'
            ],
            'By Material' => [
                'Wooden Toys',
                'Plastic Toys',
                'Fabric & Plush Toys',
                'Eco-Friendly & Sustainable Toys',
                'Metal & Mechanical Toys'
            ],
            'By Activity Type' => [
                'Imaginative Play' => [
                    'Costumes & Role Play Sets',
                    'Kitchen & Cooking Toys',
                    'Doctor & Nurse Kits',
                    'Puppet Sets',
                    'Castles & Fantasy Playsets'
                ],
                'Physical Activity' => [
                    'Balls & Sports Equipment',
                    'Trampolines',
                    'Balance Bikes & Scooters',
                    'Jump Ropes & Hula Hoops'
                ],
                'Creative Expression' => [
                    'Painting & Drawing Supplies',
                    'Music Instruments for Kids',
                    'Dance & Movement Toys',
                    'Craft & DIY Kits'
                ],
                'Problem Solving' => [
                    'Brain Teasers',
                    'Puzzles',
                    'Maze Games',
                    'Rubik’s Cube & Similar Puzzles'
                ]
            ],
            'By Special Features' => [
                'Interactive & Electronic Toys',
                'Light-Up & Glow Toys',
                'Sound & Music Toys',
                'Eco-Friendly & Sustainable Toys',
                'Portable & Travel-Friendly Toys'
            ]
        ];
    }
}
