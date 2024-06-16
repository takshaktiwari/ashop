<x-app-layout>
    <x-breadcrumb title="Checkout" :links="[
        ['text' => 'Shop', 'url' => route('shop.index')],
        ['text' => 'My Cart', 'url' => route('shop.carts.index')],
        ['text' => 'Checkout'],
    ]" />
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/ashop/style.css') }}">
    @endpush
    <section class="py-5">
        <div class="container shop_page">
            <div class="row g-4">
                <div class="col-md-6 mx-auto">
                    <form action="{{ route('shop.checkout.address') }}" method="POST">
                        @csrf
                        @guest
                            <div class="card shados-sm mb-4">
                                <div class="card-body">
                                    <h5 class="mb-1">
                                        Are an existing user? Please
                                        <a href="{{ route('login', ['refer' => ['refer_from' => route('dashboard')]]) }}">
                                            login to your account.
                                        </a>
                                    </h5>
                                    <p class="mb-0 small">Or you can proceed by filling your addess.</p>
                                </div>
                            </div>
                        @else
                            @foreach (auth()->user()->addresses as $address)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="form-check ">
                                            <label class="form-check-label d-block" for="address_{{ $address->id }}">
                                                <input type="radio" class="form-check-input"
                                                    id="address_{{ $address->id }}" name="address_id"
                                                    value="{{ $address->id }}" @checked($address->default)>
                                                <p class="fw-bold mb-0">
                                                    <span>{{ $address->name }},</span>
                                                    <span>{{ $address->mobile }}</span>
                                                </p>
                                                <p class="mb-0 small">
                                                    <span>{{ $address->address_line_1 }},</span>
                                                    <span>{{ $address->address_line_2 }}</span>
                                                    <span>{{ $address->landmark }},</span>
                                                </p>
                                                <p class="mb-0 small">
                                                    <span>{{ $address->city }},</span>
                                                    <span>{{ $address->state }}</span>,
                                                    <span>{{ $address->pincode }}</span>
                                                </p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-check ">
                                        <label class="form-check-label d-block" for="address_0">
                                            <input type="radio" class="form-check-input" id="address_0" name="address_id"
                                                value="0">
                                            <p class="fw-bold mb-0">
                                                Add new address
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endguest

                        <div class="card" id="new_address" style="display: {{ $newAddress ? 'block' : 'none' }}">
                            <div class="card-header">
                                <h6 class="my-1">Your delivery address</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="">Full name (First and Last name)*</label>
                                        <input type="text" name="name" class="form-control new_addr_input"
                                            placeholder="Your full name"
                                            value="{{ old('name', auth()->user()?->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Mobile number*</label>
                                        <input type="tel" name="mobile" class="form-control new_addr_input"
                                            placeholder="Your contact no."
                                            value="{{ old('mobile', auth()->user()?->mobile) }}" required>
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
                                        <label for="">Landmark</label>
                                        <input type="text" name="landmark" class="form-control new_addr_input"
                                            placeholder="Near by landmark" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Town/City*</label>
                                        <input type="text" name="city" class="form-control new_addr_input"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Pincode*</label>
                                        <input type="number" name="pincode" class="form-control new_addr_input"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">State*</label>
                                        <input type="text" name="state" class="form-control new_addr_input"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Country/Region*</label>
                                        <input type="text" name="country" class="form-control new_addr_input"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-dark px-3">
                                Proceed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                @if ($newAddress)
                    newAddressShow();
                @else
                    newAddressHide();
                @endif

                $("input[name='address_id']").change(function(e) {
                    var addressId = $("input[name='address_id']:checked").val();
                    if (addressId == '0') {
                        newAddressShow();
                    } else {
                        newAddressHide();
                    }
                });

                function newAddressHide() {
                    $("#new_address").slideUp();
                    $('#new_address .new_addr_input').each(function() {
                        if ($(this).attr('required')) {
                            $(this).attr('data-required', 'required');
                            $(this).removeAttr('required');;
                        }
                    });
                }

                function newAddressShow() {
                    $("#new_address").slideDown();
                    $('#new_address .new_addr_input').each(function() {
                        if ($(this).attr('data-required') == 'required') {
                            $(this).removeAttr('data-required');
                            $(this).attr('required', '');
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
