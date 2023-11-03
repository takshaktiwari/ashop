<x-app-layout>
    <x-breadcrumb title="Products" :links="[['text' => 'Products', 'url' => route('shop.products.index')], ['text' => $product->name]]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="">
        <div class="container product_page py-5">
            <div class="row g-4">
                <div class="col-lg-4 col-md-5">
                    <div class="product_images sticky-top">
                        <div class="product_md_images">
                            <a href="{{ $product->image('lg') }}" class="zoomImage">
                                <img src="{{ $product->image() }}" alt="primary img" class="w-100">
                            </a>
                            @foreach ($product->images as $image)
                                <a href="{{ $image->image('lg') }}" class="zoomImage">
                                    <img src="{{ $image->image() }}" alt="primary img" class="w-100">
                                </a>
                            @endforeach
                        </div>
                        <div class="product_sm_images">
                            <div class="p-1">
                                <img src="{{ $product->image('sm') }}" alt="primary img" class="w-100">
                            </div>
                            @foreach ($product->images as $image)
                                <div class="p-1">
                                    <img src="{{ $image->image('sm') }}" alt="primary img" class="w-100">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7">
                    <h3 class="fw-bold">{{ $product->name }}</h3>
                    @if ($product->subtitle)
                        <p class="mb-2">{{ $product->subtitle }}</p>
                    @endif
                    <div class="product_price d-flex gap-2">
                        <h3 class="mb-0 fw-bold">{{ $product->formattedPrice() }}</h3>
                        <div class="mt-auto">
                            <del>{{ $product->formattedNetPrice() }}</del>
                        </div>
                    </div>
                    <div class="rating small">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        (1024 reviews)
                    </div>
                    <form action="" class="mt-3" id="add_to_cart_form">
                        <div class="variations mb-3 row g-3">
                            @foreach ($variations as $variation)
                                <div class=" col-xl-4 col-sm-6">
                                    <select name="variants[]" class="form-control variants">
                                        <option value="">
                                            -- Select {{ $variation['display_name'] }} --
                                        </option>
                                        @foreach ($variation['variants'] as $variant)
                                            <option value="{{ $variant['id'] }}" @selected(collect(request('variants'))->contains($variant['id']))>
                                                {{ $variant['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex gap-3">
                            <b class="my-auto">
                                Quantity:
                            </b>
                            <input type="number" name="quantity" id="cart_quantity" class="form-control" min="1"
                                value="1" required>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" id="add_to_cart_btn" class="btn btn-primary px-3 rounded-pill">
                                <i class="fas fa-shopping-cart"></i> Add cart
                            </button>
                            <button type="submit" id="buy_now_btn" class="btn btn-primary px-3 rounded-pill">
                                <i class="fas fa-external-link-alt"></i> Buy Now
                            </button>
                            @if ($product->wishlistAuthUser->count())
                                <a href="{{ route('shop.wishlist.items.toggle', [$product]) }}" id="add_to_cart_btn"
                                    class="btn btn-danger px-3 rounded-pill">
                                    <i class="fas fa-heart"></i> Wishlist
                                </a>
                            @else
                                <a href="{{ route('shop.wishlist.items.toggle', [$product]) }}" id="add_to_cart_btn"
                                    class="btn btn-primary px-3 rounded-pill">
                                    <i class="far fa-heart"></i> Wishlist
                                </a>
                            @endif
                        </div>
                    </form>
                    <hr />
                    @if ($product->brand)
                        <div class="brand mb-2">
                            <b class="me-1">Brand: </b>
                            <a href="{{ route('shop.products.index', ['brand' => $product->brand->slug]) }}">
                                {{ $product->brand->name }}
                            </a>
                        </div>
                    @endif
                    <div class="categories mb-2">
                        <b class="me-1">Categories: </b>
                        @foreach ($product->categories as $category)
                            <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}">
                                {{ $category->display_name }}
                            </a>
                            @if (!$loop->last)
                                <span class="me-1">,</span>
                            @endif
                        @endforeach
                    </div>
                    @if ($product->metas->where('key', 'attributes')->count())
                        <ul class="list-group mb-4 mt-3">
                            @foreach ($product->metas->where('key', 'attributes') as $attribute)
                                <li class="list-group-item">
                                    <b>{{ $attribute->name }}: </b>
                                    {{ $attribute->getValue()->implode(', ') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <hr />
                    @endif
                    {!! $product->details('description') !!}
                </div>
            </div>
        </div>
        <hr />
        <x-ashop-ashop:products-group title="Related Products" :categories="$product->categories->pluck('id')->toArray()" limit="10" />
    </section>

    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"
            integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"
            integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
            integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('assets/ashop/jquery.zoom.min.js') }}"></script>
        <script>
            $(document).ready(function() {

                $("select.variants").change(function (e) {
                    e.preventDefault();

                    var variants = [];
                    document.querySelectorAll("select.variants").forEach(elem => {
                        if(elem.value){
                            variants.push(eval(elem.value));
                        }
                    });

                    var currentUrl = "{{ route('shop.products.show', [$product] + request()->except('variants')) }}";

                    currentUrl += currentUrl.includes("?") ? '&' : '?';
                    currentUrl += $.param({variants: variants});

                    window.location.href = currentUrl;
                });


                $('a.zoomImage').zoom({
                    magnify: 1.75,
                    url: $(this).attr('href')
                });

                $('.product_md_images').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    autoplay: true,
                    fade: true,
                    asNavFor: '.product_sm_images'
                });
                $('.product_sm_images').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    asNavFor: '.product_md_images',
                    dots: true,
                    centerMode: true,
                    focusOnSelect: true
                });

            });
        </script>
    @endpush
</x-app-layout>
