<x-admin.layout>
    <x-admin.breadcrumb title='Variations' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Variations']]" :actions="[
        [
            'text' => 'Add Variation',
            'icon' => 'fas fa-plus',
            'url' => route('admin.shop.variations.create'),
            'class' => 'btn-success btn-loader',
        ],
    ]" />

    <div class="card shadow-sm">
        <x-admin.paginator-info :items="$variations" class="card-header" />
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
                    @foreach ($variations as $variation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $variation->name }}
                                <span class="badge bg-success">{{ $variation->variants_count }}</span>
                            </td>
                            <td>{{ $variation->slug }}</td>
                            <td>{{ $variation->display_name }}</td>
                            <td>
                                <a href="{{ route('admin.shop.variations.show', [$variation]) }}" class="btn btn-info btn-sm btn-loader load-circle">
                                    <i class="fas fa-info-circle"></i>
                                </a>

                                <a href="{{ route('admin.shop.variations.edit', [$variation]) }}" class="btn btn-success btn-sm btn-loader load-circle">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.shop.variations.destroy', [$variation]) }}" method="POST" class="d-inline-block">
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
