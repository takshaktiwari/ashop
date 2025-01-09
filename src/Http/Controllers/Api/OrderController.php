<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\OrdersResource;
use Takshak\Ashop\Http\Resources\ProductsResource;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\Product;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->withCount('orderProducts')
            ->paginate(50);

        return OrdersResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load('orderProducts');
        $order->load('orderUpdates');
        return OrdersResource::make($order);
    }
}
