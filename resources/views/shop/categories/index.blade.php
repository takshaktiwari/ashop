<x-app-layout>
    <x-breadcrumb title="Categories" :links="[['text' => 'Categories']]" />
    <section class="py-5">
        <div class="container">
            <div class="row g-4" data-masonry='{"percentPosition": true }'>
                @foreach ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card">
                            <img class="card-img-top" src="{{ $category->image() }}" alt="Card image">
                            <div class="card-body d-flex justify-content-between gap-3">
                                <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}" class="fs-5 my-auto">{{ $category->name }}</a>
                                @if ($category->children_count)
                                    <a href="{{ route('shop.categories.index', ['category_id' => $category->id]) }}" class="my-auto">
                                        <i class="fas fa-arrow-alt-circle-right"></i>
                                    </a>
                                @endif
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
