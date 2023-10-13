<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Takshak\Ashop\Models\Shop\Variant;
use Takshak\Ashop\Models\Shop\Variation;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $variation = new Variation();
            $variation->name = fake()->name();
            $variation->slug = str()->of($variation->name)->slug('-');
            $variation->display_name = $variation->name;
            $variation->remarks = fake()->realText(rand(20, 250), 2);
            $variation->save();

            foreach (fake()->words(rand(2, 25)) as $word) {
                $variant = new Variant();
                $variant->variation_id = $variation->id;
                $variant->name = $word;
                $variant->slug = str()->of($word)->slug();
                $variant->save();
            }
        }
    }
}
