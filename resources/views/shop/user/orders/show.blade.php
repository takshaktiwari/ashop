<x-ashop-ashop:user-account>
    <x-slot:breadcrumb>
        <x-breadcrumb title="Order: #{{ $order->order_no }}" :links="[['text' => 'Home', 'url' => url('/')], ['text' => 'Dashboard', 'url' => route('shop.user.dashboard')], ['text' => 'Orders', 'url' => route('shop.user.orders.index')], ['text' => 'Detail']]" />
    </x-slot:breadcrumb>

    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-solid fa-box me-2"></i> Order: <b>#{{ $order->order_no }}</b>
        </h4>
        <a href="{{ route('shop.user.orders.index') }}" class="btn btn-sm btn-primary px-3">
            <i class="fa-solid fa-list"></i> Orders
        </a>
    </x-slot:title>

    @push('styles')
    @endpush

    <div id="user_orders_detail">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table mb-0 table-sm">
                            <tr>
                                <th>Placed at</th>
                                <td>{{ $order->created_at->format('d-m-Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Order Status</th>
                                <td>{{ $order->orderStatus() }}</td>
                            </tr>
                            <tr>
                                <th>Payment</th>
                                <td>
                                    <span class="payment_status fw-bold">{{ $order->paymentStatus() }}</span>
                                    <span class="payment_mode">({{ $order->payment_mode }})</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Subtotal</th>
                                <td>{{ $order->priceFormat('subtotal') }}</td>
                            </tr>
                            <tr>
                                <th>Shipping Charge</th>
                                <td>{{ $order->priceFormat('shipping_charge') }}</td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td>
                                    {{ $order->priceFormat('discount') }}
                                    @if ($order->coupon_code)
                                        <span>; Coupon: {{ $order->coupon_code }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>
                                    {{ $order->priceFormat('totalAmount') }}
                                    <i class="fa-solid fa-circle-info text-secondary" data-bs-toggle="tooltip"
                                        title="Subtotal ({{ $order->priceFormat('subtotal') }}) + Shipping
                                        ({{ $order->priceFormat('shipping_charge') }}) - Discount
                                        ({{ $order->priceFormat('discount') }})"></i>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="fw-bold mb-2">Shipping Address:</p>
                        <p class="mb-0">{!! $order->address() !!}</p>

                        <a href="{{ route('shop.user.orders.invoice', [$order]) }}"
                                class="btn btn-sm btn-primary px-3 mt-3">
                                <i class="fas fa-file-invoice"></i> Invoice
                        </a>

                        @if ($order->cancellable())
                            <a href="{{ route('shop.user.orders.cancel', [$order]) }}"
                                class="btn btn-sm btn-danger px-3 mt-3">
                                <i class="fas fa-ban"></i> Cancel
                            </a>
                        @endif
                        @if ($order->replaceable())
                            <a href="{{ route('shop.user.orders.replace', [$order]) }}" class="btn btn-sm btn-danger px-3 mt-3">
                                <i class="fas fa-box-open"></i> Replace
                            </a>
                        @endif
                        @if ($order->returnable())
                            <a href="{{ route('shop.user.orders.return', [$order]) }}" class="btn btn-sm btn-danger px-3 mt-3">
                                <i class="fas fa-truck-loading"></i> Return
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                @foreach ($order->orderProducts as $orderProduct)
                    <div class="card mb-1">
                        <div class="card-body d-flex gap-3">
                            <a href="{{ route('shop.products.show', [$orderProduct->product]) }}" target="_blank" class="product_image">
                                <img src="{{ $orderProduct->image() }}" alt="product image" class="rounded"
                                    style="max-height: 80px">
                            </a>
                            <div class="product_detail flex-fill">
                                <p class="mb-2">
                                    <a href="{{ route('shop.products.show', [$orderProduct->product]) }}" target="_blank">
                                        {{ $orderProduct->name }}
                                    </a>
                                </p>
                                <p class="small">
                                    <del
                                        class="me-1 text-secondary">{{ config('ashop.currency.sign', '₹') . $orderProduct->net_price }}</del>
                                    <b>{{ config('ashop.currency.sign', '₹') . $orderProduct->price }}</b>
                                    <span>x</span>
                                    <b>{{ $orderProduct->quantity }}</b>
                                    <span>=</span>
                                    <b>{{ config('ashop.currency.sign', '₹') . $orderProduct->subtotal }}</b>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-12">
                <div class="card order_updates">
                    <div class="card-body">
                        @foreach ($order->orderUpdates as $update)
                            <div class="d-flex gap-2 mb-3">
                                <div class="dot text-center" style="position: relative">
                                    <span class="d-block">
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                    <span class="bg-dark"
                                        style="width: 4px; position: absolute; height: 90%; left: 6px;"></span>
                                </div>
                                <div class="flex-fill">
                                    <p class="fw-bold mb-0 mt-1 lh-1">{{ $update->orderStatus() }}</p>
                                    <span class="d-block small text-secondary mb-1">
                                        <em class="small">{{ $update->created_at->diffForHumans() }}</em>
                                    </span>
                                    <p class="small mb-0 text-secondary">{{ $update->notes }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <x-ashop-ashop:user-bottom-nav />
    </div>
    @push('scripts')
        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
    @endpush
</x-ashop-ashop:user-account>
