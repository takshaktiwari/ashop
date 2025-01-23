<div {{ $attributes->merge(['class' => 'products_viewed']) }}>
    @if ($title)
        <h4 class="fw-bold mb-0">{{ $title }}</h4>
    @endif
    @if ($subtitle)
        <p class="mb-2">{{ $subtitle }}</p>
    @endif

    <div class="d-flex gap-2 horizontal-scroll py-2">
        @foreach ($productsViewed as $pViewed)
            <div class="scroll-item">
                <a href="{{ route('shop.products.show', [$pViewed->product]) }}">
                    <img src="{{ $pViewed->product->image('sm') }}" class="w-100 rounded" />
                </a>
                <p class="small mb-0 viewed_at">{{ $pViewed->created_at->diffForHumans() }}</p>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
    <style>
        .products_viewed{
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .horizontal-scroll {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            scroll-behavior: smooth;
        }

        .horizontal-scroll .scroll-item {
            min-width: 90px;
        }

        .horizontal-scroll .scroll-item .viewed_at {
            font-size: 0.7rem;
            font-style: italic;
        }

        .horizontal-scroll .scroll-item:hover img {
            scale: 1.07;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
            transition: all 0.06s ease-in-out;
        }

        .horizontal-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .horizontal-scroll::-webkit-scrollbar-track {
            background: #e0e0e0;
        }

        .horizontal-scroll::-webkit-scrollbar-thumb {
            background: #a5a5a5;
        }

        .horizontal-scroll::-webkit-scrollbar-thumb:hover {
            background: #8f8f8f;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('assets/ashop/script.js') }}"></script>
@endpush
