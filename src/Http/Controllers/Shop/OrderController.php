<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with('orderProducts')
            ->where('user_id', auth()->id())
            ->whereNotNull('order_status')
            ->paginate(50);

        return View::first(['shop.user.orders.index', 'ashop::shop.user.orders.index'])->with([
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        $order->load('orderProducts');
        return View::first(['shop.user.orders.show', 'ashop::shop.user.orders.show'])->with([
            'order' => $order
        ]);
    }
}
