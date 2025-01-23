<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\ProductViewed;

use function Laravel\Prompts\progress;

class ProductViewedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        progress(
            label: 'Seeding Product Viewed',
            steps: 500,
            callback: function()
            {
                ProductViewed::create([
                    'product_id' => Product::inRandomOrder()->first()->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'user_ip' => fake()->ipv4
                ]);
            }
        );
    }
}
