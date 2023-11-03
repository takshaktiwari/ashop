<x-admin.layout>
    <x-admin.breadcrumb title='Brands' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Brands']]" :actions="[['text' => 'Create New', 'icon' => 'fas fa-plus', 'url' => route('admin.shop.brands.create')]]" />

    <div class="card shadow-sm">
        <x-admin.paginator-info :items="$brands" class="card-header" />
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Added By</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img src="{{ $brand->image_sm() }}" class="rounded" height="50px" width="50px"></td>
                            <td>
                                {{ $brand->name }}

                                <span class="small d-block">{{ $brand->categories->pluck('name')->implode(', ') }}</span>
                            </td>
                            <td>
                                @if ($brand->user)
                                    <a href="{{ route('admin.users.show', [$brand]) }}">
                                        {{ $brand->user->name }}
                                    </a>
                                @endif
                                <span class="small d-block text-nowrap">{{ $brand->created_at->format('d-M-Y h:i A') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.shop.brands.status.toggle', [$brand]) }}"
                                    class="text-nowrap d-block {{ $brand->status ? 'font-weight-bold text-success' : 'text-danger' }}">
                                    {{ $brand->status ? 'Active' : 'In-Active' }}
                                </a>
                                <a href="{{ route('admin.shop.brands.featured.toggle', [$brand]) }}"
                                    class="text-nowrap d-block {{ $brand->featured ? 'font-weight-bold text-info' : 'text-dark' }}">
                                    {{ $brand->featured ? 'Featured' : 'Not-Featured' }}
                                </a>
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.shop.brands.edit', [$brand]) }}"
                                    class="load-circle btn btn-sm btn-success"><i class="fas fa-edit"></i></a>

                                <form action="{{ route('admin.shop.brands.destroy', [$brand]) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="load-circle btn btn-sm btn-danger delete-alert"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin.layout>
