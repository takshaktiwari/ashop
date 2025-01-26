<x-ashop-ashop:mail-layout>

    <h3>Dear {{ $order->user?->name }},</h3>
    <br />
    <p>
        We hope this message finds you well. We're writing to inform you about a recent update to your order <b>#{{ $order->order_no }}</b>.
    </p>
    <br />
    <br />
    <p>Here are the details of the update:</p>
    <ul>
        <li><b>Order No:</b> #{{ $order->order_no }}</li>
        <li><b>Order Date:</b> {{ $order->created_at->format('d-m-Y h:i A') }}</li>
        <li><b>Total:</b> {{ $order->priceFormat('total_amount') }}</li>
        <li>
            <b>Shipping Address:</b>
            <ul>
                {!! $order->address() !!}
            </ul>
        </li>
        <li><b>Order Status:</b> {{ $order->orderUpdate->orderStatus() }}</li>
        <li><b>Payment Status:</b> {{ $order->orderUpdate->paymentStatus() }}</li>
        <li><b>Updated at:</b> {{ $order->orderUpdate->created_at->format('d-m-Y h:i A') }}</li>
        <li><b>Notes:</b> {{ $order->orderUpdate->notes }}</li>
    </ul>
</x-ashop-ashop:mail-layout>
