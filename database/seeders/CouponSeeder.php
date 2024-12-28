<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Takshak\Ashop\Models\Shop\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 25; $i++) {
            $amount = ($i % 2) ? rand(1, 9) * 50 : null;
            Coupon::create([
                'code'  =>  strtoupper(str()->random(rand(4, 7))),
                'percent'   =>  $amount ? null : (rand(1, 5) * 10),
                'amount'    =>  $amount,
                'min_purchase'  =>  rand(0, 9) * 100,
                'expires_at'    =>  now()->addDays(rand(0, 365)),
                'status'        =>  rand(0, 1),
                'title'         =>  $faker->sentence($nbWords = rand(5, 10), $variableNbWords = true),
                'description'   =>  $faker->randomHtml(5, 10)
            ]);
        }
    }
}
