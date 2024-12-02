<x-app-layout>
    <x-breadcrumb title="Products" :links="[
        ['text' => 'Products', 'url' => route('shop.products.index')],
        ['text' => str($product->name)->limit(20), 'url' => route('shop.products.show', $product)],
        ['text' => 'Reviews', 'url' => route('shop.products.reviews', $product)],
    ]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container products_page">
            <div class="row">
                <div class="col-md-4 ">
                    <div class="sticky-top mb-4">
                        <img src="{{ $product->image_sm() }}" alt="product image" class="w-100 rounded mb-2">
                        <h5>{{ $product->name }}</h5>
                        <div class="mt-2 fs-5">
                            <b class="me-1">{{ $product->formattedPrice() }}</b>
                            <del class="small">{{ $product->formattedNetPrice() }}</del>
                        </div>
                        <div class="mt-1 reviews_rating d-flex gap-2">
                            <div class="badge bg-warning my-auto text-dark fs-6">
                                {{ $product->rating }} <i class="fas fa-star"></i>
                            </div>
                            <span>{{ $product->reviews_count }} reviews</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="reviews_list">
                        <x-areviews-areviews:reviews :model="$product" :addReview="auth()->check()" paginate="true" limit="50"
                            column="col-12" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $(".reviews-stats .stats_progress").click(function(e) {
                    window.location.href = "{{ route('shop.products.reviews', [$product]) }}?reviews_rating=" +
                        $(this).data('rating');
                });

                @if (request('reviews_rating'))
                    $('html, body').animate({
                        scrollTop: $("#reviews_list").offset().top + 225
                    }, 500);
                @endif
            });
        </script>
    @endpush
</x-app-layout>
