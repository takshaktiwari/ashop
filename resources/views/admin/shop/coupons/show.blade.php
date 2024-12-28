<x-admin.layout>
	<x-admin.breadcrumb
		title='Coupon Details'
		:links="[
			['text' => 'Dashboard', 'url' => route('admin.dashboard')],
			['text' => 'All Coupons', 'url' => route('admin.shop.coupons.index')],
            ['text' => 'All Coupons'],
		]"
		:actions="[
			['text' => 'All Coupon', 'url' => route('admin.shop.coupons.index'), 'icon' => 'fas fa-list', 'class' => 'btn btn-success btn-loader' ],
            ['text' => 'New Coupon', 'url' => route('admin.shop.coupons.create'), 'icon' => 'fas fa-plus' ]
		]"
		/>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><b>Coupon</b></td>
                            <td>{{ $coupon->code }}</td>
                        </tr>
                        <tr>
                            <td><b>Discount Type</b></td>
                            <td>{{ ucfirst($coupon->discount_type) }}</td>
                        </tr>

                        @if($coupon->discount_type == 'percent')
                            <tr>
                                <td><b>Percent</b></td>
                                <td>{{ $coupon->formattedPercent() }}</td>
                            </tr>
                        @endif
                        @if($coupon->discount_type == 'amount')
                            <tr>
                                <td><b>Amount</b></td>
                                <td>{{ $coupon->formattedAmount() }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td><b>Min Purchase</b></td>
                            <td>{{ $coupon->formattedMinPurchase() }}</td>
                        </tr>
                        <tr>
                            <td><b>Max Discount</b></td>
                            <td>{{ $coupon->formattedMaxDiscount() }}</td>
                        </tr>
                        @if($coupon->expires_at)
                        <tr>
                            <td><b>Expires At</b></td>
                            <td>{{ $coupon->expires_at->format('d-M-Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><b>Status</b></td>
                            <td>{{ $coupon->status ? 'Active' : 'In-Active' }}</td>
                        </tr>
                        <tr>
                            <td><b>Featrued</b></td>
                            <td>{{ $coupon->featured ? 'Featrued' : 'Not-Featrued' }}</td>
                        </tr>
                        <tr>
                            <td><b>Created At</b></td>
                            <td>{{ $coupon->created_at->format('d-M-Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td><b>Disposable</b></td>
                            <td>{{ $coupon->disposable ? 'Yes' : 'No' }}</td>
                        </tr>
                        @if($coupon->disposable)
                            <tr>
                                <td><b>Max Usable</b></td>
                                <td>{{ $coupon->max_usable }} Times</td>
                            </tr>
                        @endif
                        <tr>
                            <td><b>Times Used</b></td>
                            <td>{{ (int)$coupon->times_used }} Times</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0">
                        <tr>
                            <td><b>Title</b></td>
                            <td>{{ $coupon->title }}</td>
                        </tr>
                        <tr>
                            <td><b>Description</b></td>
                            <td>{!! $coupon->description !!}</td>
                        </tr>

                        <td>
                            <td colspan="2">
                                @can('coupons_update')
                                <a href="{{ route('admin.shop.coupons.edit', [$coupon]) }}" class="btn btn-success">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endcan

                                @can('coupons_delete')
                                <a href="{{ route('admin.shop.coupons.destroy', [$coupon]) }}" class="btn btn-danger delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                @endcan
                            </td>
                        </td>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h4>Coupon Used</h4>
        </div>
        <div class="card-body">
            <table class="table">
                @foreach($coupon->users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->pivot_created_at
                                    ? date('d-M-Y h:i A', strtotime($user->pivot_created_at))
                                    : '' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

</x-admin.layout>
