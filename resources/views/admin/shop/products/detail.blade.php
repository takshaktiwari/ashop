<x-admin.layout>
    <style>
        .tox-notifications-container {
            display: none;
        }
    </style>

    <x-admin.breadcrumb title='Product Detail' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Products', 'url' => route('admin.shop.products.index')],
        ['text' => 'Detail'],
    ]" :actions="[
        [
            'text' => 'Create New',
            'icon' => 'fas fa-plus',
            'class' => 'btn-success',
            'url' => route('admin.shop.products.create'),
        ],
        ['text' => 'All Products', 'icon' => 'fas fa-list', 'url' => route('admin.shop.products.index')],
    ]" />

    <x-ashop-ashop:product-nav :product="$product" />
    <form action="{{ route('admin.shop.products.detail.update', [$product]) }}" method="POST" class="card shadow-sm">
        <div class="card-body">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Min Purchase </label>
                        <input type="number" name="metas[min_purchase]" class="form-control" value="{{ $product->getDetail('min_purchase') ?? 1 }}"
                            min="1">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Max Purchase </label>
                        <input type="number" name="metas[max_purchase]" class="form-control" value="{{ $product->getDetail('max_purchase') ?? 10 }}"
                            min="1">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">GTIN </label>
                        <input type="text" name="metas[gtin]" class="form-control" value="{{ $product->getDetail('gtin') }}"
                            placeholder="eg. GTIN1012">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">GTIN </label>
                        <input type="text" name="metas[gtin]" class="form-control" value="{{ $product->getDetail('gtin') }}"
                            placeholder="eg. GTIN1012">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Cancellable </label>
                        <select name="metas[cancellable]" class="form-control" required="">
                            <option value="1" {{ $product->getDetail('cancellable') ? 'selected' : '' }} >Yes</option>
                            <option value="0" {{ !$product->getDetail('cancellable') ? 'selected' : '' }} >No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Cancel In </label>
                        <div class="input-group">
                            <input type="number" name="metas[cancel_within]" class="form-control" placeholder="eg ." value="{{ $product->getDetail('cancel_within') ?? 0 }}" required max="15">
                            <span class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Returnable </label>
                        <select name="metas[returnable]" class="form-control bg-light rounded-0">
                            <option value="1" {{ $product->getDetail('returnable') ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0" {{ !$product->getDetail('returnable') ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Return In </label>
                        <div class="input-group">
                            <input type="number" name="metas[return_within]" class="form-control" placeholder="eg ."
                                value="{{ $product->getDetail('return_within') ?? 0 }}"
                                required max="15">
                            <span class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Replaceable </label>
                        <select name="metas[replaceable]" class="form-control bg-light rounded-0">
                            <option value="1" {{ $product->getDetail('replaceable') ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0" {{ $product->getDetail('replaceable') ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Replace In </label>
                        <div class="input-group">
                            <input type="number" name="metas[replace_within]" class="form-control" placeholder="eg ."
                                value="{{ $product->getDetail('replace_within') ?? 0 }}"
                                required max="15">
                            <span class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Product Description</label>
                <textarea name="metas[description]" rows="6" class="form-control summernote-editor">{{ $product->getDetail('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="">Meta Tile</label>
                <textarea name="metas[m_title]" rows="2" class="form-control" maxlength="250">{{ $product->getDetail('m_title') }}</textarea>
            </div>
            <div class="form-group">
                <label for="">Meta Keywords</label>
                <textarea name="metas[m_keywords]" rows="2" class="form-control" maxlength="250">{{ $product->getDetail('m_keywords') }}</textarea>
            </div>
            <div class="form-group">
                <label for="">Meta Description</label>
                <textarea name="metas[m_description]" rows="2" class="form-control" maxlength="250">{{ $product->getDetail('m_description') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-lg rounded-sm btn-dark px-4 btn-loader">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </form>

</x-admin.layout>
