<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\DataTables\OrdersDataTable;
use Takshak\Ashop\Mail\OrderUpdateMail;
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

        $notes = $request->post('notes');
        if(empty($notes)) {
            $notes = config('ashop.order.status_messages.' . $order->order_status, 'Order has been updated');
        }

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $request->post('order_status'),
            'payment_status' => $request->boolean('payment_status'),
            'notes' => $notes
        ]);

        if ($order->user?->email) {
            dispatch(function () use ($order) {
                Mail::to($order->user?->email)->send(new OrderUpdateMail($order, $order->orderUpdate));
            })->onQueue(config('ashop.queues.emails'))->delay(now()->addMinute());
        }

        return redirect()->route('admin.shop.orders.show', $order->id);
    }

}
