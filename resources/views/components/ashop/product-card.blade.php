<div class="card product">
    <div class="product_image">
        <img class="card-img-top" src="{{ $product->image() }}" alt="Card image">
        <div class="d-flex overlay">
            <a href="#" class="btn btn-sm btn-primary rounded-pill px-3 add_to_cart">
                <i class="fas fa-shopping-cart"></i> Add to cart
            </a>
            @if ($product->wishlistAuthUser->count())
                <a href="{{ route('shop.wishlist.items.toggle', [$product]) }}"
                    class="btn btn-sm text-danger rounded-pill wishlist_btn fs-5">
                    <i class="fas fa-heart"></i>
                </a>
            @else
                <a href="{{ route('shop.wishlist.items.toggle', [$product]) }}"
                    class="btn btn-sm rounded-pill wishlist_btn fs-5">
                    <i class="far fa-heart"></i>
                </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <a href="{{ route('shop.products.show', [$product]) }}" class="card-text lc-3">{{ $product->name }}</a>
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
    </div>
    <div class="card-footer text-center ">
        <a href="{{ route('shop.products.show', [$product]) }}"
            class="btn btn-sm btn-primary px-3 rounded-pill details_btn">
            <i class="fas fa-info-circle"></i> Details
        </a>
    </div>
</div>
