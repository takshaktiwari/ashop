<x-app-layout>
    <x-breadcrumb title="Products" :links="[
        ['text' => 'Home', 'url' => url('/')],
        ['text' => 'Shop', 'url' => route('shop.index')],
        ['text' => 'Products'],
    ]" />

    @if ($category)
        @section('metatags')
            <x-ametas-ametas:metatags :tags="[
                'title' => config('ashop.shop.name') . ' | ' . $category->display_name,
                'description' => $category->description,
            ]" />
        @endsection
    @endif

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="ashop_page_wrapper">
        <div class="container products_page">
            <div class="row g-4 mb-5">
                @if (config('ashop.sections.products.sidebar', true))
                    <div class="col-lg-3 col-md-4">
                        <x-ashop-ashop:shop-sidebar :category="$category" :filterAttributes="$filterAttributes" />
                    </div>
                @endif

                <div @class([
                    'col-lg-12' => !config('ashop.sections.products.sidebar', true),
                    'col-lg-9 col-md-8' => config('ashop.sections.products.sidebar', true),
                ])>
                    <x-ashop-ashop:shop-header />

                    @if ($products->currentPage() == 1)
                        @if ($category?->banner() && config('ashop.sections.products.show_banner', true))
                            <img src="{{ $category->banner() }}" alt="banner" class="w-100 rounded mb-4">
                        @endif

                        @if ($category?->children?->count() && config('ashop.sections.products.show_subcategories', true))
                            <div class="row g-2 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 mb-4">
                                @foreach ($category->children as $child)
                                    <div class="col">
                                        <div class="card ">
                                            <img class="card-img-top" src="{{ $child->image() }}" alt="Card image">
                                            <div class="card-body p-2">
                                                <a href="{{ route('shop.products.index', ['category' => $child->slug]) }}"
                                                    class="small my-auto">
                                                    <span class="small">{{ $child->name() }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if (request('display') == 'list')
                        <div class="row g-2">
                            @foreach ($products as $product)
                                <div class="col-12">
                                    <x-ashop-ashop:product-list-item :product="$product" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="row g-2 row-cols-2 row-cols-lg-4" data-masonry='{"percentPosition": true }'>
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
                                <p class="fw-light fs-5 ">Nothing found with your search creteria, please search or
                                    filter using some other keywords or you can write us or explore more.</p>

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

            <x-ashop-ashop:products-viewed-history title="Products Viewed "
                subtitle="Here are the products you have viewed recently" limit="25" class="py-0" />
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
            integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
        </script>
    @endpush
</x-app-layout>
