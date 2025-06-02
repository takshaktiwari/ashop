<x-app-layout>
    <x-breadcrumb title="Checkout" :links="[
        ['text' => 'Home', 'url' => url('/')],
        ['text' => 'Shop', 'url' => route('shop.index')],
        ['text' => 'Checkout'],
    ]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="ashop_page_wrapper">
        <div class="container shop_page">
            <div class="row g-4">
                <div class="col-md-6 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="my-auto py-2">
                                Order Summary
                            </h6>
                        </div>
                        <ul class="list-group list-flush rounded-0">
                            @foreach ($cartService->items() as $item)
                                <li class="list-group-item rounded-0 d-flex gap-2">
                                    <div class="image">
                                        <img src="{{ $item->product->image('sm') }}" alt="image" class="rounded"
                                            style="max-height: 80px;">
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
                        <form action="{{ route('shop.checkout.coupon') }}" method="POST" class="card-body coupon_form">
                            @csrf
                            <p class="mb-1">
                                Do you have a coupon?
                                <a href="javascript:void(0)" class="small" data-bs-toggle="modal"
                                    data-bs-target="#couponsModal">(See coupons) </a>
                            </p>
                            <div class="d-flex">
                                @if ($cartService->coupon('code'))
                                    <a href="{{ route('shop.checkout.coupon.remove') }}"
                                        class="btn btn-danger rounded-0">
                                        <i class="fa-solid fa-times"></i>
                                    </a>
                                @endif
                                <input type="text" name="coupon" class="form-control flex-fill"
                                    value="{{ old('coupon', $cartService->coupon('code')) }}"
                                    placeholder="Enter the code" required>

                                <button type="submit" class="btn btn-secondary px-3">Apply</button>
                            </div>

                        </form>
                        <ul class="list-group list-flush rounded-0">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total MRP:</span>
                                <span>
                                    {{ config('ashop.currency.sign', '₹') . number_format($cartService->subtotalNetPrice(), 2) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Discount On MRP:</span>
                                <span>
                                    {{ config('ashop.currency.sign', '₹') . $cartService->discount('net_price') }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Discount Coupon:</span>
                                <span>

                                    @if ($cartService->discount('coupon'))
                                        {{ config('ashop.currency.sign', '₹') . $cartService->discount('coupon') }}
                                    @else
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#couponsModal">Apply Coupon</a>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping Fee:</span>
                                <span>{{ config('ashop.currency.sign', '₹') . $cartService->shippingCharge() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>
                                    {{ config('ashop.currency.sign', '₹') . $cartService->total() }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('shop.checkout.payment') }}" method="POST" class="card">
                        @csrf
                        <div class="card-body">
                            @foreach (config('ashop.payment.modes') as $value => $title)
                                <div class="form-check">
                                    <label class="form-check-label py-2" for="{{ $value }}">
                                        <input type="radio" class="form-check-input" id="{{ $value }}"
                                            name="payment_mode" value="{{ $value }}" @checked(config('ashop.payment.default_mode') == $value)> {{ $title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer py-3 text-end">
                            <button type="submit" class="btn btn-dark px-3">
                                Proceed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" id="couponsModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Select Coupon</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body coupons">
                    <ul class="list-group list-group-flush">
                        @foreach ($coupons as $coupon)
                            <li class="coupon list-group-item py-4">
                                <div class="d-flex gap-2 mb-3">
                                    <a href="javascript:void(0)" class="coupon_code py-1 px-3 fw-bolder rounded"
                                        data-code="{{ $coupon->code }}">
                                        {{ $coupon->code }}
                                    </a>
                                    <span class="my-auto small">Click to apply</span>
                                </div>
                                <h5>{{ $coupon->title }}</h5>
                                <p class="mb-0 small">{!! $coupon->description !!}</p>
                            </li>
                        @endforeach

                        @if ($coupons->isEmpty())
                            <li class="coupon list-group-item py-4">
                                <h4 class="text-center">No coupons found</h4>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $(".coupon .coupon_code").click(function(e) {
                    var code = $(this).attr('data-code');

                    $("form.coupon_form input[name='coupon']").val(code);
                    $("form.coupon_form").submit();
                });
            });
        </script>
    @endpush
</x-app-layout>
