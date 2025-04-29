<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .column {
            width: 48%;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #c9c9c9;
            padding: 6px 10px;
            text-align: left;
            vertical-align: top;
        }

        .details-table th {
            background-color: #c9c9c9;
        }

        .details-table .tax-row td {
            background-color: #fafafa;
            font-size: 80%;
            padding: 4px 10px;
        }

        .details-table tfoot td {
            font-weight: bold;
            background-color: #fafafa;
        }

        .status {
            margin-top: 10px;
            font-weight: bold;
            color: green;
            text-align: right;
        }

        .notes {
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }

        .contact-info {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 50px;
            color: #aaa;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <div class="header">
            <img src="{{ setting('logo_full') }}" alt="Company Logo">
            <h2>INVOICE</h2>
        </div>

        <div class="invoice-details">
            <strong>Invoice #:</strong> {{ $invoice->invoice_no }}<br>
            <strong>Date:</strong> {{ $invoice->created_at?->format('d-m-Y') }}
        </div>

        <div class="row">
            <div class="column">
                <div style="margin-bottom: 0.25rem;"><strong>Company Details</strong></div>
                {{ config('ashop.shop.name', 'Shop Name') }}<br>
                {{ config('ashop.shop.address', 'Shop Address') }}
                <div style="margin-bottom: 0.25rem;"></div>
                <b>Email:</b> {{ config('ashop.shop.email', 'company@example.com') }}<br>
                <b>Phone:</b> {{ config('ashop.shop.mobile', '+1 123 456 7890') }}
            </div>
            <div class="column">
                <div style="margin-bottom: 0.25rem;"><strong>Customer Details</strong></div>
                {!! $order->address(3) !!}
                <div style="margin-bottom: 0.25rem;"></div>
                <b>Email:</b> {{ $order->user?->email }}<br>
                <b>Phone:</b> {{ $order->user?->mobile }}
            </div>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderProducts as $oProduct)
                    @php
                        $priceBreakDown = $oProduct->getPriceBreakDown();
                    @endphp
                    <tr>
                        <td>{{ $oProduct->name }}</td>
                        <td>{{ $oProduct->quantity }}</td>
                        <td>{{ $priceBreakDown['basePrice'] }}</td>
                        <td>{{ $priceBreakDown['subtotal'] }}</td>
                    </tr>
                    @foreach ($priceBreakDown['taxAmounts'] as $key => $taxAmount)
                        <tr class="tax-row">
                            <td colspan="3" style="text-align: right">{{ $key }} ({{ $taxAmount['percent'] }}%)</td>
                            <td>{{ $taxAmount['amount'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;">
                        <strong>Subtotal :</strong>
                    </td>
                    <td><strong>{{ $order->priceFormat('subtotal') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">
                        <strong>Shipping Charge :</strong>
                    </td>
                    <td><strong>{{ $order->priceFormat('shipping_charge') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">
                        <span class="status">(Paid)</span>
                        <strong>Total :</strong>
                    </td>
                    <td><strong>{{ $order->priceFormat('total_amount') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="notes">
            <p><strong>Delivery Instructions:</strong><br>
                Please allow 3-5 business days for delivery. Contact us if you face any issues.</p>

            <p><strong>Important Information:</strong><br>
                Returns accepted within 30 days. Products must be unused and in original packaging.</p>
        </div>

        <div class="contact-info">
            Company Support: support@example.com | +1 234 567 8900
        </div>

        <div class="footer">
            Thank you for your business!
        </div>
    </div>

</body>

</html>
