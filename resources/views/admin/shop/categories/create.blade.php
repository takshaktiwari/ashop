<x-admin.layout>
    <x-admin.breadcrumb title='Dashboard' :links="[['text' => 'Dashboard']]" :actions="[
        [
            'text' => 'All Categories',
            'icon' => 'fas fa-list',
            'url' => route('admin.shop.categories.index'),
            'class' => 'btn-success',
        ],
    ]" />

    <form action="{{ route('admin.shop.categories.store') }}" method="POST" enctype="multipart/form-data"
        class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Select Image <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div id="image-thumb"></div>
                            <input type="file" name="image_file" required="" class="form-control" id="crop-image">
                        </div>

                        <div class="small text-info">
                            <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                            <div><b>*</b> Image should be in ratio of
                                {{ config('ashop.categories.images.width', 800) . ' x ' . config('shopze.categories.images.height', 900) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Category <span class="text-danger">*</span></label>
                        <input type="text" name="name" required="" class="form-control"
                            placeholder="Category name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" required="" class="form-control"
                            placeholder="Category name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Parent Category </label>
                        <select name="category_id" class="form-control select2">
                            <option value="">-- Select --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                    @if ($category->parent)
                                        {{ ' < ' . $category->parent->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Featured <span class="text-danger">*</span></label>
                        <select name="featured" class="form-control" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="">Is Top <span class="text-danger">*</span></label>
                        <select name="is_top" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Description</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-4">Submit</button>
        </div>
    </form>

    @push('scripts')
        <script>
            var imageRatio = 8/9;
            var previewImg = {
                targetId: 'image-thumb',
                width: '50px',
                rounded: '4px'
            };
            imageCropper('crop-image', imageRatio, previewImg);
        </script>
    @endpush
</x-admin.layout>
