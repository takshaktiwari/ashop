<x-admin.layout>
    <x-admin.breadcrumb title='Dashboard' :links="[['text' => 'Categories']]" :actions="[
        [
            'text' => 'Filter',
            'icon' => 'fas fa-filter',
            'class' => 'btn-secondary',
            'data' => ['toggle' => 'collapse', 'target' => '#filter'],
        ],
        [
            'text' => 'Create New',
            'icon' => 'fas fa-plus',
            'url' => route('admin.shop.categories.create'),
            'class' => 'btn-success',
        ],
    ]" />

    <div class="card collapse" id="filter">
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <input type="text" name="search" class="form-control mb-sm-0 mb-2" placeholder="Search"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="parent" id="" class="form-control mb-sm-0 mb-2 select2">
                            <option value="">-- Parent Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('parent') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="status" id="" class="form-control mb-sm-0 mb-2">
                            <option value="">-- Select --</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>In-Active
                            </option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="featured" id="" class="form-control mb-sm-0 mb-2">
                            <option value="">-- Select --</option>
                            <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured
                            </option>
                            <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>
                                Not-Featured</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-success px-3 btn-loader">Submit</button>
                        <a href="{{ route('admin.shop.categories.index') }}" class="btn btn-basic border">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <x-admin.paginator-info :items="$categories" class="card-header" />
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Parent</th>
                        <th>Status</th>
                        <th>Other</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                <img src="{{ $category->image_sm() }}" alt="" style="max-height: 60px;"
                                    class="rounded">
                            </td>
                            <td>
                                @if ($category->children_count)
                                    <a href="{{ route('admin.shop.categories.index', ['category_id' => $category->id]) }}">
                                        {{ $category->name }}
                                        <span class="badge bg-success">{{ $category->children_count }}</span>
                                    </a>
                                @else
                                    {{ $category->name }}
                                @endif
                                <span class="badge badge-dark">{{ $category->display_name }}</span>

                                <div class="small text-danger">
                                    {{ $category->created_at->format('d-M-Y h:i A') }}
                                </div>
                            </td>
                            <td>
                                @isset($category->parent)
                                    <a
                                        href="{{ route('admin.shop.categories.index', ['category_id' => $category->parent]) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @endisset
                            </td>
                            <td>
                                <a href="{{ route('admin.shop.categories.featured', [$category]) }}"
                                    class="d-block font-weight-bold">
                                    {{ $category->featured ? 'Featured' : 'Not-Featured' }}
                                </a>

                                <a href="{{ route('admin.shop.categories.status', [$category]) }}"
                                    class="font-weight-bold">
                                    {{ $category->status ? 'Active' : 'In-Active' }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.shop.categories.is_top', [$category]) }}"
                                    class="font-weight-bold">
                                    {{ $category->is_top ? 'Is Top' : '- - -' }}
                                </a>
                            </td>
                            <td class="font-size-20">
                                <a href="{{ route('admin.shop.categories.edit', [$category]) }}"
                                    class="load-circle btn btn-sm btn-success" title="Edit this">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('admin.shop.categories.destroy', [$category]) }}"
                                    class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    </div>


</x-admin.layout>
