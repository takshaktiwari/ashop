<x-admin.layout>
    <x-admin.breadcrumb title='Variations' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Variations']]" :actions="[
        [
            'text' => 'Add variation',
            'icon' => 'fas fa-plus',
            'url' => route('admin.shop.variations.create'),
            'class' => 'btn-success btn-loader',
        ],
        [
            'text' => 'All Variations',
            'icon' => 'fas fa-list',
            'url' => route('admin.shop.variations.index'),
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
                                <td>{{ $variation->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug</th>
                                <td>{{ $variation->slug }}</td>
                            </tr>
                            <tr>
                                <th>Display Name</th>
                                <td>{{ $variation->display_name }}</td>
                            </tr>
                            <tr>
                                <th>Variants</th>
                                <td>{{ $variation->variants->pluck('name')->implode(', ') }}</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>{{ $variation->remarks }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.shop.variations.edit', [$variation]) }}"
                        class="btn btn-success btn-loader load-circle">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('admin.shop.variations.destroy', [$variation]) }}" method="POST"
                        class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger delete-alert btn-loader load-circle">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body list-group">
                    @foreach ($variation->variants as $variant)
                        <li class="list-group-item d-flex justify-content-between">
                            <div class="my-auto">
                                <b>{{ $variant->name }}</b>
                                @if ($variant->key)
                                    <span class="ms-2">({{ $variant->key }})</span>
                                @endif
                                @if ($variant->remarks)
                                    <span class="small d-block">
                                        {{ $variant->remarks }}
                                    </span>
                                @endif
                            </div>
                            <div class="my-auto">
                                <a href="{{ route('admin.shop.variants.delete', [$variant]) }}" class="btn btn-sm btn-danger delete-alert">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>
