<x-admin.layout>
    <x-admin.breadcrumb title='Edit Product' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Products', 'url' => route('admin.shop.products.index')],
        ['text' => 'Edit'],
    ]" :actions="[
        [
            'text' => 'Create New',
            'icon' => 'fas fa-plus',
            'class' => 'btn-success',
            'url' => route('admin.shop.products.create'),
        ],
        [
            'text' => 'All Products',
            'icon' => 'fas fa-list',
            'class' => 'btn-success',
            'url' => route('admin.shop.products.index'),
        ],
    ]" />

    <x-ashop-ashop:product-nav :product="$product" />
    <form action="{{ route('admin.shop.products.update', [$product]) }}" method="POST" enctype="multipart/form-data"
        class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="mr-2" id="preview-img">
                        <img src="{{ $product->image_sm() }}" alt="" style="max-height: 70px;">
                    </div>
                    <div class="form-group flex-fill">
                        <label for="">Product Image</label>
                        <input type="file" name="image" class="form-control" id="crop-image">
                        <div class="small text-info">
                            <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                            <div><b>*</b> Image should be in
                                {{ config('shopze.products.images.width') . ' x ' . config('shopze.products.images.height') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" required class="form-control" value="{{ $product->name }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="">Product Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="{{ $product->subtitle }}">
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Brand Name </label>
                        <select name="brand_id" class="form-control">
                            <option value="">-- Select Brand --</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">SKU Code</label>
                        <input type="text" name="sku" class="form-control" value="{{ $product->sku }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock" required class="form-control"
                            value="{{ $product->stock }}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Price (MRP)<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-rupee-sign"></i>
                            </span>
                            <input type="number" name="net_price" required class="form-control"
                                value="{{ $product->net_price }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Sale Price<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-rupee-sign"></i>
                            </span>
                            <input type="number" name="sell_price" class="form-control"
                                value="{{ $product->sell_price }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Deal Price</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-rupee-sign"></i>
                            </span>
                            <input type="number" name="deal_price" class="form-control"
                                value="{{ $product->deal_price }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Deal Expiry</label>
                        <input type="datetime-local" name="deal_expiry" class="form-control"
                            value="{{ $product->deal_expiry }}" min="{{ date('Y-m-d') . 'T' . date('H:i') }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ $product->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$product->status ? 'selected' : '' }}>In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Featured <span class="text-danger">*</span></label>
                        <select name="featured" class="form-control" required>
                            <option value="1" {{ $product->featured ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$product->featured ? 'selected' : '' }}>In-Active</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Categories <span class="text-danger">*</span> </label>
                <select name="categories[]" id="categories" class="form-control" multiple="multiple"
                    required="">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->categories->pluck('id')->contains($category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                            {{ $category->parent ? ' (' . $category->parent->name . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">
                    Product Info
                    <span class="small">(Short Description)</span>
                </label>
                <textarea name="info" rows="3" class="form-control">{{ $product->info }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-lg rounded-sm btn-dark px-4">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </form>

    <x-slot name="script">
        <script>
            var imageRatio = {{ config('shopze.images.products.width') . '/' . config('shopze.images.products.height') }};
            var previewImg = {
                targetId: 'preview-img',
                width: '50px',
                rounded: '4px'
            };
            imageCropper('crop-image', imageRatio, previewImg);
        </script>
    </x-slot>
</x-admin.layout>
