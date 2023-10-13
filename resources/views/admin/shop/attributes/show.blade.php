<x-admin.layout>
    <x-admin.breadcrumb title='Attributes' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Attributes']]" :actions="[
        [
            'text' => 'Add Attribute',
            'icon' => 'fas fa-plus',
            'url' => route('admin.shop.attributes.create'),
            'class' => 'btn-success btn-loader',
        ],
        [
            'text' => 'All Attributes',
            'icon' => 'fas fa-list',
            'url' => route('admin.shop.attributes.index'),
            'class' => 'btn-dark btn-loader',
        ],
    ]" />

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $attribute->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug</th>
                                <td>{{ $attribute->slug }}</td>
                            </tr>
                            <tr>
                                <th>Display Name</th>
                                <td>{{ $attribute->display_name }}</td>
                            </tr>
                            <tr>
                                <th>Options</th>
                                <td>{{ $attribute->options->implode(', ') }}</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>{{ $attribute->remarks }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.shop.attributes.edit', [$attribute]) }}" class="btn btn-success btn-loader load-circle">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('admin.shop.attributes.destroy', [$attribute]) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger delete-alert btn-loader load-circle">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>
