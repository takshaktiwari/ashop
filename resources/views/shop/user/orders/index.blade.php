<x-ashop-ashop:user-account>
    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-solid fa-box me-2"></i> All Orders
        </h4>
    </x-slot:title>
    @push('styles')
        <style>
            #user_orders .order_product_images {
                position: relative;
            }

            #user_orders .order_product_image:nth-child(2),
            .order_product_image:nth-child(3) {
                position: absolute;
            }

            #user_orders .order_product_images .order_product_image:nth-child(2) {
                left: 0.3rem;
                top: 0.3rem;
            }

            #user_orders .order_product_images .order_product_image:nth-child(3) {
                left: 0.6rem;
                top: 0.6rem;
            }
        </style>
    @endpush

    <div id="user_orders">
        @foreach ($orders as $order)
            <div class="card mb-3">
                <div class="card-body d-flex flex-wrap gap-2">
                    <div class="d-flex flex-fill gap-4">
                        <div class="order_product_images">
                            @foreach ($order->orderProducts->take(3) as $orderProduct)
                                <img src="{{ $orderProduct->image() }}" alt="product image"
                                    class="rounded order_product_image border shadow-sm" style="height: 100px;">
                            @endforeach
                        </div>
                        <div class="flex-fill">
                            <p class="fw-bold mb-2">
                                <a href="{{ route('shop.user.orders.show', [$order]) }}">
                                    #{{ $order->order_no }}
                                </a>
                            </p>
                            <p class="small">
                                <span class="text-nowrap">
                                    Placed at: <b>{{ $order->created_at->format('d-M-Y h:i A') }}</b>
                                </span>
                                <br />
                                <span class="text-nowrap">
                                    Ordered items: <b>{{ $order->orderProducts->count() }} Products</b>
                                </span>
                                <br />
                                <span class="text-nowrap">
                                    Order amount: <b>{{ config('ashop.currency.sign', '₹') . $order->totalAmount }}</b>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="action_btns my-auto">
                        <h4 class="mb-1">{{ $order->orderStatus() }}</h4>
                        <a href="" class="btn btn-sm btn-light border border-dark px-4 py-0 my-1">
                            Details
                        </a>
                        <a href="" class="btn btn-sm btn-light border border-dark px-4 py-0 my-1 text-nowrap">
                            Track Package
                        </a>
                    </div>
                </div>
            </div>
        @endforeach


        <x-ashop-ashop:user-bottom-nav />
    </div>
</x-ashop-ashop:user-account>
