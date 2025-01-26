<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Mail\OrderUpdateMail;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\OrderUpdate;

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
        $order->load(['orderProducts' => function ($query) {
            $query->with(['product' => function ($query) {
                $query->select('products.id', 'products.name', 'products.slug');
                $query->with('metas');
            }]);
        }]);
        $order->load(['orderUpdates' => function ($query) {
            $query->latest();
        }]);

        return View::first(['shop.user.orders.show', 'ashop::shop.user.orders.show'])->with([
            'order' => $order
        ]);
    }

    public function cancel(Order $order)
    {
        $order->order_status = 'cancelled';
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'notes' => config('ashop.order.status_messages.cancelled', 'Order Cancelled by user: ' . auth()->user()->name)
        ]);

        if ($order->user?->email) {
            dispatch(function () use ($order) {
                Mail::to($order->user?->email)->send(new OrderUpdateMail($order, $order->orderUpdate));
            })->onQueue(config('ashop.queues.emails'))->delay(now()->addMinute());
        }

        return to_route('shop.user.orders.show', $order->id)->withSuccess('Order has been cancelled successfully');
    }

    public function replace(Order $order)
    {
        $order->order_status = 'replace_requested';
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'notes' => config('ashop.order.status_messages.replace_requested', 'Order Replacement has been requested by user: ' . auth()->user()->name)
        ]);

        if ($order->user?->email) {
            dispatch(function () use ($order) {
                Mail::to($order->user?->email)->send(new OrderUpdateMail($order, $order->orderUpdate));
            })->onQueue(config('ashop.queues.emails'))->delay(now()->addMinute());
        }

        return to_route('shop.user.orders.show', $order->id)->withSuccess('Order Replacement has been requested successfully');
    }
}
