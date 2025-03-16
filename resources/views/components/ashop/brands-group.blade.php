<div class="product_group py-5">
    <div class="container">
        <div class="d-flex gap-3">
            <h2 class="fw-bold mb-0 text-nowrap">{{ $title }}</h2>
            <span class="buttons my-auto">
                @foreach ($buttons as $button)
                    @isset($button['text'])
                        <a href="{{ $button['url'] ?? 'javascript:void(0)' }}" class="fs-6">
                            [{{ $button['text'] }}]
                        </a>
                    @endisset
                @endforeach
            </span>
        </div>

        @if ($subtitle)
            <p class="mb-0">{{ $subtitle }}</p>
        @endif

        <div class="row {{ $columns }} g-2 mt-4">
            @foreach ($brands as $brand)
                <div class="col">
                    <div class="card brand_card">
                        <img class="card-img-top" src="{{ $brand->image() }}" alt="Card image">
                        <div class="card-body">
                            <a href="{{ route('shop.products.index', ['brand_id' => $brand->id]) }}"
                                class="card-text">{{ $brand->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
