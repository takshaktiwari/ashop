<x-ashop-ashop:user-account>
    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-regular fa-address-book me-2"></i> New Address
        </h4>
        <a href="{{ route('shop.user.addresses.create') }}" class="btn btn-sm btn-primary px-3">
            <i class="fa-solid fa-list"></i> All Addresses
        </a>
    </x-slot:title>

    <div id="user_addresses">
        <form action="{{ route('shop.user.addresses.store') }}" method="POST" class="card">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="">Full name (First and Last name)*</label>
                        <input type="text" name="name" class="form-control new_addr_input"
                            placeholder="Your full name" value="{{ old('name', auth()->user()?->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="">Mobile number*</label>
                        <input type="tel" name="mobile" class="form-control new_addr_input"
                            placeholder="Your contact no." value="{{ old('mobile', auth()->user()?->mobile) }}"
                            required>
                    </div>
                    <div class="col-md-12">
                        <label for="">Flat, House no., Building, Company, Apartment*</label>
                        <input type="text" name="address_line_1" class="form-control new_addr_input"
                            placeholder="Your full address" required />
                    </div>
                    <div class="col-md-12">
                        <label for="">Area, Street, Sector, Village*</label>
                        <input type="text" name="address_line_2" class="form-control new_addr_input"
                            placeholder="Your full address" required />
                    </div>
                    <div class="col-md-12">
                        <label for="">Landmark*</label>
                        <input type="text" name="landmark" class="form-control new_addr_input"
                            placeholder="Near by landmark" />
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="">Town/City*</label>
                        <input type="text" name="city" class="form-control new_addr_input" required>
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="">Pincode*</label>
                        <input type="number" name="pincode" class="form-control new_addr_input" required>
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="">State*</label>
                        <input type="text" name="state" class="form-control new_addr_input" required>
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="">Country/Region*</label>
                        <input type="text" name="country" class="form-control new_addr_input" required>
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="">Default Shipping Address*</label>
                        <select name="default_addr" id="default_addr" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="">Default Billing Address*</label>
                        <select name="billing_addr" id="billing_addr" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-dark px-3">
                    <i class="fa-solid fa-save"></i> Submit
                </button>
            </div>
        </form>
    </div>
</x-ashop-ashop:user-account>
