<x-admin.layout>
    <x-admin.breadcrumb title='Brands' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Categories', 'url' => route('admin.shop.categories.index')],
        ['text' => 'Brands'],
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
    <form action="{{ route('admin.shop.categories.brands.update', [$category]) }}" method="POST"
        enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row">
                @foreach ($brands as $brand)
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" name="brands[]" class="form-check-input"
                                    value="{{ $brand->id }}"
                                    {{ $category->brands?->pluck('id')?->contains($brand->id) ? 'checked' : '' }}>
                                {{ $brand->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-4 btn-loader">Submit</button>
        </div>
    </form>
</x-admin.layout>
