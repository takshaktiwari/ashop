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
                                <div class="col-sm-12 col-6">
                                    <x-ashop-ashop:product-list-item :product="$product" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="row g-2" data-masonry='{"percentPosition": true }'>
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-6">
                                    <x-ashop-ashop:product-card :product="$product" />
                                </div>
                            @endforeach
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
