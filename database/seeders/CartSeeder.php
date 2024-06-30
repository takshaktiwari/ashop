<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Takshak\Ashop\Models\Shop\Cart;
use Takshak\Ashop\Models\Shop\Product;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 1500; $i++) {

            Cart::create([
                'user_id'  =>  ($i % 5) ? User::inRandomOrder()->first()?->id : null,
                'user_ip'  =>  fake()->ipv4,
                'product_id'  =>  Product::inRandomOrder()->first()?->id,
                'quantity'  =>  rand(1, 5),
            ]);
        }
    }
}
