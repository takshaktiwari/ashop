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
        return View::first(['admin.shop.orders.index', 'ashop::admin.shop.orders.index'])->with([
            'order' => $order
        ]);
    }
}
