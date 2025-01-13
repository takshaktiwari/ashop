<x-admin.layout>
    <x-admin.breadcrumb title='Orders' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Orders']]" :actions="[
        [
            'text' => 'Dashboard',
            'url' => route('admin.dashboard'),
            'class' => 'btn-dark btn-loader',
        ],
    ]" />

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
