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
        foreach ($this->attributes() as $key => $values) {
            $attribute = new Attribute();
            $attribute->name = $key;
            $attribute->slug = str()->of($attribute->name)->slug('-');
            $attribute->display_name = $attribute->name;
            $attribute->options = $values;
            $attribute->remarks = fake()->realText(rand(20, 250), 2);
            $attribute->save();
        }
    }

    public function attributes()
    {
        return [
            'Color' => [
                'Red',
                'Blue',
                'Green',
                'Yellow',
                'Pink',
                'Purple',
                'Orange',
                'Black',
                'White',
                'Multicolor'
            ],
            'Size/Dimensions' => [
                'Small',
                'Medium',
                'Large',
                'Custom Dimensions (e.g., 12 x 8 x 5 inches)'
            ],
            'Weight' => [
                'Lightweight',
                'Medium Weight',
                'Heavy',
                'Custom Weight (e.g., 500 grams, 2 lbs)'
            ],
            'Material' => [
                'Plastic',
                'Wood',
                'Fabric',
                'Metal',
                'Rubber',
                'Silicone',
                'Eco-friendly Materials (e.g., recycled plastic, bamboo)'
            ],
            'Shape' => [
                'Round',
                'Square',
                'Rectangular',
                'Cylindrical',
                'Irregular',
                'Custom Shape'
            ],
            'Texture' => [
                'Smooth',
                'Rough',
                'Soft',
                'Plush',
                'Rubbery',
                'Grainy'
            ],
            'Interactive Features' => [
                'Lights',
                'Sounds',
                'Touch-Activated',
                'Voice-Activated',
                'App-Controlled',
                'Motion-Sensing'
            ],
            'Movement' => [
                'Static',
                'Remote Control',
                'Wind-Up',
                'Battery-Operated',
                'Manual Push/Pull'
            ],
            'Battery Requirements' => [
                'No Batteries',
                'AA',
                'AAA',
                '9V',
                'Rechargeable',
                'Included',
                'Not Included'
            ],
            'Sound' => [
                'No Sound',
                'Music',
                'Animal Sounds',
                'Voice Commands',
                'Beeping',
                'Custom Sound'
            ],
            'Light' => [
                'No Lights',
                'Flashing Lights',
                'Glow in the Dark',
                'LED',
                'Multicolor Lights',
                'Custom Light Patterns'
            ],
            'Assembly Required' => [
                'No Assembly Required',
                'Minimal Assembly',
                'Full Assembly Required'
            ],
            'Safety Features' => [
                'Non-Toxic Materials',
                'Choking Hazard Warning',
                'BPA-Free',
                'Lead-Free',
                'Phthalate-Free'
            ],
            'Water Resistance' => [
                'Not Water-Resistant',
                'Water-Resistant',
                'Waterproof',
                'Suitable for Bath Play'
            ],
            'Durability' => [
                'Fragile',
                'Moderately Durable',
                'Highly Durable',
                'Shatterproof',
                'Scratch-Resistant'
            ]
        ];
    }
}
