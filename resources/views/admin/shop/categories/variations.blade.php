<x-admin.layout>
	<x-admin.breadcrumb
			title='Variations'
			:links="[
				['text' => 'Dashboard', 'url' => route('admin.dashboard') ],
				['text' => 'Categories', 'url' => route('admin.shop.categories.index') ],
				['text' => 'Variations' ],
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
    <form action="{{ route('admin.shop.categories.variations.update', [$category]) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
		@csrf
		<div class="card-body">
            <table class="table table-bordered">
                @foreach($variations as $variation)
                    <tr>
                        <td>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="variations[]" class="form-check-input" value="{{ $variation->id }}" {{ $category->variations?->pluck('id')?->contains($variation->id) ? 'checked' : '' }}>
                                    {{ $variation->name }}
                                </label>
                            </div>
                        </td>
                        <td>{{ $variation->variants->pluck('name')->implode(', ') }}</td>
                    </tr>
                @endforeach
            </table>
		</div>
        <div class="card-footer">
		    <button type="submit" class="btn btn-dark px-4 btn-loader">Submit</button>
        </div>
	</form>
</x-admin.layout>
