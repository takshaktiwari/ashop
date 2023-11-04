<x-app-layout>
    <x-breadcrumb title="Categories" :links="[['text' => 'Categories']]" />
    <section class="py-5">
        <div class="container">
            <div class="row g-4" data-masonry='{"percentPosition": true }'>
                @foreach ($categories as $category)
                    <div class="col">
                        <div class="card brand_card">
                            <img class="card-img-top" src="{{ $brand->image() }}" alt="Card image">
                            <div class="card-body">
                                <a href="{{ route('shop.products.index', ['brand_id' => $brand->id]) }}" class="card-text">{{ $brand->name }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $categories->links() }}
            </div>
        </div>
    </section>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
            integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
        </script>
    @endpush
</x-app-layout>
