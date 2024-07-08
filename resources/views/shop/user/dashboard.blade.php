<x-ashop-ashop:user-account>
    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
        </h4>
    </x-slot:title>


    <p class="lead">
        Welcome,
        <span class="fw-bold">{{ auth()->user()->name }}</span>
        <a href="{{ route('shop.user.profile') }}" class="badge bg-light text-success border">
            <i class="fa-solid fa-edit"></i> Edit
        </a>
    </p>

    <p>
        <b>Email:</b> {{ auth()->user()->email }} <br />
        <span>
            <b>Shipping Address:</b>
            @if ($defaultAddr)
                <span>{{ $defaultAddr->address_line_1 }},</span>
                <span>{{ $defaultAddr->address_line_2 }},</span>
                <span>{{ $defaultAddr->landmark }},</span>
                <span>{{ $defaultAddr->city }},</span>
                <span>{{ $defaultAddr->pincode }},</span>
                <span>{{ $defaultAddr->state }}</span>
                <a href="{{ route('shop.user.addresses.edit', [$defaultAddr]) }}"
                    class="badge bg-light text-success border">
                    <i class="fa-solid fa-edit"></i> Edit
                </a>
            @else
                <span>No Address</span>
                <a href="{{ route('shop.user.addresses.create') }}" class="badge bg-light text-success border">
                    <i class="fa-solid fa-edit"></i> Edit
                </a>
            @endif
        </span>
        <br />
        <span>
            <b>Billing Address:</b>
            @if ($billingAddr)
                <span>{{ $billingAddr->address_line_1 }},</span>
                <span>{{ $billingAddr->address_line_2 }},</span>
                <span>{{ $billingAddr->landmark }},</span>
                <span>{{ $billingAddr->city }},</span>
                <span>{{ $billingAddr->pincode }},</span>
                <span>{{ $billingAddr->state }}</span>
                <a href="{{ route('shop.user.addresses.edit', [$billingAddr]) }}"
                    class="badge bg-light text-success border">
                    <i class="fa-solid fa-edit"></i> Edit
                </a>
            @else
                <span>No Address</span>
                <a href="{{ route('shop.user.addresses.create') }}" class="badge bg-light text-success border">
                    <i class="fa-solid fa-edit"></i> Edit
                </a>
            @endif
        </span>
    </p>

    <p class="mb-2"><b>Orders:</b> You have <b>{{ $pendingOrdersCount }}</b> pending orders and
        <b>{{ $deliveredOrdersCount }}</b> completed orders.
    </p>
    <p><b>Wishlist:</b> {{ $wishlistItemsCount }} items in your wishlist.</p>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                Recent Orders
                <span class="small">
                    <a href="{{ route('shop.user.orders.index') }}" class="small">[All Orders]</a>
                </span>
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Item(s)</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('shop.user.orders.show', [$order]) }}">
                                    #{{ $order->order_no }}
                                </a>
                            </td>
                            <td>{{ $order->order_products_count }} Item(s)</td>
                            <td>{{ $order->orderStatus() }}</td>
                            <td>{{ $order->created_at->format('d-m-Y h:i A') }}</td>
                            <td>{{ config('ashop.currency.sign', '₹') . $order->total_amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-ashop-ashop:products-group subtitle="Browse other products similar to these you have ordered." :buttons="[['text' => 'See All', 'url' => route('shop.products.index')]]"
        limit="8" class="py-4" :container="false" columns="row-cols-2 row-cols-md-3 row-cols-xl-4">
        <x-slot:heading>
            <h4 class="fw-bold">Products you might like</h4>
        </x-slot:heading>
    </x-ashop-ashop:products-group>

    <x-ashop-ashop:user-bottom-nav />
</x-ashop-ashop:user-account>
