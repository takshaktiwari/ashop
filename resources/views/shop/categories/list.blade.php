<x-app-layout>
    <x-breadcrumb title="Categories" :links="[['text' => 'Home', 'url' => url('/')], ['text' => 'Shop', 'url' => route('shop.index')], ['text' => 'Categories']]" />

    <section class="ashop_page_wrapper">
        <div class="container">
            <div class="row g-4" data-masonry='{"percentPosition": true }'>
                @foreach ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="my-auto py-2">
                                    <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </h6>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($category->children as $childCategory)
                                    <a href="{{ route('shop.products.index', ['category' => $childCategory->slug]) }}" class="list-group-item">
                                        {{ $childCategory->name }}
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
            integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
        </script>
    @endpush
</x-app-layout>
