<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\DataTables\OrdersDataTable;
use Takshak\Ashop\Models\Shop\Order;

class OrderController extends Controller
{
    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render(
            View::exists('admin.shop.orders.index') ? 'admin.shop.orders.index' : 'ashop::admin.shop.orders.index'
        );
    }

    public function show(Order $order)
    {
        $order->load(['orderUpdates' => function ($query) {
            $query->orderBy('order_updates.id', 'desc');
        }]);

        return View::first(['admin.shop.orders.show', 'ashop::admin.shop.orders.show'])->with([
            'order' => $order
        ]);
    }
}
