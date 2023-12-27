<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\ProductImage;
use Takshak\Ashop\Models\Shop\Variant;
use Takshak\Imager\Facades\Picsum;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($b = 0; $b < 5; $b++) {
            $this->product();
        }
    }

    public function product()
    {
        $net_price = rand(10, 99) * 5;
        $sell_price = $net_price - $net_price * (rand(5, 30) / 100);
        $deal_price = $sell_price - $sell_price * (rand(5, 30) / 100);

        $product = new Product();
        $product->name          =  fake()->realText(rand(20, 150)) . microtime();
        $product->subtitle      =  fake()->realText(rand(20, 250));
        $product->brand_id      =  Brand::inRandomOrder()->first()->id;
        $product->sku           =  'ASI' . rand();
        $product->stock         =  rand(10, 250);
        $product->net_price     =  $net_price;
        $product->sell_price    =  $sell_price;
        $product->deal_price    =  $deal_price;
        $product->deal_expiry   =  now()->addDays(rand(5, 25));
        $product->status        =  true;
        $product->featured      =  true;
        $product->info          =  fake()->realText(rand(150, 300));
        $product->user_id       =  User::inRandomOrder()->first()->id;

        $product->slug          = str()->of($product->name)->slug('-') . time();
        $product->image_sm  = 'products/sm/' . $product->slug . '.jpg';
        $product->image_md  = 'products/md/' . $product->slug . '.jpg';
        $product->image_lg  = 'products/' . $product->slug . '.jpg';

        $imgWidth = config('ashop.products.images.width', 800);
        $imgHeight = config('ashop.products.images.height', 900);
        Picsum::dimensions($imgWidth, $imgHeight)
            ->basePath(Storage::disk('public')->path('/'))
            ->save($product->image_lg)
            ->save($product->image_md, $imgWidth / 2)
            ->save($product->image_sm, $imgWidth / 4)
            ->destroy();

        $product->save();

        $product->metas()->create([
            'name' => 'm_title',
            'value' => fake()->realText(rand(50, 150)),
        ]);
        $product->metas()->create([
            'name' => 'm_keywords',
            'value' => fake()->realText(rand(50, 150)),
        ]);
        $product->metas()->create([
            'name' => 'm_description',
            'value' => fake()->realText(rand(50, 150)),
        ]);
        $product->metas()->create([
            'name' => 'description',
            'value' => fake()->realText(rand(500, 3000)),
        ]);

        $categories = Category::with('attributes')->inRandomOrder()->limit(rand(1, 5))->get();
        $product->categories()->sync($categories->pluck('id'));

        foreach ($categories->pluck('attributes')->collapse() as $attribute) {
            $product->metas()->create([
                'key' => 'attributes',
                'name' => $attribute->name,
                'value' => collect($attribute->options)
                    ->shuffle()
                    ->take(rand(1, count($attribute->options)))
                    ->toJson(),
            ]);
        }

        for ($j = 0; $j < rand(0, 10); $j++) {
            $productImage = new ProductImage();
            $productImage->product_id = $product->id;
            $productImage->title = $product->name;

            $fileName = str()->of(microtime())->slug('-') . '.jpg';
            $productImage->image_lg = 'products/' . $fileName;
            $productImage->image_md = 'products/md/' . $fileName;
            $productImage->image_sm = 'products/sm/' . $fileName;

            $imgWidth = config('shopze.products.images.width', 800);
            $imgHeight = config('shopze.products.images.height', 900);
            Picsum::dimensions($imgWidth, $imgHeight)
                ->basePath(Storage::disk('public')->path('/'))
                ->save($productImage->image_lg)
                ->save($productImage->image_md, $imgWidth / 2)
                ->save($productImage->image_sm, $imgWidth / 4)
                ->destroy();

            $productImage->save();
        }

        return $product;
    }

    public function cloneProduct($product, $variants)
    {
        $net_price = rand(10, 99) * 5;
        $sell_price = $net_price - $net_price * (rand(5, 30) / 100);
        $deal_price = $sell_price - $sell_price * (rand(5, 30) / 100);

        $productName = $product->name . ' - ' . microtime() . ' - ' . implode('-', $variants);
        $newProduct = $product->replicate()->fill([
            'product_id'  =>  $product->id,
            'name'  =>  $productName,
            'slug'  =>  str()->of($productName)->slug('-')->append('-' . time()),
            'net_price' => $net_price,
            'sell_price' => $sell_price,
            'deal_price' => $deal_price,
            'image_sm'  =>  str()->of($product->name)->slug('-')->prepend('products/sm/')->append('-' . time() . '.jpg'),
            'image_md'  =>  str()->of($product->name)->slug('-')->prepend('products/md/')->append('-' . time() . '.jpg'),
            'image_lg'  =>  str()->of($product->name)->slug('-')->prepend('products/')->append('-' . time() . '.jpg'),
        ]);
        $newProduct->save();

        Storage::disk('public')->copy($product->image_sm, $newProduct->image_sm);
        Storage::disk('public')->copy($product->image_md, $newProduct->image_md);
        Storage::disk('public')->copy($product->image_lg, $newProduct->image_lg);

        $newProduct->categories()->sync($product->categories?->pluck('id')?->toArray());

        foreach ($product->details() as $meta) {
            $newProduct->metas()->create([
                'key' => $meta->key,
                'name' => $meta->name,
                'value' => $meta->value,
                'remarks' => $meta->remarks,
            ]);
        }

        foreach ($product->images as $key => $image) {
            $newImage = $image->replicate()->fill([
                'product_id' => $newProduct->id,
                'image_sm'  =>  str()->of($newProduct->slug . '-' . $key)->prepend('products/sm/')->append('.jpg'),
                'image_md'  =>  str()->of($newProduct->slug . '-' . $key)->prepend('products/md/')->append('.jpg'),
                'image_lg'  =>  str()->of($newProduct->slug . '-' . $key)->prepend('products/')->append('.jpg'),
            ]);
            $newImage->save();

            Storage::disk('public')->copy($image->image_sm, $newImage->image_sm->__toString());
            Storage::disk('public')->copy($image->image_md, $newImage->image_md->__toString());
            Storage::disk('public')->copy($image->image_lg, $newImage->image_lg->__toString());
        }

        return $newProduct;
    }
}
