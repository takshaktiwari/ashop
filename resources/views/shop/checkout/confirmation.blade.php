<x-app-layout>
    <x-breadcrumb title="Checkout" :links="[
        ['text' => 'Shop', 'url' => route('shop.index')],
        ['text' => 'Orders', 'url' => '#'],
        ['text' => 'Confirmation'],
    ]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
        <style>
            .order_confirmation .tick_icon {
                font-size: 6rem;
            }
        </style>
    @endpush
    <section class="py-5">
        <div class="container shop_page">
            <div class="row g-4">
                <div class="col-md-6 mx-auto  order_confirmation">
                    <div class="text-center mb-3">
                        <div class="tick_icon text-success">
                            <i class="fa-regular fa-circle-check"></i>
                        </div>
                        <h1>Order Placed</h1>
                        <p class="lead">Your order has been successfully placed. We appreciate your business and are
                            excited to deliver your items to you soon.</p>

                        <a href="{{ route('shop.index') }}" class="btn btn-dark px-3">
                            <i class="fa-solid fa-bag-shopping"></i> Shop More
                        </a>
                        <a href="{{ route('shop.user.orders.index') }}" class="btn btn-dark px-3">
                            <i class="fa-solid fa-box"></i> All Orders
                        </a>
                    </div>

                    <div class="card shadow-sm mb-3">
                        <div class="card-header">
                            <h6 class="my-auto py-2">
                                Order Summary
                            </h6>
                        </div>
                        <ul class="list-group list-flush rounded-0">
                            @foreach ($order->orderProducts as $item)
                                <li class="list-group-item rounded-0 d-flex gap-2">
                                    <div class="image">
                                        <img src="{{ $item->product->image('sm') }}" alt="image" class="rounded"
                                            style="max-height: 100px;">
                                    </div>
                                    <div class="flex-fill my-auto">
                                        <a href="{{ route('shop.products.show', [$item->product]) }}" class="d-block"
                                            target="_blank">
                                            {{ $item->product->name }}
                                        </a>
                                        <del class="text-secondary me-2 small">
                                            {{ config('ashop.currency.sign', '₹') . $item->product->net_price }}
                                        </del>
                                        <span>{{ config('ashop.currency.sign', '₹') . $item->product->price }}</span> x
                                        <span class="fw-bold">{{ $item->quantity }}</span> =
                                        <span class="fw-bold">
                                            {{ config('ashop.currency.sign', '₹') . $item->subtotal }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <ul class="list-group list-flush rounded-0">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>
                                    {{ config('ashop.currency.sign', '₹') . $order->subtotal }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Discount:</span>
                                <span>
                                    {{ config('ashop.currency.sign', '₹') . $order->discount }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping Fee:</span>
                                <span>Free</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Payment Method:</span>
                                <span>
                                    {{ $order->payment_mode }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Payment Status:</span>
                                <span>
                                    {{ $order->paymentStatus() }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>
                                    {{ config('ashop.currency.sign', '₹') . $order->total_amount }}
                                </span>
                            </li>

                        </ul>

                        <div class="card-body">
                            <p class="fw-bold mb-2">Delivery Address:</p>

                            <p class="mb-0">
                                <span>{{ $order->name }},</span>
                                <span>{{ $order->mobile }}</span>
                            </p>
                            <p class="mb-0 small">
                                <span>{{ $order->address_line_1 }},</span>
                                <span>{{ $order->address_line_2 }}</span>
                                <span>{{ $order->landmark }},</span>
                            </p>
                            <p class="mb-0 small">
                                <span>{{ $order->city }},</span>
                                <span>{{ $order->state }}</span>,
                                <span>{{ $order->pincode }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('shop.index') }}" class="btn btn-dark px-3">
                            <i class="fa-solid fa-bag-shopping"></i> Shop More
                        </a>
                        <a href="{{ route('shop.user.orders.index') }}" class="btn btn-dark px-3">
                            <i class="fa-solid fa-box"></i> All Orders
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <hr />

    <x-ashop-ashop:products-group title="You might alo like"
        subtitle="Browse other products similar to these you have ordered." :categories="$order->orderProducts->pluck('product.categories')->collapse()->pluck('id')" :buttons="[['text' => 'See All', 'url' => route('shop.products.index')]]"
        limit="10" class="py-4" />
</x-app-layout>
