<x-admin.layout>
    <x-admin.breadcrumb title='Details' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Categories', 'url' => route('admin.shop.categories.index')],
        ['text' => 'Details'],
    ]" :actions="[
        [
            'text' => 'Create New',
            'icon' => 'fas fa-plus',
            'url' => route('admin.shop.categories.create'),
            'class' => 'btn-success',
        ],
        [
            'text' => 'All Categories',
            'icon' => 'fas fa-list',
            'url' => route('admin.shop.categories.index'),
            'class' => 'btn-dark',
        ],
    ]" />

    <x-ashop-ashop:inner-nav title="Edit" :links="[
        ['text' => 'Info', 'url' => route('admin.shop.categories.edit', [$category])],

        ['text' => 'Details', 'url' => route('admin.shop.categories.details', [$category])],

        ['text' => 'Brands', 'url' => route('admin.shop.categories.brands', [$category])],

        ['text' => 'Attributes', 'url' => route('admin.shop.categories.attributes', [$category])],
    ]" />
    <form action="{{ route('admin.shop.categories.details.update', [$category]) }}" method="POST"
        enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex">
                        @if ($category?->getMeta('banner_desktop'))
                            <div class="banner pe-3 my-auto">
                                <img src="{{ $category?->getMeta('banner_desktop') }}" alt=""
                                    style="max-height: 65px; max-width: 100px;" class="border rounded">
                            </div>
                        @endif
                        <div class="form-group flex-fill pr-3">
                            <label for="">Category (Desktop)</label>
                            <input type="file" name="metas[banner_desktop]" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex">
                        @if ($category?->getMeta('banner_tablet'))
                            <div class="banner pe-3 my-auto">
                                <img src="{{ $category?->getMeta('banner_tablet') }}" alt=""
                                    style="max-height: 65px; max-width: 100px;" class="border rounded">
                            </div>
                        @endif
                        <div class="form-group flex-fill pr-3">
                            <label for="">Category (Tablet)</label>
                            <input type="file" name="metas[banner_tablet]" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex">
                        @if ($category?->getMeta('banner_mobile'))
                            <div class="banner pe-3">
                                <img src="{{ $category?->getMeta('banner_mobile') }}" alt=""
                                    style="max-height: 65px; max-width: 100px;" class="border rounded">
                            </div>
                        @endif
                        <div class="form-group flex-fill pr-3">
                            <label for="">Category (Mobile)</label>
                            <input type="file" name="metas[banner_mobile]" class="form-control">
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Min Order Qty. </label>
                        <input type="number" name="metas[min_order_qty]" class="form-control" required=""
                            value="{{ $category?->getMeta('min_order_qty') ? $category?->getMeta('min_order_qty') : 1 }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">COD Available </label>
                        <select name="metas[cod]" class="form-control" required="">
                            <option value="1" {{ $category?->getMeta('cod') == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $category?->getMeta('cod') == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Cancellable </label>
                        <select name="metas[cancellable]" class="form-control" required="">
                            <option value="1" {{ $category?->getMeta('cancellable') == 1 ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0" {{ $category?->getMeta('cancellable') == 0 ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Cancel In </label>
                        <div class="input-group">
                            <input type="number" name="metas[cancel_within]" class="form-control" placeholder="eg ."
                                value="{{ $category?->getMeta('cancel_within') }}" required max="15">
                            <span class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Returnable </label>
                        <select name="metas[returnable]" class="form-control bg-light rounded-0">
                            <option value="1" {{ $category?->getMeta('returnable') == 1 ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0" {{ $category?->getMeta('returnable') == 0 ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Return In </label>
                        <div class="input-group">
                            <input type="number" name="metas[return_within]" class="form-control" placeholder="eg ."
                                value="{{ $category?->getMeta('return_within') }}" required max="15">
                            <span class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Replaceable </label>
                        <select name="metas[replaceable]" class="form-control bg-light rounded-0">
                            <option value="1" {{ $category?->getMeta('replaceable') == 1 ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0" {{ $category?->getMeta('replaceable') == 0 ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Replace In </label>
                        <div class="input-group">
                            <input type="number" name="metas[replace_within]" class="form-control"
                                placeholder="eg ." value="{{ $category?->getMeta('replace_within') }}" required
                                max="15">
                            <span class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-4 btn-loader">Submit</button>
        </div>
    </form>

</x-admin.layout>
