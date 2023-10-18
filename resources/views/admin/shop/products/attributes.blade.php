<x-admin.layout>
    <style>
        .tox-notifications-container{ display: none; }
    </style>

	<x-admin.breadcrumb
			title='Product Detail'
			:links="[
				['text' => 'Dashboard', 'url' => route('admin.dashboard') ],
				['text' => 'Products', 'url' => route('admin.shop.products.index')],
				['text' => 'Detail']
			]"
			:actions="[
                ['text' => 'Create New', 'icon' => 'fas fa-plus', 'class' => 'btn-success', 'url' => route('admin.shop.products.create') ],
				['text' => 'All Products', 'icon' => 'fas fa-list', 'url' => route('admin.shop.products.index') ],
			]"
		/>

    <x-ashop-ashop:product-nav :product="$product" />
	<form action="{{ route('admin.shop.products.attributes.update', [$product]) }}" method="POST" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <table class="table">
                <thead>
                	<th>#</th>
                	<th>Attributes</th>
                	<th>Options</th>
                </thead>
                <tbody>
                	@foreach($attributes as $attribute)
                		<tr>
                			<td>{{ $loop->iteration }}</td>
                			<td>{{ $attribute->name }}</td>
                			<td>
                				<select name="metas[{{ $attribute->name }}][]"  class="form-control" multiple="" >
                					@foreach($attribute->options as $option)
                						<option value="{{ $option }}" >{{ $option }}</option>
                					@endforeach
                				</select>
                			</td>
                		</tr>
                	@endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-3 btn-loader">Submit</button>
        </div>
    </form>

</x-admin.layout>
