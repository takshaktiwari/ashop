<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Areviews\Actions\ReviewAction;
use Takshak\Areviews\Models\Areviews\Review;
use Takshak\Ashop\Http\Resources\ReviewsResource;
use Takshak\Ashop\Models\Shop\Product;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return $request->all();
    }
}
