<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\DataTables\OrdersDataTable;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\OrderUpdate;

class OrderUpdateController extends Controller
{
    public function store(Order $order, Request $request)
    {
        $request->validate([
            'order_status' => 'required',
            'payment_status' => 'required',
            'notes' => 'nullable|max:255'
        ]);

        $order->update([
            'order_status' => $request->post('order_status'),
            'payment_status' => $request->boolean('payment_status'),
        ]);

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $request->post('order_status'),
            'payment_status' => $request->boolean('payment_status'),
            'notes' => $request->post('notes')
        ]);

        return redirect()->route('admin.shop.orders.show', $order->id);
    }
}
