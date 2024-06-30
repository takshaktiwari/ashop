<x-admin.layout>
    <x-admin.breadcrumb title='Wishlist Items' :links="[['text' => 'Wishlist']]" />

    <div class="card shadow-sm">
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
        <div class="card-footer">
        </div>
    </div>

    @push('scripts')
        @dataTableAssets

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $.fn.dataTable.ext.buttons.delete = {
                    name: 'delete',
                    className: 'buttons-add',
                    text: 'Delete',
                    action: function(e, dt, button, config) {

                        let selectedValues = [];
                        $('.selected_items:checked').each(function() {
                            selectedValues.push($(this).val());
                        });

                        let baseUrl = '{{ route("admin.shop.wishlist.destroy.checked") }}';
                        let params = selectedValues.map(value => `wishlist_ids[]=${value}`).join('&');
                        let fullUrl = `${baseUrl}?${params}`;

                        window.location.href = fullUrl;
                    }
                };
            });
        </script>
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

        <style>
            table.table.dataTable>tbody>tr.selected>* {
                box-shadow: unset;
                color: unset;
                border-color: black;
            }
        </style>
    @endpush
</x-admin.layout>
