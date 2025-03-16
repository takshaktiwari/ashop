<x-app-layout>
    <x-breadcrumb title="Brands" :links="[['text' => 'Brands']]" />
    <section class="ashop_page_wrapper">
        <div class="container">
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2"
                data-masonry='{"percentPosition": true }'>
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

            <div class="mt-5">
                {{ $brands->links() }}
            </div>
        </div>
    </section>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
            integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
        </script>
    @endpush
</x-app-layout>
