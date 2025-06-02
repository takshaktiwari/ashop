<x-app-layout>
    <x-breadcrumb title="My Cart" :links="[
        ['text' => 'Home', 'url' => url('/')],
        ['text' => 'Shop', 'url' => route('shop.index')],
        ['text' => 'Cart'],
    ]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush

    <style>
        .cart_page .cart_image img {
            max-height: 6rem;
        }
    </style>
    <section class="ashop_page_wrapper">
        <div class="container cart_page">
            <div class="row g-4 mb-5">
                <div class="col-md-8 mx-auto">
                    @if (!$carts->count())
                        <div class="text-danger">
                            <div class="display-1 text-center">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <h2 class="text-center">Your cart is empty</h2>
                            <h4 class="text-center fw-normal">You have no products in you cart. Browse our products,
                                load your cart and enjoy your shopping.</h4>
                            <div class="text-center mt-4">
                                <a href="{{ url('/') }}" class="btn btn-dark px-3">
                                    <i class="fa-solid fa-house"></i> Home
                                </a>
                                <a href="{{ route('shop.index') }}" class="btn btn-dark px-3">
                                    <i class="fa-solid fa-shop"></i> Shop
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($carts->count())
                        <div class="checkout_action d-flex flex-wrap justify-content-between gap-2 mb-3">
                            <h4 class="my-auto fw-normal">
                                <span class="fs-6">Subtotal: </span>
                                <b>{{ config('ashop.currency.sign', '₹') . $carts->sum('subtotal') }}</b>
                            </h4>

                            <a href="{{ route('shop.checkout.index') }}" class="btn btn-dark px-3">
                                Proceed to buy ({{ $carts->count() }})
                            </a>
                        </div>

                        @foreach ($carts as $cart)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex gap-3 mb-2">
                                        <div class="cart_image">
                                            <a href="{{ route('shop.products.show', [$cart->product]) }}">
                                                <img src="{{ $cart->product->image('sm') }}" alt="product image"
                                                    class="rounded">
                                            </a>
                                        </div>
                                        <div class="cart_details">
                                            <h6 class="mb-1">
                                                <a href="{{ route('shop.products.show', [$cart->product]) }}">
                                                    {{ $cart->product->name }}
                                                </a>
                                            </h6>
                                            @if ($cart->product->subtitle)
                                                <p class="small mb-2">{{ $cart->product->subtitle }}</p>
                                            @endif
                                            <h4>
                                                {{ $cart->product->formattedPrice() }}
                                                <del
                                                    class="fs-6 fw-normal">{{ $cart->product->formattedNetPrice() }}</del>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="cart_actions d-flex gap-3">
                                        <form action="{{ route('shop.carts.update', [$cart]) }}" method="POST"
                                            class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <span>Quantity:</span>
                                            <input type="number" name="quantity"
                                                class="form-control px-1 py-0 rounded-0 cart_quantity"
                                                min="{{ $cart->product->getDetail('min_purchase') ?? 1 }}"
                                                max="{{ $cart->product->getDetail('max_purchase') ?? 10 }}"
                                                style="width: 50px" value="{{ $cart->quantity }}">
                                        </form>
                                        <div class="action_btns d-flex gap-1">
                                            <a href="{{ route('shop.carts.delete', [$cart]) }}"
                                                class="btn btn-sm border-danger text-danger px-3 pt-1 pb-0 lh-sm">
                                                <i class="fas fa-times"></i> Remove
                                            </a>

                                            @if (config('ashop.features.favorites.status', true))
                                                @if ($cart->product->wishlistAuthUser->count())
                                                    <a href="{{ route('shop.user.wishlist.items.toggle', [$cart->product]) }}"
                                                        class="btn btn-sm border-danger text-danger px-2 pt-1 pb-0 lh-sm text-danger">
                                                        <i class="fas fa-heart"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('shop.user.wishlist.items.toggle', [$cart->product]) }}"
                                                        class="btn btn-sm border-primary text-primary px-2 pt-1 pb-0 lh-sm">
                                                        <i class="far fa-heart"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="checkout_action d-flex flex-wrap justify-content-between gap-2 mt-3">
                            <h4 class="my-auto fw-normal">
                                <span class="fs-6">Subtotal: </span>
                                <b>{{ config('ashop.currency.sign', '₹') . $carts->sum('subtotal') }}</b>
                            </h4>

                            <a href="{{ route('shop.checkout.index') }}" class="btn btn-dark px-3">
                                Proceed to buy ({{ $carts->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <hr />

            <x-ashop-ashop:products-group title="Similar products"
                subtitle="Browse other products similar to these in your cart." :categories="$carts->pluck('product')->pluck('categories')->collapse()->pluck('id')->unique()->toArray()" :buttons="[['text' => 'See All', 'url' => route('shop.products.index')]]"
                limit="10" class="py-4" />
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('assets/ashop/script.js') }}"></script>
        <script>
            $(document).ready(function() {
                $(".cart_quantity").change(function() {
                    $(this).parent('form').submit();
                });
            });
        </script>
    @endpush
</x-app-layout>
