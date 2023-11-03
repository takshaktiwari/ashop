<x-app-layout>
    <x-breadcrumb title="Shop" :links="[['text' => 'Shop']]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container shop_page">
            <x-ashop-ashop:categories-group title="Shop By Categories" : parent="0" type="featured" limit="10" :buttons="[['text' => 'See All', 'url', route('shop.categories.index')]]" />
        </div>
    </section>
</x-app-layout>
