<x-admin.layout>
    <x-admin.breadcrumb title='Searched Terms' :links="[['text' => 'Searched Terms']]" />

    <div class="card shadow-sm">
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
        <div class="card-footer">
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-admin.layout>
