<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class ShopController extends Controller
{
    public function index()
    {
        return View::first(['shop.index', 'ashop::shop.index']);
    }
}
