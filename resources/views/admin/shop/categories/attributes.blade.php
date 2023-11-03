<x-admin.layout>
	<x-admin.breadcrumb
			title='Attributes'
			:links="[
				['text' => 'Dashboard', 'url' => route('admin.dashboard') ],
				['text' => 'Categories', 'url' => route('admin.shop.categories.index') ],
				['text' => 'Attributes' ],
			]"
			:actions="[
			    ['text' => 'Create New', 'icon' => 'fas fa-plus', 'url' => route('admin.shop.categories.create'), 'class' => 'btn-success'],
			    ['text' => 'All Categories', 'icon' => 'fas fa-list', 'url' => route('admin.shop.categories.index'), 'class' => 'btn-dark'],
			]"
		/>


    <x-ashop-ashop:inner-nav
        title="Edit"
        :links="[
            ['text' =>  'Info', 'url' => route('admin.shop.categories.edit', [$category])],

            ['text' =>  'Details', 'url' => route('admin.shop.categories.details', [$category])],

            ['text' => 'Brands', 'url' => route('admin.shop.categories.brands', [$category])],

            ['text' =>  'Attributes', 'url' => route('admin.shop.categories.attributes', [$category])],

            ['text' =>  'Vatiations', 'url' => route('admin.shop.categories.variations', [$category])],
        ]"
        />
    <form action="{{ route('admin.shop.categories.attributes.update', [$category]) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
		@csrf
		<div class="card-body">
            <table class="table table-bordered">
                @foreach($attributes as $attribute)
                    <tr>
                        <td>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="attributes[]" class="form-check-input" value="{{ $attribute->id }}" {{ $category->attributes?->pluck('id')?->contains($attribute->id) ? 'checked' : '' }}>
                                    {{ $attribute->name }}
                                </label>
                            </div>
                        </td>
                        <td>{{ $attribute->options->implode(', ') }}</td>
                    </tr>
                @endforeach
            </table>
		</div>
        <div class="card-footer">
		    <button type="submit" class="btn btn-dark px-4 btn-loader">Submit</button>
        </div>
	</form>
</x-admin.layout>
