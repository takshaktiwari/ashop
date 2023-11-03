<x-admin.layout>
    <x-admin.breadcrumb title='Create Brand' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Brands']]" :actions="[['text' => 'All Brands', 'icon' => 'fas fa-list', 'url' => route('admin.shop.brands.index')]]" />

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('admin.shop.brands.store') }}" method="POST" enctype="multipart/form-data"
                class="card shadow-sm">
                <div class="card-body">
                    @csrf
                    <div class="form-group" id="variants">
                        <div class="d-flex">
                            <div class="mr-2" id="preview-img"></div>
                            <div class="flex-fill">
                                <label for="">Image</label>
                                <input type="file" name="image" class="form-control mb-2" id="crop-image">
                            </div>
                        </div>
                        <div class="small text-info">
                            <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                            <div><b>*</b> Image should be in {{ config('ashop.brands.images.width', 800) . ' x ' . config('shopze.brands.images.height', 900) }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" placeholder="Brand name"
                            required="">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Featured <span class="text-danger">*</span></label>
                                <select name="featured" class="form-control" required>
                                    <option value="1">Featured</option>
                                    <option value="0">Not Featured</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-dark px-3">
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            var imageRatio = 8/9;
            var previewImg = {
                targetId: 'preview-img',
                width: '70px',
                rounded: '4px'
            }
            imageCropper('crop-image', imageRatio, previewImg);
        </script>
    @endpush
</x-admin.layout>
