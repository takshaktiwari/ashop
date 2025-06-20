<x-app-layout>
    {!! isset($breadcrumb) ? $breadcrumb : '' !!}

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container user_account">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card user_nav mb-4">
                        <div class="card-header py-2 d-flex justify-content-between">
                            <h5 class="my-auto d-none d-lg-block py-2">My Account</h5>
                            <h5 class="my-auto d-block d-lg-none">{{ auth()->user()->name }}</h5>
                            <button class="btn btn-secondary d-block d-lg-none" data-bs-toggle="collapse"
                                data-bs-target=".nav-block">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                        </div>
                        <div class="card-body text-center py-4 nav-block d-lg-block">
                            <img src="{{ auth()->user()->profileImg() }}" alt="user"
                                class="rounded-circle img-thumbnail" style="max-width: 150px; width: 100%;">
                            <h5 class="fw-bold mt-3">{{ auth()->user()->name }}</h5>
                            <p class="mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <ul class="list-group list-group-flush nav-block d-lg-block">
                            <a href="{{ route('shop.user.dashboard') }}" class="list-group-item">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                            <a href="{{ route('shop.user.addresses.index') }}" class="list-group-item">
                                <i class="fa-regular fa-address-book"></i> Address Book
                            </a>
                            <a href="{{ route('shop.user.orders.index') }}" class="list-group-item">
                                <i class="fa-solid fa-box"></i> All Orders
                            </a>
                            @if (config('ashop.features.favorites.status', true))
                                <a href="{{ route('shop.user.wishlist.items.index') }}" class="list-group-item">
                                    <i class="fa-solid fa-heart"></i> Wishlist Items
                                </a>
                            @endif
                            <a href="{{ route('shop.user.profile') }}" class="list-group-item">
                                <i class="fa-solid fa-user-edit"></i> My Profile
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="fa-solid fa-power-off"></i> Logout
                            </a>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between">
                        {{ $title }}
                    </div>
                    <hr />

                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
