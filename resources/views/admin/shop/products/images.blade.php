<x-admin.layout>

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
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{ route('admin.shop.products.images.store', [$product]) }}" method="POST"
                        enctype="multipart/form-data" class="border rounded my-4 p-md-4 p-3 color-box bg-light shadow-sm">
                        @csrf
                        <div class="form-group">
                            <label for="">Select Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="product_images[]" multiple=""
                                required="">
                            <div class="small text-info">
                                <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                                <div><b>*</b> Image should be in
                                    {{ config('ashop.products.images.width', 800) . ' x ' . config('ashop.products.images.height', 900) }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $product->name }}">
                        </div>
                        <button type="submit" class="btn rounded-sm btn-dark px-4" >
                            <i class="fas fa-save"></i> Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($product->images->count())
        <form method="POST" action="{{ route('admin.shop.products.images.bulk.destroy') }}" class="card shadow-sm">
            @csrf
            <div class="card-body">
                <div class="row">
                    @foreach ($product->images as $image)
                        <div class="col-xl-2 col-md-3 col-sm-4 col-6 mb-3">
                            <div class="border rounded" style="overflow: hidden;">
                                <div class="form-check-inline m-0">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="images[]"
                                            value="{{ $image->id }}" style="position: absolute;">
                                        <img src="{{ $image->image_sm() }}" class="w-100">
                                    </label>
                                </div>


                                @if (!$image->primary_img)
                                    <div class="d-flex">
                                        <a href="{{ route('admin.shop.products.images.destroy', [$image]) }}"
                                            class="btn btn-sm btn-danger delete-alert rounded-0 flex-fill">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete All
                </button>
            </div>
        </form>
    @endif

</x-admin.layout>
