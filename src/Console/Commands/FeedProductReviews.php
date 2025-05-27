<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\Product;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class FeedProductReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:feed-product-reviews {--min=2} {--max=15} {--min-rating=4} {--max-rating=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add some initial product reviews to look good';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::select('id')->get();

        if ($this->option('max') < $this->option('min')) {
            error('Max should be greater than min');
            return;
        }

        if ($this->option('min') < 0) {
            error('Min should be greater than 0');
            return;
        }

        if ($this->option('max-rating') < $this->option('min-rating')) {
            error('Max rating should be greater than min rating');
            return;
        }

        if ($this->option('min-rating') <= 0) {
            error('Min rating should be greater than 0');
            return;
        }

        if(!Storage::disk('local')->exists('reviews.json')){
            copy(__DIR__ . '/../../../resources/assets/reviews.json', Storage::disk('local')->path('reviews.json'));
        }

        $reviews = json_decode(Storage::disk('local')->get('reviews.json'), true);
        $reviews = collect($reviews);

        progress(
            label: 'Feeding product reviews',
            steps: $products,
            callback: function ($product) use ($reviews) {
                $min = $this->option('min');
                $max = $this->option('max');

                $reviews_count = rand($min, $max);

                foreach ($reviews->take($reviews_count) as $key => $review) {
                    $product->reviews()->create([
                        'name' => $review['name'],
                        'email' => $review['email'],
                        'rating' => rand($this->option('min-rating'), $this->option('max-rating')),
                        'title' => $review['title'],
                        'content' => $review['content'],
                        'created_at' => now()->subDays(365)->addDays(rand(0, now()->dayOfYear))
                    ]);
                }
            }
        );
    }
}
