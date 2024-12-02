<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Takshak\Ashop\Models\Shop\Product;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Takshak\Areviews\Models\Areviews\Review;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalProducts = Product::count();

        $productsLimit = ceil($totalProducts * 0.85);

        $products = Product::inRandomOrder()->limit($productsLimit)->get();

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, $productsLimit);
        $progressBar->start();

        foreach ($products as $product) {
            for ($i=0; $i < rand(5, 100); $i++) {
                Review::create([
                    'reviewable_type' => get_class($product),
                    'reviewable_id'     =>  $product->id,
                    'user_id'   =>  User::inRandomOrder()->first()?->id,
                    'name'  =>  fake()->name(),
                    'mobile'    =>  fake()->phoneNumber(),
                    'email'     =>  fake()->email(),
                    'rating'    =>  rand(1, 5),
                    'title'     =>  fake()->realText(rand(50, 200), 2),
                    'content'     =>  fake()->realText(rand(200, 1000), 2),
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln('');
    }
}
