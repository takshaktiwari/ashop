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

    <form method="POST" action="" class="card" id="products-form">
        @csrf
        <x-admin.paginator-info :items="$products" class="card-header pb-0" />
        <div class="card-header text-end pt-0">
            <a href="{{ route('admin.shop.products.export.excel', request()->all()) }}"
                class="badge bg-primary py-1 px-3 rounded-pill fs-12 no-loader">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <th>
                        <div class="form-check-inline d-block">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="checkAll">
                            </label>
                        </div>
                    </th>
                    <th>Image</th>
                    <th style="min-width: 200px;">Name</th>
                    <th>Category</th>
                    <th class="text-nowrap">Price <span class="small">(Net/Sell)</span></th>
                    <th>Status</th>
                    <th style="min-width: 100px;">Action</th>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <div class="form-check-inline d-block">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input imports-check"
                                            name="products[]" value="{{ $product->id }}">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('shop.products.show', [$product]) }}" target="_blank">
                                    <img src="{{ $product->image_sm() }}" alt="" style="max-height: 50px;"
                                        class="rounded">
                                </a>
                            </td>
                            <td>
                                <span class="small lc-2">
                                    @if ($product->product_id)
                                        <a href="{{ route('admin.shop.products.index', ['product_id' => $product->product_id]) }}" title="{{ $product->productParent?->name }}" class="badge bg-primary d-inline-block fs-12">
                                            <i class="fas fa-code-branch"></i>
                                        </a>
                                    @endif
                                    {{ $product->name }}
                                </span>
                                <div class="small"><b>SKU: </b>{{ $product->sku }}</div>
                                <div class="small text-success">
                                    {{ $product->updated_at->format('d-M-Y h:i a') }}
                                </div>
                            </td>
                            <td class="lh-1">
                                @foreach ($product->categories as $category)
                                    <a href="{{ route('admin.shop.products.index', ['category' => $category->id]) }}"
                                        class="small">
                                        {{ $category->name }}
                                    </a>
                                    @if (!$loop->last)
                                        <span>,</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="text-success small" title="Net Price">
                                    <b>Net: </b>{{ $product->formattedNetPrice() }}
                                </div>
                                <div class="text-dark fs-12" title="Sell Price">
                                    <b>Sell: </b>{{ $product->formattedSellPrice() }}
                                </div>
                                @if ($product->deal_price)
                                    <div class="text-dark text-nowrap" title="Deal Price">
                                        <b>Deal: </b>{{ $product->formattedDealPrice() }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div
                                    class="d-block font-weight-bold {{ $product->status ? 'text-success' : 'text-danger' }}">
                                    {{ $product->status ? 'Active' : 'In-Active' }}
                                </div>
                                <span
                                    class="d-block font-weight-bold {{ $product->featured ? 'text-primary' : 'text-dark' }}">
                                    {{ $product->featured ? 'Featured' : 'Not-Featured' }}
                                </span>
                            </td>
                            <td class="">
                                <a href="{{ route('admin.shop.products.copy', [$product]) }}"
                                    class="btn btn-sm btn-info load-circle"><i class="fas fa-copy"></i></a>
                                <a href="{{ route('admin.shop.products.edit', [$product]) }}"
                                    class="btn btn-sm btn-success load-circle"><i class="fas fa-edit"></i></a>

                                <a href="{{ route('admin.shop.products.delete', [$product]) }}"
                                    class="btn btn-sm btn-danger load-circle">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <div class="small text-nowrap">
                                    {{ $product->created_at->format('d-M-Y h:i a') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-wrap justify-content-between">
            <div>
                <select name="action" id="bulkAction" class="form-control">
                    <option value="">-- Action --</option>
                    <option value="{{ route('admin.shop.products.selected.delete') }}">Delete Selected</option>
                    <option value="{{ route('admin.shop.products.selected.featured', [1]) }}">Featured Selected
                    </option>
                    <option value="{{ route('admin.shop.products.selected.featured', [0]) }}">Not-Featured Selected
                    </option>
                </select>
            </div>
            {{ $products->links() }}
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function($) {
                $('#checkAll').change(function() {
                    $('input.imports-check').prop('checked', $(this).is(':checked'));
                });

                $("#bulkAction").change(function(event) {

                    if ($(this).val() !== '') {
                        if (confirm(
                                'Are you to perform the action on selected items. This process is Irreversible'
                            )) {
                            $("#products-form").attr('action', $(this).val());
                            $("#products-form").submit();
                        }
                    }
                });
            });
        </script>
    @endpush
</x-admin.layout>
