<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Takshak\Ashop\Models\Shop\Product;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class ProductsSearchTagsOptimizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:products-search-tags-optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare search tags and save in products table for optimized search';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::query()
            ->with('categories:id,name,slug,display_name')
            ->with('brand:id,name,slug')
            ->with('metas')
            ->get();

        progress(
            label: 'Syncing search tags',
            steps: $products,
            callback: function ($product) {
                $this->syncSearchTags($product);
            }
        );

        info('Search tags synced successfully.');
    }

    public function syncSearchTags($product)
    {
        $searchTags = str($product->search_tags);

        if (!$searchTags->contains($product->name)) {
            $searchTags = $searchTags->append($product->name . ', ');
        }

        if (!$searchTags->contains($product->slug)) {
            $searchTags = $searchTags->append($product->slug . ', ');
        }

        if (!$searchTags->contains($product->subtitle)) {
            $searchTags = $searchTags->append($product->subtitle . ', ');
        }

        if (!$searchTags->contains($product->info)) {
            $searchTags = $searchTags->append($product->info . ', ');
        }

        if (!$searchTags->contains($product->brand?->name)) {
            $searchTags = $searchTags->append($product->brand?->name . ', ');
        }

        if (!$searchTags->contains($product->brand?->slug)) {
            $searchTags = $searchTags->append($product->brand?->slug . ', ');
        }

        foreach ($product->categories as $category) {
            if (!$searchTags->contains($category->name)) {
                $searchTags = $searchTags->append($category->name . ', ');
            }

            if (!$searchTags->contains($category->slug)) {
                $searchTags = $searchTags->append($category->slug . ', ');
            }

            if (!$searchTags->contains($category->display_name)) {
                $searchTags = $searchTags->append($category->display_name . ', ');
            }
        }

        $product->search_tags = $searchTags;
        $product->save();
    }
}
