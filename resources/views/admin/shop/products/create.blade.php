<x-admin.layout>
	<x-admin.breadcrumb
			title='Create Product'
			:links="[
				['text' => 'Dashboard', 'url' => route('admin.dashboard') ],
				['text' => 'Products', 'url' => route('admin.shop.products.index')],
				['text' => 'Create']
			]"
			:actions="[
				['text' => 'All Products', 'icon' => 'fas fa-list', 'class' => 'btn-success', 'url' => route('admin.shop.products.index') ],
			]"
		/>

	<form action="{{ route('admin.shop.products.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
		<div class="card-body">
			@csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="d-flex">
                        	<div class="mr-2" id="preview-img"></div>
                        	<div class="flex-fill">
                                <label for="">Product Image <span class="text-danger">*</span></label>
                        		<input type="file" name="image" required class="form-control" id="crop-image">
                        	</div>
                        </div>
                        <div class="small text-info">
                            <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                            <div><b>*</b> Image should be in {{ config('ashop.images.products.width', 800).' x '.config('ashop.images.products.height', 900) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" required class="form-control" value="{{ old('name') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="">Product Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Brand Name </label>
                        <select name="brand_id" class="form-control">
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ (old('brand_id') == $brand->id) ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">SKU Code</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock" required class="form-control" value="{{ old('stock', 100) }}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Price (MRP)<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-rupee-sign"></i>
                            </span>
                            <input type="number" name="net_price" required class="form-control" value="{{ old('net_price') }}">
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
                            <input type="number" name="sell_price" class="form-control" value="{{ old('sell_price') }}">
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
                            <input type="number" name="deal_price" class="form-control" value="{{ old('deal_price') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Deal Expiry</label>
                        <input type="datetime-local" name="deal_expiry" class="form-control" value="{{ old('deal_expiry') }}" min="{{ date('Y-m-d').'T'.date('H:i') }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ (old('status') == '1') ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (old('status') == '0') ? 'selected' : '' }}>In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="">Featured <span class="text-danger">*</span></label>
                        <select name="featured" class="form-control" required>
                            <option value="0" {{ (old('featured') == '0') ? 'selected' : '' }}>Not Featured</option>
                            <option value="1" {{ (old('featured') == '1') ? 'selected' : '' }}>Featured</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Categories <span class="text-danger">*</span> </label>
                <select name="categories[]" id="categories" class="form-control" multiple="multiple" required="">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                            {{ $category->parent ? ' ('.$category->parent->name.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">
                    Product Info
                    <span class="small">(Short Description)</span>
                </label>
                <textarea name="info" rows="3" class="form-control"></textarea>
            </div>
	   </div>
       <div class="card-footer">
            <button type="submit" class="btn btn-lg rounded-sm btn-dark px-5 btn-loader">Create Product</button>
       </div>
	</form>

    <x-slot name="script">
        <script>
            var imageRatio = {{ config('ashop.images.products.width', 800).'/'.config('ashop.images.products.height', 900) }};
            var previewImg = {
            	targetId: 'preview-img',
            	width: '55px',
            	rounded: '4px'
            };
            imageCropper('crop-image', imageRatio, previewImg);
        </script>
    </x-slot>
</x-admin.layout>
