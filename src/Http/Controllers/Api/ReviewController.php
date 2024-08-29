<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Areviews\Actions\ReviewAction;
use Takshak\Areviews\Models\Areviews\Review;
use Takshak\Ashop\Http\Resources\ReviewsResource;
use Takshak\Ashop\Models\Shop\Product;

class ReviewController extends Controller
{
    public function store(Request $request, ReviewAction $action)
    {
        $request->validate([
            'product_id' => 'required|numeric'
        ]);

        $product = Product::findOrFail($request->product_id);
        $request->merge(['reviewable_type' => get_class($product)]);
        $request->merge(['reviewable_id' => $product->id]);

        $validated = $action->validate($request);

        $review = Review::query()
            ->where('reviewable_type', get_class($product))
            ->where('reviewable_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$review) {
            $review = Review::create($validated);
        }

        return ReviewsResource::make($review);
    }
}
