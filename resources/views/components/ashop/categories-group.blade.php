<div class="product_group py-5">
    <div class="container">
        <h2 class="fw-bold mb-0">
            {{ $title }}
            <span class="buttons">
                @foreach ($buttons as $button)
                    @isset($button['text'])
                        <a href="{{ $button['url'] ?? 'javascript:void(0)' }}" class="fs-6">
                            [{{ $button['text'] }}]
                        </a>
                    @endisset
                @endforeach
            </span>
        </h2>
        @if ($subtitle)
            <p class="mb-0">{{ $subtitle }}</p>
        @endif

        <div class="row {{ $columns }} g-2 mt-4">
            @foreach ($categories as $category)
                <div class="col">
                    <div class="card">
                        <img class="card-img-top" src="{{ $category->image() }}" alt="Card image">
                        <div class="card-body d-flex justify-content-between gap-3">
                            <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}"
                                class="fs-6 my-auto">{{ $category->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
