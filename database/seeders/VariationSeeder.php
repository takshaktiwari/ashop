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
        foreach ($this->variations() as $variationArr) {

            $variation = new Variation();
            $variation->name = $variationArr['name'];
            $variation->slug = str()->of($variation->name)->slug('-');
            $variation->display_name = $variation->name;
            $variation->remarks = fake()->realText(rand(20, 250), 2);
            $variation->save();

            foreach ($variationArr['variants'] as $variantArr) {
                $variant = new Variant();
                $variant->variation_id = $variation->id;
                $variant->name = $variantArr;
                $variant->slug = str()->of($variantArr)->slug();
                $variant->save();
            }
        }
    }

    public function variations()
    {
        return [
            [
                'name'  =>  'Pattern',
                'variants'  =>  [
                    'Celestial Serenade',
                    'Enigmatic Echoes',
                    'Whimsical Waltz',
                    'Ethereal Embrace',
                    'Luminous Labyrinth',
                    'Mystic Mirage',
                    'Harmonious Haven',
                    'Radiant Ripples',
                    'Blossom Ballet',
                    'Tranquil Tapestry',
                    'Melodic Mosaic',
                    'Enchanted Ensemble',
                    'Cosmic Cascade',
                    'Serene Symphony',
                    'Vibrant Voyage',
                    'Dreamy Drift',
                    'Dazzling Delight',
                    'Floral Fantasy',
                    'Sublime Swirls',
                    'Oceanic Odyssey'
                ]
            ],
            [
                'name' => 'Lens Type',
                'variants' => [
                    'Convex Lens',
                    'Concave Lens',
                    'Bifocal Lens',
                    'Trifocal Lens',
                    'Cylindrical Lens',
                    'Aspheric Lens',
                    'Meniscus Lens',
                    'Plano-Convex Lens',
                    'Plano-Concave Lens',
                    'Double Convex Lens',
                    'Double Concave Lens',
                    'Fresnel Lens',
                    'Achromatic Lens',
                    'Apochromatic Lens',
                ]
            ],
            [
                'name' => 'Style',
                'variants' => [
                    'Classic Elegance',
                    'Urban Chic',
                    'Retro Fusion',
                    'Bohemian Bliss',
                    'Minimalist Modern',
                    'Vintage Glamour',
                    'Contemporary Edge',
                    'Rustic Charm',
                    'Eclectic Enchantment',
                    'Coastal Serenity',
                    'Industrial Vibes',
                    'Art Deco Delight',
                    'Scandinavian Simplicity',
                    'Mediterranean Magic',
                    'Tropical Paradise',
                    'Mid-Century Marvel',
                    'French Country Flair',
                    'Zen Tranquility',
                    'Gothic Revival',
                    'Modern Farmhouse',
                ]
            ],
            [
                'name' => 'Disk Size',
                'variants' => [
                    '1.44 MB (3.5-inch floppy disk)',
                    '700 MB (CD)',
                    '4.7 GB (DVD)',
                    '25 GB (Blu-ray)',
                    '50 GB (Dual-layer Blu-ray)',
                    '120 GB (CompactFlash card)',
                    '250 GB (Portable external hard drive)',
                    '500 GB (Standard internal hard drive)',
                    '1 TB (SSD or HDD)',
                    '2 TB (HDD)',
                    '4 TB (HDD)',
                    '8 TB (HDD)',
                    '16 TB (HDD)',
                    '32 TB (HDD)',
                    '64 TB (HDD)',
                ]
            ],
            [
                'name' => 'RAM Size',
                'variants' => [
                    '2 GB (Gigabytes)',
                    '4 GB',
                    '8 GB',
                    '16 GB',
                    '32 GB',
                    '64 GB',
                    '128 GB',
                    '256 GB',
                    '512 GB',
                    '1 TB (Terabyte)',
                    '2 TB',
                    '4 TB',
                ]
            ],
            [
                'name' => 'Color',
                'variants' => [
                    'Red',
                    'Blue',
                    'Green',
                    'Yellow',
                    'Orange',
                    'Purple',
                    'Pink',
                    'Brown',
                    'Black',
                    'White',
                    'Gray',
                    'Cyan',
                    'Magenta',
                    'Indigo',
                    'Violet',
                    'Turquoise',
                    'Gold',
                    'Silver',
                    'Maroon',
                    'Navy Blue',
                ]
            ],
            [
                'name' => 'Size No.',
                'variants' => [
                    '0',
                    '2',
                    '4',
                    '6',
                    '8',
                    '10',
                    '12',
                    '14',
                    '16',
                    '18',
                    '20',
                    '22',
                    '24',
                    '26',
                    '28',
                    '30',
                    '32',
                    '34',
                    '36',
                    '38',
                ]
            ],
            [
                'name' => 'Size Al.',
                'variants' => [
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                    'I',
                    'J',
                    'K',
                    'L',
                    'M',
                    'N',
                    'O',
                    'P',
                    'Q',
                    'R',
                    'S',
                    'T',
                ]
            ],
            [
                'name' => 'Size Ft.',
                'variants' => [
                    '1 ft',
                    '2 ft',
                    '3 ft',
                    '4 ft',
                    '5 ft',
                    '6 ft',
                    '7 ft',
                    '8 ft',
                    '9 ft',
                    '10 ft',
                    '11 ft',
                    '12 ft',
                    '13 ft',
                    '14 ft',
                    '15 ft',
                    '16 ft',
                    '17 ft',
                    '18 ft',
                    '19 ft',
                    '20 ft',
                ]
            ],
            [
                'name' => 'Materials',
                'variants' => [
                    'Wood',
                    'Metal',
                    'Plastic',
                    'Glass',
                    'Ceramic',
                    'Fabric',
                    'Leather',
                    'Rubber',
                    'Stone',
                    'Paper',
                    'Concrete',
                    'Silk',
                    'Wool',
                    'Cotton',
                    'Polyester',
                    'Nylon',
                    'Acrylic',
                    'Linen',
                    'Velvet',
                    'Satin',
                ]
            ],
            [
                'name' => 'Length In.',
                'variants' => [
                    '1 in',
                    '2 in',
                    '3 in',
                    '4 in',
                    '5 in',
                    '6 in',
                    '7 in',
                    '8 in',
                    '9 in',
                    '10 in',
                    '11 in',
                    '12 in',
                    '13 in',
                    '14 in',
                    '15 in',
                    '16 in',
                    '17 in',
                    '18 in',
                    '19 in',
                    '20 in',
                ]
            ],
            [
                'name' => 'Length Cm.',
                'variants' => [
                    '1 cm',
                    '2 cm',
                    '3 cm',
                    '4 cm',
                    '5 cm',
                    '6 cm',
                    '7 cm',
                    '8 cm',
                    '9 cm',
                    '10 cm',
                    '11 cm',
                    '12 cm',
                    '13 cm',
                    '14 cm',
                    '15 cm',
                    '16 cm',
                    '17 cm',
                    '18 cm',
                    '19 cm',
                    '20 cm',
                ]
            ],
            [
                'name' => 'Volume',
                'variants' => [
                    '1 ltr',
                    '2 ltr',
                    '3 ltr',
                    '4 ltr',
                    '5 ltr',
                    '6 ltr',
                    '7 ltr',
                    '8 ltr',
                    '9 ltr',
                    '10 ltr',
                    '11 ltr',
                    '12 ltr',
                    '13 ltr',
                    '14 ltr',
                    '15 ltr',
                    '16 ltr',
                    '17 ltr',
                    '18 ltr',
                    '19 ltr',
                    '20 ltr',
                ]
            ],
            [
                'name' => 'Pack Of',
                'variants' => [
                    'Pack of 1',
                    'Pack of 2',
                    'Pack of 3',
                    'Pack of 4',
                    'Pack of 5',
                    'Pack of 6',
                    'Pack of 7',
                    'Pack of 8',
                    'Pack of 9',
                    'Pack of 10',
                    'Pack of 11',
                    'Pack of 12',
                    'Pack of 13',
                    'Pack of 14',
                    'Pack of 15',
                    'Pack of 16',
                    'Pack of 17',
                    'Pack of 18',
                    'Pack of 19',
                    'Pack of 20',
                ]
            ]
        ];
    }
}
