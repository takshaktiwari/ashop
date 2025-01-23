<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\OrdersResource;
use Takshak\Ashop\Http\Resources\ProductsResource;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\OrderUpdate;
use Takshak\Ashop\Models\Shop\Product;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()?->id)
            ->withCount('orderProducts')
            ->paginate(50);

        return OrdersResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load(['orderProducts' => function ($query) {
            $query->with(['product' => function ($query) {
                $query->with(['metas' => function ($query) {
                    $query->whereIn('shop_metas.name', [
                        "cancellable", "cancel_within", "returnable", "return_within", "replaceable", "replace_within"
                    ]);
                }]);
            }]);
        }]);

        $order->load(['orderUpdates' => function ($query) {
            $query->latest();
        }]);

        return OrdersResource::make($order)->additional([
            'data' => [
                'cancellable' => $order->cancellable(),
                'returnable' => $order->returnable(),
                'replaceable' => $order->replaceable(),
            ]
        ]);
    }

    public function cancel(Order $order)
    {
        abort_if(!$order->cancellable(), 403, 'Order is not cancellable');
        $order->order_status = 'cancelled';
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status ?? false,
            'notes' => 'Order Cancelled by user: ' . auth()->user()->name
        ]);

        return response()->json(['data' => ['message' => 'Order has been cancelled successfully']]);
    }

    public function orderReturn(Order $order)
    {
        abort_if(!$order->returnable(), 403, 'Order is not returnable');
        $order->order_status = 'return_requested';
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status ?? false,
            'notes' => 'Order Return has been requested by user: ' . auth()->user()->name
        ]);

        return response()->json(['data' => ['message' => 'Order Return has been requested successfully']]);
    }

    public function replace(Order $order)
    {
        abort_if(!$order->replaceable(), 403, 'Order is not replaceable');
        $order->order_status = 'replace_requested';
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status ?? false,
            'notes' => 'Order Replacement has been requested by user: ' . auth()->user()->name
        ]);

        return response()->json(['data' => ['message' => 'Order Replacement has been requested successfully']]);
    }
}
