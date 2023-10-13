<x-admin.layout>
    <x-admin.breadcrumb title='Attributes' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'Attributes']]" :actions="[
        [
            'text' => 'All Attributes',
            'icon' => 'fas fa-list',
            'url' => route('admin.shop.attributes.index'),
            'class' => 'btn-success btn-loader',
        ],
    ]" />

    <div class="row">
        <div class="col-md-6">
            <form method="POST" action="{{ route('admin.shop.attributes.store') }}" class="card shadow-sm">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Display Name <span class="text-danger">*</span></label>
                                <input type="text" name="display_name" class="form-control"
                                    value="{{ old('display_name') }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Options <span class="text-danger">*</span></label>
                        <select name="options[]" multiple data-role="tagsinput" id="options"
                            class="form-control no-select2" required>
                            <option value="">-- Select --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ old('remarks') }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark px-3">
                        <i class="fas fa-save"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <style>
            .bootstrap-tagsinput {
                display: flex!important;
                min-height: 36px;
                height: auto;
                flex-wrap: wrap;
                gap: 0.2rem;
            }
        </style>
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
            integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"
            integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function() {

            });
        </script>
    @endpush
</x-admin.layout>
