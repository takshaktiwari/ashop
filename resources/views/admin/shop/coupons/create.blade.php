<x-admin.layout>
    <x-admin.breadcrumb title='Create Coupon' :links="[
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'All Coupons', 'url' => route('admin.shop.coupons.index')],
        ['text' => 'Create Coupon'],
    ]" :actions="[['text' => 'All Coupons', 'url' => route('admin.shop.coupons.index'), 'icon' => 'fas fa-list']]" />

    <form method="POST" action="{{ route('admin.shop.coupons.store') }}" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class=col-md-4>
                    <div class="form-group">
                        <label for="">Coupon <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" required="" placeholder="Coupon"
                            value="{{ old('code') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Discount Type</label>
                        <select name="discount_type" class="form-control" id="discounType">
                            <option value="">-Select-</option>
                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}> Percent
                            </option>
                            <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}> Amount
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 block-percent">
                    <div class="form-group">
                        <label for="">Discount (%)</span></label>
                        <div class="input-group">
                            <input type="number" name="percent" id="d-percent" class="form-control"
                                placeholder="Discount in Percent" onfocus="getElementById('d-amount').value = null"
                                value="{{ old('percent') }}">
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
                            <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="number" name="max_discount" class="form-control" placeholder=" Flat Discount">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 block-amount">
                    <div class="form-group">
                        <label for="">Discount (Amt)</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="number" name="amount" id="d-amount" class="form-control"
                                placeholder=" Flat Discount" onfocus="getElementById('d-percent').value = null"
                                value="{{ old('amount') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}"
                            min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Minimum Purchase</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="number" name="min_purchase" class="form-control"
                                placeholder="Minimum Purchase" value="{{ old('min_purchase') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="1"> Active</option>
                            <option value="0"> Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Featured</label>
                        <select name="featured" class="form-control">
                            <option value="1"> Featured</option>
                            <option value="0"> Not-Featured</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Max. Usable</label>
                        <div class="d-flex">
                            <input type="number" name="max_usable" class="form-control rounded-0"
                                placeholder="eg. 10">
                            <div class="bg-light form-control rounded-0" style="max-width: 70px;">Times</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                    placeholder="Title" required>
            </div>
            <div class="form-group">
                <label for=""> Description <span class="text-danger">*</span></label>
                <textarea name="description" rows="4" class="form-control summernote-editor" placeholder="Your Description">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark px-3 btn-loader">Submit</button>
        </div>
    </form>

    @push('scripts')
        <script>
            var discounType = $("#discounType").val();
            if (discounType == 'percent') {
                $(".block-percent").slideDown('fast');
                $(".block-amount").slideUp('fast');
            } else {
                $(".block-amount").slideDown('fast');
                $(".block-percent").slideUp('fast');
            }

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
        </script>
    @endpush
</x-admin.layout>
