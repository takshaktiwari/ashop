<div class="product_group {{ $attributes['class'] ?? 'py-5' }}">
    <div class="{{ $container ? 'container' : 'false' }}">
        @if ($title)
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
