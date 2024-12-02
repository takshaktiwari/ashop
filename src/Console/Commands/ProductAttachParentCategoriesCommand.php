<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Takshak\Ashop\Models\Shop\Product;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class ProductAttachParentCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:product-attach-parent-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach the parent categories to a subcategory which is attached to the product.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::with('categories')->get();

        progress(
            label: 'Syncing categories',
            steps: $products,
            callback: function ($product) {
                $categoryIds = [];
                foreach ($product->categories as $category) {
                    $categoryIds[] = $category->id;
                    $categoryIds = array_merge($categoryIds, $category->parentCategoryIds());
                }

                $product->categories()->sync($categoryIds);
            }
        );

        info('Products categories synced successfully.');
    }
}
