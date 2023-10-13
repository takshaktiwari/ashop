<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Takshak\Ashop\Models\Shop\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $attribute = new Attribute();
            $attribute->name = fake()->name();
            $attribute->slug = str()->of($attribute->name)->slug('-');
            $attribute->display_name = $attribute->name;
            $attribute->options = fake()->words(rand(2, 25));
            $attribute->remarks = fake()->realText(rand(20, 250), 2);
            $attribute->save();
        }
    }
}
