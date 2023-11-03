<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Takshak\Ashop\Models\Shop\Product;

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
            ->with('categories')
            ->with('brand')
            ->with('metas')
            ->with('user')
            ->get();

        foreach ($products as $product) {
            $searchTags = $product->name.', ';
            $searchTags .= $product->slug.', ';
            $searchTags .= $product->subtitle.', ';
            $searchTags .= $product->brand?->name.', ';
            $searchTags .= $product->brand?->slug.', ';
            $searchTags .= $product->info.', ';
            $searchTags .= $product->user?->name.', ';

            foreach ($product->metas as $meta) {
                $searchTags .= $meta->name.' - ';
                if($meta->key) {
                    $searchTags .= $meta->key.' - ';
                }
                $searchTags .= $meta->value.', ';
            }

            foreach ($product->categories as $category) {
                $searchTags .= $category->name.' - ';
                $searchTags .= $category->slug.', ';
            }

            $product->search_tags = $searchTags;
            $product->save();
        }
    }
}
