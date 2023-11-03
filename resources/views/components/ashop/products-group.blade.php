<div class="product_group py-5">
    <div class="container">
        <h2 class="fw-bold mb-0">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mb-0">{{ $subtitle }}</p>
        @endif

        <div class="row {{ $columns }} g-2 mt-4">
            @foreach ($products as $product)
                <div class="col">
                    <x-ashop-ashop:product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</div>
