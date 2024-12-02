<x-app-layout>
    <x-breadcrumb title="Shop" :links="[['text' => 'Shop']]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container shop_page">

            @foreach (config('ashop.sections.home', []) as $section => $attributes)
                @if ($section == 'new_arrivals' && config('ashop.sections.home.new_arrivals.status', true))
                    <x-ashop-ashop:products-group title="New Arrivals" type="latest" :limit="config('ashop.sections.home.new_arrivals.items', 10)"
                        :buttons="[['text' => 'See All', 'url' => route('shop.products.index', ['latest' => true])]]" />
                @endif

                @if ($section == 'featured' && config('ashop.sections.home.featured.status', true))
                    <x-ashop-ashop:products-group title="Featured Products" type="featured" :limit="config('ashop.sections.home.featured.items', 10)"
                        :buttons="[['text' => 'See All', 'url' => route('shop.products.index', ['featured' => true])]]" />
                @endif

                @if ($section == 'popular' && config('ashop.sections.home.popular.status', true))
                    <x-ashop-ashop:products-group title="Popular Products" :products="$popularProducts" :buttons="[['text' => 'See All', 'url' => route('shop.products.index')]]" />
                @endif

                @if ($section == 'categories' && config('ashop.sections.home.categories.status', true))
                    <x-ashop-ashop:categories-group title="Shop By Categories" :parent="0" type="featured"
                        :limit="config('ashop.sections.home.categories.items', 10)" :buttons="[['text' => 'See All', 'url' => route('shop.categories.index')]]" />
                @endif

                @if ($section == 'featured_categories' && config('ashop.sections.home.featured_categories.status', true))
                    @foreach ($featuredCategories as $fCategory)
                        <x-ashop-ashop:products-group :title="$fCategory->display_name" :subtitle="$fCategory->excerpt(100)" :products="$fCategory->products"
                            :buttons="[
                                [
                                    'text' => 'See All',
                                    'url' => route('shop.products.index', ['category' => $fCategory->slug]),
                                ],
                            ]" />
                    @endforeach
                @endif

                @if ($section == 'brands' && config('ashop.sections.home.brands.status', true))
                    <x-ashop-ashop:brands-group title="Shop By Brands" type="featured" :limit="config('ashop.sections.home.brands.items', 12)"
                        :buttons="[['text' => 'See All', 'url' => route('shop.brands.index')]]" />
                @endif

                @if ($section == 'top_categories' && config('ashop.sections.home.top_categories.status', true))
                    @foreach ($topCategories as $tCategory)
                        <x-ashop-ashop:products-group :title="$tCategory->display_name" :subtitle="$tCategory->excerpt(100)" :products="$tCategory->products"
                            :buttons="[
                                [
                                    'text' => 'See All',
                                    'url' => route('shop.products.index', ['category' => $tCategory->slug]),
                                ],
                            ]" />
                    @endforeach
                @endif
            @endforeach
        </div>
    </section>
</x-app-layout>
