<x-admin.layout>
    <x-admin.breadcrumb title='Orders Show' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Orders', 'url' => route('admin.shop.orders.index')],
        ['text' => 'Show'],
    ]" :actions="[
        [
            'text' => 'Dashboard',
            'url' => route('admin.dashboard'),
            'class' => 'btn-dark btn-loader',
        ],
    ]" />

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Order #{{ $order->order_no }}</h5>
                    <h5>{{ $order->orderStatus() }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <th>Payment</th>
                            <td>
                                {{ $order->paymentMode() }}
                                ({{ $order->paymentStatus() }})
                            </td>
                        </tr>
                        <tr>
                            <th>Subtotal</th>
                            <td>{{ config('ashop.currency.sign', '₹') . $order->subtotal }}</td>
                        </tr>
                        <tr>
                            <th>Shipping</th>
                            <td>{{ config('ashop.currency.sign', '₹') . $order->shipping_charge }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                {{ config('ashop.currency.sign', '₹') . $order->discount }}
                                @if ($order->coupon_code)
                                    <span>{{ $order->coupon_code }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>{{ config('ashop.currency.sign', '₹') . $order->total_amount }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        {!! $order->address(4) !!}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Products</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($order->orderProducts as $oProduct)
                        <li class="list-group-item d-flex gap-3">
                            <div class="product_img">
                                <img src="{{ $oProduct->image() }}" alt="product img" class="w-100 rounded"
                                    style="max-height: 70px;">
                            </div>
                            <div class="product_details">
                                <p class="fw-bold mb-1">{{ $oProduct->name }}</p>
                                <p>
                                    {{ config('ashop.currency.sign', '₹') . $oProduct->price }}
                                    <span class="px-1">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    {{ $oProduct->quantity }}
                                    <span class="px-1">
                                        <i class="fas fa-equals"></i>
                                    </span>
                                    {{ config('ashop.currency.sign', '₹') . $oProduct->subtotal }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <form class="card" action="{{ route('admin.shop.orders.updates.store', [$order]) }}" method="POST">
                @csrf
                <div class="card-header">
                    <h5 class="mb-0">Order Updates</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="">Order Status</label>
                            <select name="order_status" class="form-control">
                                @foreach (config('ashop.order.status') as $key => $status)
                                    <option value="{{ $key }}" @selected($key == $order->order_status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="">Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option value="1" @selected($order->payment_status)>
                                    Paid
                                </option>
                                <option value="0" @selected(!$order->payment_status)>
                                    Not Paid
                                </option>
                            </select>
                        </div>
                        <div class="col-12">
                            <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Write order notes."
                                maxlength="254"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark px-4 btn-loader">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Order Updates</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($order->orderUpdates as $update)
                            <li class="list-group-item" style="border-left: 0.3rem solid #888; border-radius: 0.5rem 0px 0px 0.5rem;">
                                <p class="mb-1 lh-base">
                                    <span class="fw-bold">{{ $update->orderStatus() }}</span>
                                    <span class="fs-12 d-block">
                                        {{ $update->created_at->diffForHumans() }}
                                    </span>
                                </p>
                                @if ($update->notes)
                                    <p class="mb-0 small">
                                        {{ $update->notes }}
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
