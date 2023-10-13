<x-admin.layout>
    <x-admin.breadcrumb title='Attributes' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Attributes']]" :actions="[
        [
            'text' => 'Add Attribute',
            'icon' => 'fas fa-plus',
            'url' => route('admin.shop.attributes.create'),
            'class' => 'btn-success btn-loader',
        ],
    ]" />

    <div class="card shadow-sm">
        <x-admin.paginator-info :items="$attributes" class="card-header" />
        <div class="card-body">
            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Display Name</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($attributes as $attribute)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attribute->name }}</td>
                            <td>{{ $attribute->slug }}</td>
                            <td>{{ $attribute->display_name }}</td>
                            <td>
                                <a href="{{ route('admin.shop.attributes.show', [$attribute]) }}" class="btn btn-info btn-sm btn-loader load-circle">
                                    <i class="fas fa-info-circle"></i>
                                </a>

                                <a href="{{ route('admin.shop.attributes.edit', [$attribute]) }}" class="btn btn-success btn-sm btn-loader load-circle">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.shop.attributes.destroy', [$attribute]) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-alert btn-loader load-circle"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin.layout>
