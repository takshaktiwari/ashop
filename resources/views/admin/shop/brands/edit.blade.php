<x-admin.layout>
    <x-admin.breadcrumb title='Edit Brands' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Brands']]" :actions="[
        ['text' => 'All Brands', 'icon' => 'fas fa-list', 'url' => route('admin.shop.brands.index')],
        ['text' => 'Create New', 'icon' => 'fas fa-plus', 'url' => route('admin.shop.brands.create')],
    ]" />

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('admin.shop.brands.update', [$brand]) }}" method="POST" enctype="multipart/form-data"
                class="card shadow-sm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-2" id="preview-img">
                            <img src="{{ $brand->image_sm() }}" height="70" class="rounded">
                        </div>
                        <div class="flex-fill">
                            <div class="form-group">
                                <label for="">Image</label>
                                <input type="file" name="image" class="form-control" id="crop-image">
                                <div class="small text-info">
                                    <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                                    <div><b>*</b> Image should be in
                                        {{ config('ashop.brands.images.width', 800) . ' x ' . config('shopze.brands.images.height', 900) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Brand Name </label>
                        <input type="text" name="brand" value="{{ $brand->name }}" class="form-control"
                            placeholder="Brand name">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label for="">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="1" @selected($brand->status)>Active</option>
                                    <option value="0" @selected(!$brand->status)>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label for="">Featured <span class="text-danger">*</span></label>
                                <select name="featured" class="form-control" required>
                                    <option value="1" @selected($brand->featured)>Featured</option>
                                    <option value="0" @selected(!$brand->featured)>Not Featured</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark px-3 btn-loader">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="script">
        <script>
            var imageRatio = 8 / 9;
            var previewImg = {
                targetId: 'preview-img',
                width: '75px',
                rounded: '4px'
            }
            imageCropper('crop-image', imageRatio, previewImg);
        </script>
    </x-slot>
</x-admin.layout>
