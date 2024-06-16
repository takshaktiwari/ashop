<div class="d-flex flex-wrap gap-3 mt-4">
    <div class="card flex-fill">
        <a href="{{ route('shop.user.addresses.index') }}" class="card-body text-center">
            <span class="fs-3 d-block">
                <i class="fa-regular fa-address-book"></i>
            </span>
            <h6 class="text-nowrap">Address Book</h6>
        </a>
    </div>
    <div class="card flex-fill">
        <a href="{{ route('shop.user.orders.index') }}" class="card-body text-center">
            <span class="fs-3 d-block">
                <i class="fa-solid fa-box"></i>
            </span>
            <h6 class="text-nowrap">All Orders</h6>
        </a>
    </div>
    <div class="card flex-fill">
        <a href="{{ route('shop.user.wishlist.items.index') }}" class="card-body text-center">
            <span class="fs-3 d-block">
                <i class="fa-regular fa-heart"></i>
            </span>
            <h6 class="text-nowrap">Your Wishlist</h6>
        </a>
    </div>
    <div class="card flex-fill">
        <a href="{{ route('shop.user.profile') }}" class="card-body text-center">
            <span class="fs-3 d-block">
                <i class="fa-solid fa-user-edit"></i>
            </span>
            <h6 class="text-nowrap">My Profile</h6>
        </a>
    </div>
</div>
