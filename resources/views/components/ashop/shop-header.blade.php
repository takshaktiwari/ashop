<div class="shop_header pb-4">
    <div class="d-flex flex-sm-nowrap flex-wrap justify-content-between gap-3">
        <div class="flex-fill d-flex gap-3">
            <div class="d-flex d-md-none m-auto">
                <a href="javascript: void(0);" class="rounded mobile-filter-btn btn btn-primary btn-sm text-nowrap">
                    <i class="fa-solid fa-sliders-h m-auto"></i>
                </a>
            </div>
            @if (config('ashop.sections.products.search', true))
                <form action="{{ route('shop.products.index') }}" class="search d-flex">
                    <select name="category" id="search_form_category" class="form-control">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected($category->slug == request('category'))>
                                {{ $category->name }}
                            </option>
                            @foreach ($category->children as $childCategory)
                                <option value="{{ $childCategory->slug }}" @selected($childCategory->slug == request('category'))>
                                    -- {{ $childCategory->name }}
                                </option>
                                @foreach ($childCategory->children as $child2)
                                    <option value="{{ $child2->slug }}" @selected($child2->slug == request('category'))>
                                        -- -- {{ $child2->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    <input name="search" type="text" id="search_form_search" placeholder="Search Here"
                        class="form-control rounded-0" value="{{ request('search') }}">

                    @foreach (request()->except(['category', 'search']) as $input => $value)
                        @if (is_array($value))
                            @foreach ($value as $val)
                                <input type="hidden" name="{{ $input . '[]' }}" value="{{ $val }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $input }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" id="search_form_btn" class="btn btn-dark px-3">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            @endif
        </div>

        <div class="shorting fs-4 my-auto d-flex gap-4">
            @if (config('ashop.sections.products.sorting', true))
                <form action="{{ route('shop.products.index') }}" id="shorting_form">
                    <select name="short_by" id="short_by" class="form-control rounded-pill"
                        onchange="document.getElementById('shorting_form').submit()">
                        <option value="">Default</option>
                        <option value="latest" @selected(request('short_by') == 'latest')>
                            Latest First
                        </option>
                        <option value="oldest" @selected(request('short_by') == 'oldest')>
                            Oldest First
                        </option>
                        <option value="name_asc" @selected(request('short_by') == 'name_asc')>
                            Name (Ascending)
                        </option>
                        <option value="name_desc" @selected(request('short_by') == 'name_desc')>
                            Name (Descending)
                        </option>
                        <option value="price_asc" @selected(request('short_by') == 'price_asc')>
                            Price (Ascending)
                        </option>
                        <option value="price_desc" @selected(request('short_by') == 'price_desc')>
                            Price (Descending)
                        </option>
                    </select>
                    @foreach (request()->except(['short_by']) as $input => $value)
                        @if (is_array($value))
                            @foreach ($value as $val)
                                <input type="hidden" name="{{ $input . '[]' }}" value="{{ $val }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $input }}" value="{{ $value }}">
                        @endif
                    @endforeach
                </form>
            @endif
            @if (config('ashop.sections.products.display_style', true))
                <div class="list-type my-auto text-nowrap">
                    <a href="{{ route('shop.products.index', request()->except('display') + ['display' => 'grid']) }}"
                        class="me-2 fs-5">
                        <i class="fa-solid fa-table-cells-large"></i>
                    </a>
                    <a href="{{ route('shop.products.index', request()->except('display') + ['display' => 'list']) }}" class="fs-5">
                        <i class="fa-solid fa-list"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
