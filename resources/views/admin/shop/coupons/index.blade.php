<x-admin.layout>
    <div class="container-fluid">
        <!-- start page title -->
        <x-admin.breadcrumb title='All Coupons' :links="[['text' => 'Dashboard', 'url' => route('admin.dashboard')], ['text' => 'All Coupons']]" :actions="[['text' => 'New Coupon', 'url' => route('admin.shop.coupons.create'), 'icon' => 'fas fa-plus']]" />

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Coupon</th>
                        <th>Discount</th>
                        <th>Max. Discount</th>
                        <th>Used</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr class="{{ $coupon->featured ? 'table-success' : '' }}">
                                <th>{{ $loop->iteration }}</th>
                                <th>{{ $coupon->code }}</th>
                                <th>
                                    {{ $coupon->formattedPercent() }}
                                    {{ $coupon->formattedAmount() }}
                                </th>
                                <th>{{ $coupon->formattedMaxDiscount() }}</th>
                                <th>{{ $coupon->users_count ? $coupon->users_count . ' times' : '' }}
                                </th>
                                <td>
                                    {{ $coupon->status ? 'Active' : 'In-Active' }}
                                    <div class="font-weight-bold">{{ $coupon->featured ? 'Featured' : '' }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.shop.coupons.show', [$coupon]) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-info-circle"></i>
                                    </a>

                                    <a href="{{ route('admin.shop.coupons.edit', [$coupon]) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.shop.coupons.destroy', [$coupon]) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin.layout>
