<x-ashop-ashop:user-account>
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
                                <th>Total Amount</th>
                                <td>
                                    {{ config('ashop.currency.sign', '₹') . $order->totalAmount }}
                                    <i class="fa-solid fa-circle-info text-secondary" data-bs-toggle="tooltip"
                                        title="Subtotal ({{ config('ashop.currency.sign', '₹') . $order->subtotal }}) + Shipping
                                        ({{ config('ashop.currency.sign', '₹') . $order->shipping_charge }}) - Discount
                                        ({{ config('ashop.currency.sign', '₹') . $order->discount }})"></i>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="fw-bold">Shipping Address:</p>
                        <p class="mb-0">{!! $order->address() !!}</p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                @foreach ($order->orderProducts as $orderProduct)
                    <div class="card mb-1">
                        <div class="card-body d-flex gap-3">
                            <div class="product_image">
                                <img src="{{ $orderProduct->image() }}" alt="product image" class="rounded"
                                    style="max-height: 80px">
                            </div>
                            <div class="product_detail flex-fill">
                                <p class="mb-0">{{ $orderProduct->name }}</p>
                                <p class="small">
                                    <del class="me-1 text-secondary">{{ config('ashop.currency.sign', '₹') . $orderProduct->net_price }}</del>
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
                        <div class="d-flex gap-2 mb-4">
                            <div class="dot text-center" style="position: relative">
                                <span class="d-block">
                                    <i class="fa-solid fa-circle-dot"></i>
                                </span>
                                <span class="bg-dark" style="width: 4px; position: absolute; height: 100%; left: 6px;"></span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-bold mb-0 mt-1 lh-1">Order Placed</p>
                                <span class="d-block small text-secondary mb-1">
                                    <em class="small">22-12-1212 23:23:23</em>
                                </span>
                                <p class="small mb-0 text-secondary">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <div class="dot text-center">
                                <span class="d-block">
                                    <i class="fa-solid fa-circle-dot"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-bold mb-0 mt-1 lh-1">
                                    Order Placed
                                </p>
                                <span class="d-block small text-secondary mb-1">
                                    <em class="small">22-12-1212 23:23:23</em>
                                </span>

                                <p class="small mb-0 text-secondary">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                        </div>
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
