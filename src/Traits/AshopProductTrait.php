<?php

namespace Takshak\Ashop\Traits;

use Illuminate\Support\Facades\DB;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\Review;

trait AshopProductTrait
{

    public function reviewStats(Product $product)
    {
        $ratings = Review::groupBy('rating')
            ->active()
            ->select('rating', DB::raw('count(*) as count'))
            ->where('reviewable_type', get_class($product))
            ->where('reviewable_id', $product->id)
            ->orderBy('rating', 'DESC')
            ->get()
            ->pluck('count', 'rating');

        $total = $ratings->sum();
        $ratings = $ratings->map(function ($item) use ($total) {
            return [
                'count' => $item,
                'percentage' => round($item / $total * 100, 1)
            ];
        });

        if ($product->rating == null) {
            $product->rating = Review::query()
                ->active()
                ->where('reviewable_type', get_class($this->model))
                ->where('reviewable_id', $this->model->id)
                ->avg('rating');
        }

        return [
            'total' => $total,
            'rating' => $product->rating,
            'rating_count' => $ratings
        ];
    }
}
