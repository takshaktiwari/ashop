<x-admin.layout>
    <x-admin.breadcrumb title='Dashboard' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Categories', 'url' => route('admin.shop.categories.index')],
        ['text' => 'Edit'],
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
    <form action="{{ route('admin.shop.categories.update', $category) }}" method="POST" enctype="multipart/form-data"
        class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="d-flex">
                            <div class="mr-3" id="image-thumb">
                                <img src="{{ $category->image_sm() }}" alt="" style="max-height: 70px;">
                            </div>
                            <div class="flex-fill">
                                <label for="">Select Image </label>
                                <input type="file" name="image_file" class="form-control" id="crop-image">
                            </div>
                        </div>
                        <div class="small text-secondary">
                            <div><b>*</b> Image format should be 'jpg' or 'png'</div>
                            <div><b>*</b> Image should be in ratio of 4:5, eg:
                                {{ config('ashop.categories.images.width', 800) . ' x ' . config('shopze.categories.images.height', 900) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Category <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="category" required="" class="form-control"
                            placeholder="Category name" value="{{ $category->name }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" required="" class="form-control"
                            placeholder="Category name"
                            value="{{ $category->display_name ? $category->display_name : $category->category }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Parent Category </label>
                        <select name="category_id" class="form-control select2">
                            <option value="">-- Select --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $cat->id == $category->parent_category?->id ? 'selected' : '' }}>
                                    {{ $cat->name }}

                                    @if ($cat->parent_category)
                                        {{ ' < ' . $cat->parent_category->name }}
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
                            <option value="0" {{ $category->featured == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $category->featured == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label for="">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ $category->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $category->status == '0' ? 'selected' : '' }}>In-Active
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="">Is Top <span class="text-danger">*</span></label>
                        <select name="is_top" class="form-control" required>
                            <option value="1" {{ $category->is_top == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $category->is_top == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ $category->description }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-4">
                <i class="fas fa-save"></i> Submit
            </button>
        </div>
    </form>

    <x-slot name="script">
        <script>
            var imageRatio = {{ config('shopze.images.categories.width') . '/' . config('shopze.images.categories.height') }};
            var previewImg = {
                targetId: 'image-thumb',
                width: '70px',
                rounded: '4px',
            }
            imageCropper('crop-image', imageRatio, previewImg);
        </script>
    </x-slot>
</x-admin.layout>
