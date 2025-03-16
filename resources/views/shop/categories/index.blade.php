<x-app-layout>
    <x-breadcrumb title="Categories" :links="[['text' => 'Home', 'url' => url('/')], ['text' => 'Shop', 'url' => route('shop.index')], ['text' => 'Categories']]" />

    <section class="ashop_page_wrapper">
        <div class="container">
            <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" data-masonry='{"percentPosition": true }'>
                @foreach ($categories as $category)
                    <div class="col">
                        <div class="card brand_card">
                            <img class="card-img-top" src="{{ $category->image() }}" alt="Card image">
                            <div class="card-body">
                                <a href="{{ route('shop.products.index', ['category_id' => $category->id]) }}" class="card-text">{{ $category->name }}</a>
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
