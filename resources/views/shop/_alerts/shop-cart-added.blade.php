<div class="d-flex gap-2">
    <div class="my-auto">
        <img src="{{ $product->image('sm') }}" alt="image" class="w-100" style="max-height: 80px;">
    </div>
    <div class="my-auto flex-fill">
        <p class="fw-bold mb-1 small">
            {{ $product->name }}
        </p>

        <a href="{{ route('shop.carts.index') }}" class="btn btn-sm btn-info py-0">
            <span class="fw-bold" style="font-size: 0.8rem;">
                <i class="fa-solid fa-cart-shopping"></i> My Cart
            </span>
        </a>
        <a href="{{ route('shop.checkout.index') }}" class="btn btn-sm btn-info py-0">
            <span class="fw-bold" style="font-size: 0.8rem;">Checkout</span>
        </a>
    </div>
</div>
