<x-ashop-ashop:mail-layout>

    <h3>Dear {{ $order->user?->name }},</h3>
    <br />
    <p>
        Thank you for placing your order with {{ config('ashop.shop.name') }}! We're excited to get started on
        fulfilling your purchase. Below are the details of your order for your reference:
    </p>

    <ul>
        <li>Order No: <b>#{{ $order->order_no }}</b></li>
        <li>Order Date: <b>{{ $order->created_at->format('d-m-Y h:i A') }}</b></li>
    </ul>


    <h4 style="margin-bottom: 10px">Items Ordered:</h4>
    <table border="1" cellpadding="4" cellspacing="0" style="width: 100%; border-collapse: collapse">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>Qty</th>
            <th class="text-end">Price</th>
        </thead>
        <tbody>
            @foreach ($order->orderProducts as $orderProduct)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $orderProduct->name }}</td>
                    <td class="text-center">{{ $orderProduct->quantity }}</td>
                    <td class="text-end">{{ $orderProduct->priceFormat('price') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right">Subtotal</th>
                <th style="text-align: right">{{ $order->priceFormat('subtotal') }}</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right">Shipping</th>
                <th style="text-align: right">{{ $order->priceFormat('shipping_charge') }}</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right">Discount</th>
                <th style="text-align: right">{{ $order->priceFormat('discount') }}</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right">Total</th>
                <th style="text-align: right">{{ $order->priceFormat('total_amount') }}</th>
            </tr>
        </tfoot>
    </table>
    <br />

    <h4 style="margin-bottom: 5px">Shipping Details:</h4>
    {!! $order->address() !!}
    <br />
    <br />
    <p><b>Estimated Delivery:</b> 7 - 10 days</p>
    <br />
    <p>
        We're preparing your order for shipment and will send you another email with the tracking details once it's on
        its way.
    </p>

</x-ashop-ashop:mail-layout>
