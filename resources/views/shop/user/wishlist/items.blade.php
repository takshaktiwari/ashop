<x-ashop-ashop:user-account>
    <x-slot:breadcrumb>
        <x-breadcrumb title="Wishlist" :links="[['text' => 'Home', 'url' => url('/')], ['text' => 'Dashboard', 'url' => route('shop.user.dashboard')], ['text' => 'Wishlist']]" />
    </x-slot:breadcrumb>

    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-solid fa-heart me-2"></i> Wishlist Items
        </h4>
    </x-slot:title>

    <div id="wislist_items">
        <div class="card">
            <div class="card-body">
                <table class="table table-stripped mb-0">
                    <thead>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @if(!auth()->user()->wishlistItems->count())
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <h4 class="text-secondary">
                                    No items in your wislist
                                </h4>
                                <a href="{{ route('shop.index') }}" class="btn btn-dark px-3">
                                    See Products
                                </a>
                            </td>
                        </tr>
                        @endif
                        @foreach (auth()->user()->wishlistItems as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $product->image('sm') }}" alt="product image" class="rounded"
                                        style="max-height: 60px">
                                </td>
                                <td>
                                    <p class="mb-1">{{ $product->name }}</p>
                                    <p class="small mb-0">
                                        <del class="text-secondary me-1">
                                            {{ config('ashop.currency.sign', '₹') . $product->net_price }}
                                        </del>
                                        <span class="fw-bold">
                                            {{ config('ashop.currency.sign', '₹') . $product->price }}
                                        </span>
                                    </p>
                                </td>
                                <td>
                                    <a href="{{ route('shop.user.wishlist.items.toggle', [$product]) }}"
                                        class="btn btn-sm rounded-pill fs-4 text-danger">
                                        <i class="fas fa-heart"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <x-ashop-ashop:user-bottom-nav />
    </div>
</x-ashop-ashop:user-account>
