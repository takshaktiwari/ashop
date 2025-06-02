<div class="card product">
    <div class="product_image">
        <img class="card-img-top" src="{{ $product->image() }}" alt="Card image">
        @if (config('ashop.features.favorites.status', true))
            <div class="d-flex overlay">
                @if ($product->wishlistAuthUser->count())
                    <a href="{{ route('shop.user.wishlist.items.toggle', [$product]) }}"
                        class="btn btn-sm rounded-pill wishlist_btn fs-5 text-danger">
                        <i class="fas fa-heart"></i>
                    </a>
                @else
                    <a href="{{ route('shop.user.wishlist.items.toggle', [$product]) }}"
                        class="btn btn-sm rounded-pill wishlist_btn fs-5">
                        <i class="far fa-heart"></i>
                    </a>
                @endif
            </div>
        @endif
    </div>
    <div class="card-body">
        <a href="{{ route('shop.products.show', [$product]) }}" class="card-text lc-2">{{ $product->name }}</a>
        <div class="mt-1 reviews_rating d-flex gap-2">
            <div class="badge bg-warning my-auto text-dark">
                {{ $product->rating }} <i class="fas fa-star"></i>
            </div>
            <a href="{{ route('shop.products.show', [$product]) }}#reviews_listing" class="my-auto">
                {{ $product->reviews_count }} reviews
            </a>
        </div>
        <div class="mt-2">
            <b class="me-1">{{ $product->formattedPrice() }}</b>
            <del class="small">{{ $product->formattedNetPrice() }}</del>
        </div>
    </div>
    <div class="card-footer text-center ">
        <a href="{{ route('shop.carts.store', [$product]) }}"
            class="btn btn-sm btn-dark rounded-pill px-3 add_to_cart">
            {!! config('ashop.features.cart.button_text', 'Add to cart') !!}
        </a>
    </div>
</div>
