<x-admin.layout>
    <x-admin.breadcrumb title='Variations' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Variations']]" :actions="[
        [
            'text' => 'Add Variation',
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

    <form method="POST" action="{{ route('admin.shop.variations.update', [$variation]) }}" class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $variation->name) }}" required />
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control"
                            value="{{ old('display_name', $variation->display_name) }}" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Remarks</label>
                <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ old('remarks', $variation->remarks) }}</textarea>
            </div>
        </div>
        <div class="card-body border-top" id="variants">
            <h4>
                Variants
                <a href="javascript:void(0)" class="badge bg-primary" id="add_variant">
                    <i class="fas fa-plus"></i> Add More
                </a>
            </h4>
            @foreach ($variation->variants as $variant)
                <div class="row mb-2" id="{{ $loop->first ? 'first_item' : '' }}">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <label for="">Name</label>
                                <input type="text" name="variants[name][]" class="form-control" value="{{ $variant->name }}">
                            </div>
                            <div class="col-6">
                                <label for="">Key</label>
                                <input type="text" name="variants[key][]" class="form-control" value="{{ $variant->key }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="">Remarks</label>
                        <input type="text" name="variants[remarks][]" class="form-control" value="{{ $variant->remarks }}">
                    </div>
                    <div class="col-12 text-end">
                        <a href="javascript:void(0)" class="text-danger remove_variant">
                            <i class="fas fa-times"></i> Remove
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-3">
                <i class="fas fa-save"></i> Submit
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#add_variant").click(function() {
                    var variantHtml = $('#first_item').html();
                    $("#variants").append(`<div class="row mb-2">${variantHtml}</div>`);
                });

                $("#variants").on('click', '.remove_variant', function() {
                    var itemId = $(this).parent().parent().attr('id');
                    if (itemId == 'first_item') {
                        alert('Cannot remove first element.');
                        return false;
                    } else {
                        $(this).parent().parent().remove();
                        return true;
                    }
                });
            });
        </script>
    @endpush
</x-admin.layout>
