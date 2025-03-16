<div class="product_group {{ $attributes['class'] ?? 'py-5' }}">
    <div class="{{ $container ? 'container' : 'false' }}">
        @if ($title)
            <div class="d-flex gap-3">
                <h2 class="fw-bold my-auto text-nowrap">{{ $title }}</h2>
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
        @endif
        {!! $heading !!}
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
