<?php

namespace Takshak\Ashop\Traits;

trait ProductTrait
{
    public function getVariations($product)
    {
        $variations = collect([]);
        $productVariations = $product->product_id
            ? $product->productParent->productVariations
            : $product->productVariations;

        foreach ($productVariations as $productVariation) {
            if ($variations->where('id', $productVariation->variation?->id)->first()) {
                $variations = $variations->map(function ($item) use ($productVariation) {
                    if ($item['id'] == $productVariation->variation?->id) {
                        $item['variants'][] = [
                            'id' =>  $productVariation->variant?->id,
                            'name' =>  $productVariation->variant?->name,
                            'status' =>  true
                        ];
                    }

                    return $item;
                });

                continue;
            }

            $variations[] = [
                'product_variation_id' =>  $productVariation->id,
                'id' =>  $productVariation->variation?->id,
                'name' =>  $productVariation->variation?->name,
                'display_name' =>  $productVariation->variation?->display_name,
                'variants' =>  [
                    [
                        'id' =>  $productVariation->variant?->id,
                        'name' =>  $productVariation->variant?->name,
                        'status' =>  true
                    ]
                ]
            ];
        }

        return $variations->map(function ($item) {
            $newVariants = collect([]);
            collect($item['variants'])->each(function ($item) use ($newVariants) {
                if (!$newVariants->where('id', $item['id'])->first()) {
                    $newVariants->push($item);
                }
            });

            $item['variants'] = $newVariants;
            return $item;
        });
    }
}
