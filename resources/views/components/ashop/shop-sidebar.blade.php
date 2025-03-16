<div class="shop_sidebar ">
    <div class="widget">
        <h6 class="shop_sidebar_title">
            <i class="fa-solid fa-caret-right"></i> Categories
        </h6>
        <div class="widget_body">
            <ul>
                @foreach ($categories as $category)
                    <li>
                        <a
                            href="{{ route('shop.products.index', request()->except(['category', 'page']) + ['category' => $category->slug]) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                    @if ($category->children->count())
                        <ul>
                            @foreach ($category->children as $childCategory)
                                <li>
                                    <a
                                        href="{{ route('shop.products.index', request()->except(['category', 'page']) + ['category' => $childCategory->slug]) }}">
                                        {{ $childCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <form action="{{ route('shop.products.index') }}" id="attributes_filter">
        @if ($primaryCategory)
            <div class="widget">
                <h6 class="shop_sidebar_title">
                    <i class="fa-solid fa-caret-right"></i> Brands
                </h6>
                <div class="widget_body">
                    <ul>
                        @foreach ($primaryCategory->brands as $brand)
                            <li class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="brands_ids[]"
                                        value="{{ $brand->id }}"
                                        onchange='document.getElementById("attributes_filter").submit()'
                                        {{ collect(request()->get('brands_ids'))->contains($brand->id) ? 'checked' : '' }}>
                                    <span>{{ $brand->name }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @foreach ($filterAttributes as $attribute => $options)
            <div class="widget">
                <h6 class="shop_sidebar_title">
                    <i class="fa-solid fa-caret-right"></i> {{ $attribute }}
                </h6>
                <div class="widget_body">
                    <ul class="">
                        @foreach ($options as $option)
                            <li class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="attributes[]"
                                        value="{{ $option }}"
                                        onchange='document.getElementById("attributes_filter").submit()'
                                        {{ collect(request()->get('attributes'))->contains($option) ? 'checked' : '' }}>
                                    <span>{{ $option }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

        @foreach (request()->except(['attributes', 'brands_ids']) as $input => $value)
            <input type="hidden" name="{{ $input }}" value="{{ $value }}">
        @endforeach
    </form>
</div>
