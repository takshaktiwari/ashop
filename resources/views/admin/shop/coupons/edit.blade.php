<x-admin.layout>
    <x-slot name="style">
        <style>
            .tox-notifications-container {
                display: none;
            }
        </style>
    </x-slot>
    <x-admin.breadcrumb title='Edit Coupon' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'All Coupons', 'url' => route('admin.shop.coupons.index')],
        ['text' => 'Edit Coupon'],
    ]" :actions="[['text' => 'All Coupons', 'url' => route('admin.shop.coupons.index'), 'icon' => 'fas fa-list']]" />

    <form method="POST" action="{{ route('admin.shop.coupons.update', [$coupon]) }}" class="card shadow-sm">
        <div class="card-body">
            @csrf
            @method('PUT')
            <div class="row">
                <div class=col-md-4>
                    <div class="form-group">
                        <label for="">Coupon <span class="text-danger">*</span></label>
                        <input type="text" name="code" value="{{ $coupon->code }}" class="form-control"
                            required="" placeholder="Coupon">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Discount Type</label>
                        <select name="discount_type" class="form-control" id="discounType">
                            <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>
                                Percent</option>
                            <option value="amount" {{ $coupon->discount_type == 'amount' ? 'selected' : '' }}> Amount
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 block-percent">
                    <div class="form-group">
                        <label for="">Discount (%)</label>
                        <div class="input-group">
                            <input type="number" name="percent" id="d-percent" value="{{ $coupon->percent }}"
                                class="form-control" placeholder="Discount in Percent"
                                onfocus="getElementById('d-amount').value = null">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 block-percent">
                    <div class="form-group">
                        <label for="">Max. Discount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <input type="number" name="max_discount" class="form-control" placeholder=" Flat Discount"
                                value="{{ $coupon->max_discount }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 block-amount">
                    <div class="form-group">
                        <label for="">Discount (Amt)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <input type="number" name="amount" id="d-amount" value="{{ $coupon->amount }}"
                                class="form-control" placeholder=" Discount in Amount"
                                onfocus="getElementById('d-percent').value = null">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Expiry Date</label>
                        <input type="date" name="expires_at" value="{{ $coupon->expires_at->format('Y-m-d') }}"
                            class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Minimum Purchase</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <input type="number" name="min_purchase" value="{{ $coupon->min_purchase }}"
                                class="form-control" placeholder="Minimum Purchase">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $coupon->status == 1 ? 'selected' : '' }}> Active</option>
                            <option value="0" {{ $coupon->status == 0 ? 'selected' : '' }}> Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Featured</label>
                        <select name="featured" class="form-control">
                            <option value="1" {{ $coupon->featured == 1 ? 'selected' : '' }}> Featured</option>
                            <option value="0" {{ $coupon->featured == 0 ? 'selected' : '' }}> Not-Featured</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Max. Usable</label>
                        <div class="d-flex">
                            <input type="number" name="max_usable" class="form-control rounded-0"
                                placeholder="eg. 10" value="{{ $coupon->max_usable }}">
                            <div class="bg-light form-control rounded-0" style="max-width: 70px;">Times</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Title </label>
                <input type="text" name="title" class="form-control" value="{{ $coupon->title }}"
                    placeholder="Title">
            </div>
            <div class="form-group">
                <label for=""> Description <span class="text-danger">*</span></label>
                <textarea name="description" rows="4" id="" class="form-control text-editor"
                    placeholder="Your Description">{{ $coupon->description }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-dark px-3">
                Submit
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function($) {

                var discounType = $("#discounType").val();
                if (discounType == 'percent') {
                    $(".block-percent").slideDown('fast');
                    $(".block-amount").slideUp('fast');
                } else {
                    $(".block-amount").slideDown('fast');
                    $(".block-percent").slideUp('fast');
                }

                $("#couponType").change(function(event) {
                    var couponType = $(this).val();
                    if (couponType == 'category') {
                        $(".block-categories").slideDown('fast', function() {
                            $("#categories").select2();
                        });
                    } else {
                        $(".block-categories").slideUp('fast');
                    }
                });

                $("#discounType").change(function(event) {
                    var discounType = $(this).val();
                    if (discounType == 'percent') {
                        $(".block-percent").slideDown('fast');
                        $(".block-amount").slideUp('fast');
                    } else {
                        $(".block-amount").slideDown('fast');
                        $(".block-percent").slideUp('fast');
                    }
                });

            });
        </script>
    @endpush
</x-admin.layout>
