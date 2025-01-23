<x-mail::message>
## Dear {{ $order->user?->name }},

Thank you for placing your order with {{ config('ashop.shop.name') }}! We're excited to get started on fulfilling your purchase. Below are the details of your order for your reference:

- Order No: **#{{ $order->order_no }}**
- Order Date: **{{ $order->created_at->format('d-m-Y h:i A') }}**

Items Ordered:
<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">
    <thead>
        <th>#</th>
        <th>Name</th>
        <th>Qty</th>
        <th>Price</th>
    </thead>
    <tbody>
        @foreach ($order->orderProducts as $orderProduct)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $orderProduct->name }}</td>
                <td>{{ $orderProduct->quantity }}</td>
                <td style="text-align: right">{{ $orderProduct->priceFormat('price') }}</td>
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

## Shipping Details:
{!! $order->address() !!}

Estimated Delivery: 7 - 10 days

We’re preparing your order for shipment and will send you another email with the tracking details once it's on its way.

If you have any questions about your order or need assistance, feel free to contact our support team at <a href="mailto:{{ config('ashop.shop.email') }}">{{ config('ashop.shop.email') }}</a> or <a href="tel:{{ config('ashop.shop.mobile') }}">{{ config('ashop.shop.mobile') }}</a>.

Thank you for choosing {{ config('ashop.shop.name') }}. We look forward to serving you again!

Warm regards, <br />
{{ config('ashop.shop.name') }} <br />
{{ config('ashop.shop.email') }} <br />
{{ config('ashop.shop.website') }}

</x-mail::message>
