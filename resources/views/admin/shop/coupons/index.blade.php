<x-admin.layout>
    <x-admin.breadcrumb title='All Coupons' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'All Coupons']]" :actions="[['text' => 'New Coupon', 'url' => route('admin.shop.coupons.create'), 'icon' => 'fas fa-plus']]" />

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            {!! $dataTable->table() !!}
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
</x-admin.layout>
