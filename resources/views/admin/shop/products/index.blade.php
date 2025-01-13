<x-admin.layout>
    <x-admin.breadcrumb title='Products' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Products']]" :actions="[
        ['text' => 'Filter', 'icon' => 'fas fa-filter', 'class' => 'btn-success', 'url' => '?filter=1'],
        [
            'text' => 'Create New',
            'icon' => 'fas fa-plus',
            'class' => 'btn-success',
            'url' => route('admin.shop.products.create'),
        ],
    ]" />

    @if (request('filter'))
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <input type="text" name="search" class="form-control mb-sm-0 mb-2"
                                placeholder="Search eg. Name, SKU, Tags" value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="category" id="" class="form-control mb-sm-0 mb-2">
                                <option value="">Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                        {{ $category->parent ? '->' . $category->parent->name : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="number" name="min-net_price" class="form-control mb-2"
                                placeholder="< Net Price" value="{{ request('min-net_price') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="number" name="max-net_price" class="form-control mb-2"
                                placeholder="> Net Price" value="{{ request('max-net_price') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="number" name="min-sell_price" class="form-control mb-2"
                                placeholder="< Sell Price" value="{{ request('min-sell_price') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="number" name="max-sell_price" class="form-control mb-2"
                                placeholder="> Sell Price" value="{{ request('max-sell_price') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="number" name="min-stock" class="form-control mb-2" placeholder="< Stock"
                                value="{{ request('min-stock') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="number" name="max-stock" class="form-control mb-2" placeholder="> Stock"
                                value="{{ request('max-stock') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="brand_id" class="form-control mb-sm-0 mb-2">
                                <option value="">-- Brand --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-control mb-sm-0 mb-2">
                                <option value="">-- Status --</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>In-Active
                                </option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="featured" class="form-control mb-sm-0 mb-2">
                                <option value="">-- Featured --</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured
                                </option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>
                                    Not-Featured</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="approved" class="form-control mb-sm-0 mb-2">
                                <option value="">-- Approval --</option>
                                <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>
                                    Not-Approved</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-dark" name="filter" value="1">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-basic border">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card" id="products-form">
        <div class="card-body table-responsive">
            {!! $dataTable->table() !!}
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-admin.layout>
