<x-app-layout>
    <x-breadcrumb title="Products" :links="[['text' => 'Products']]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container products_page">
            <div class="row g-4">
                <div class="col-lg-3 col-md-4">
                    <x-ashop-ashop:shop-sidebar />
                </div>
                <div class="col-lg-9 col-md-8">
                    <x-ashop-ashop:shop-header />
                    @if (request('display') == 'list')
                        <div class="row g-2">
                            @foreach ($products as $product)
                                <div class="col-12">
                                    <x-ashop-ashop:product-list-item :product="$product" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="row g-2 row-cols-2 row-cols-lg-4 " data-masonry='{"percentPosition": true }'>
                            @foreach ($products as $product)
                                <div class="col">
                                    <x-ashop-ashop:product-card :product="$product" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (!$products->count())
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <span class="display-2">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                </span>

                                <h3>No products found.</h3>
                                <p class="fw-light fs-5 ">Nothing found with your search creteria, please search or filter using some other keywords or you can write us or explore more.</p>

                                <div class="actions">
                                    <a href="{{ url('/') }}" class="btn btn-dark px-4">
                                        <i class="fas fa-home"></i> Home
                                    </a>
                                    <a href="{{ route('shop.products.index') }}" class="btn btn-dark px-4">
                                        <i class="fas fa-box"></i> Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="my-5">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
            integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
        </script>
    @endpush
</x-app-layout>
