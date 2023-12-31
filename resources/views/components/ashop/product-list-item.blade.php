<div class="card product list_item d-flex flex-sm-row ">
    <div class="product_image">
        <a href="{{ route('shop.products.show', [$product]) }}">
            <img src="{{ $product->image() }}" alt="Card image" class="w-100">
        </a>
    </div>
    <div class="card-body">
        <a href="{{ route('shop.products.show', [$product]) }}" class="card-text lc-2">
            {{ $product->name }}
        </a>
        <p class="subtitle my-1 lc-2">{{ $product->subtitle }}</p>
        <div class="mt-2">
            <b class="me-1">{{ $product->formattedNetPrice() }}</b>
            <del>{{ $product->formattedPrice() }}</del>
        </div>
        <div class="rating small">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            (1024 reviews)
        </div>
        <div class="mt-3">
            <a href="{{ route('shop.carts.store', [$product]) }}" class="btn btn-sm btn-primary rounded-pill px-3 add_to_cart">
                <i class="fas fa-shopping-cart"></i> Add to cart
            </a>
            @if ($product->wishlistAuthUser->count())
                <a href="{{ route('shop.wishlist.items.toggle', [$product]) }}"
                    class="btn btn-sm rounded-pill wishlist_btn fs-5 text-danger">
                    <i class="fas fa-heart"></i>
                </a>
            @else
                <a href="{{ route('shop.wishlist.items.toggle', [$product]) }}"
                    class="btn btn-sm rounded-pill wishlist_btn fs-5 ">
                    <i class="far fa-heart"></i>
                </a>
            @endif
        </div>
    </div>

</div>
