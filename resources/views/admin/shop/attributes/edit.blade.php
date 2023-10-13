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
            <form method="POST" action="{{ route('admin.shop.attributes.update', [$attribute]) }}" class="card shadow-sm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $attribute->name) }}" required />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Display Name <span class="text-danger">*</span></label>
                                <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $attribute->display_name) }}"
                                    required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Options <span class="text-danger">*</span></label>
                        <select name="options[]" multiple data-role="tagsinput" id="options"
                            class="form-control no-select2" required>
                            <option value="">-- Select --</option>
                            @foreach ($attribute->options as $option)
                                <option value="{{ $option }}">
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ old('remarks', $attribute->remarks) }}</textarea>
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

</x-admin.layout>
