<x-app-layout>
    <x-breadcrumb title="Shop" :links="[['text' => 'Shop']]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container shop_page">
            <x-ashop-ashop:categories-group title="Shop By Categories" :parent="0" type="featured" limit="10" :buttons="[['text' => 'See All', 'url' => route('shop.categories.index')]]" />

            <x-ashop-ashop:products-group title="Featured Products" type="featured" limit="10" :buttons="[['text' => 'See All', 'url' => route('shop.products.index', ['featured' => true])]]" />

            <x-ashop-ashop:brands-group title="Shop By Brands" type="featured" limit="12" :buttons="[['text' => 'See All', 'url' => route('shop.brands.index')]]" />
        </div>
    </section>
</x-app-layout>
