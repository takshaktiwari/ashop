<x-ashop-ashop:user-account>
    <x-slot:breadcrumb>
        <x-breadcrumb title="My Profile" :links="[['text' => 'Home', 'url' => url('/')], ['text' => 'Dashboard', 'url' => route('shop.user.dashboard')], ['text' => 'Profile']]" />
    </x-slot:breadcrumb>

    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-solid fa-user-edit me-2"></i> My Profile
        </h4>
    </x-slot:title>

    <div id="user_profile">
        <div class="row">
            <div class="col-md-8 mr-auto">
                <form action="{{ route('shop.user.profile.update') }}" method="POST" class="card" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex mb-3 gap-3">
                            <div class="m-auto">
                                <img src="{{ auth()->user()->profileImg() }}" alt="profile image" class="rounded" style="max-height: 65px">
                            </div>
                            <div class="flex-fill form-group ">
                                <label for="">Image</label>
                                <input type="file" name="profile_img" class="form-control" accept="image/*" />
                            </div>
                        </div>


                        <div class="form-group mb-3">
                            <label for="">Name*</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Email*</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Mobile*</label>
                            <input type="tel" name="mobile" class="form-control" value="{{ auth()->user()->mobile }}" required />
                        </div>
                        <div class="form-group ">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" />
                            <p class="mb-0 small">
                                <span class="small text-secondary">Enter password if you want to change, otherwise leave it blank.</span>
                            </p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-dark px-3">
                            <i class="fa-solid fa-save"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <x-ashop-ashop:user-bottom-nav />
    </div>
</x-ashop-ashop:user-account>
