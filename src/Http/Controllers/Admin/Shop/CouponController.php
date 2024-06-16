<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Actions\CouponAction;
use Takshak\Ashop\Models\Shop\Coupon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::withCount('users')->orderBy('id', 'DESC')->get();

        return View::first(['admin.shop.coupons.index', 'ashop::admin.shop.coupons.index'])
            ->with([
                'coupons'   =>  $coupons
            ]);
    }

    public function create()
    {
        return View::first(['admin.shop.coupons.create', 'ashop::admin.shop.coupons.create']);
    }

    public function store(Request $request, CouponAction $action)
    {
        $request->validate([
            'code'        =>  'required|unique:coupons,code',
            'discount_type' =>  'required|in:amount,percent',
            'percent'       =>  'nullable|numeric|required_if:discount_type,percent',
            'amount'        =>  'nullable|numeric|required_if:discount_type,amount',
            'max_discount'  =>  'nullable|numeric',
            'max_usable'    =>  'nullable|numeric',
            'expires_at'    =>  'nullable|date',
        ]);

        $coupon = $action->save($request, new Coupon);
        return to_route('admin.shop.coupons.show', [$coupon])->withSuccess('SUCCESS !! Coupon has been created.');
    }

    public function show(Coupon $coupon)
    {
        return View::first(['admin.shop.coupons.show', 'ashop::admin.shop.coupons.show'])
            ->with([
                'coupon'   =>  $coupon
            ]);
    }

    public function edit(Coupon $coupon)
    {
        return View::first(['admin.shop.coupons.edit', 'ashop::admin.shop.coupons.edit'])
            ->with([
                'coupon'   =>  $coupon
            ]);
    }

    public function update(Request $request, Coupon $coupon, CouponAction $action)
    {
        $coupon = $action->save($request, $coupon);
        return to_route('admin.shop.coupons.show', [$coupon])->withSuccess('SUCCESS !! Coupon is successfully updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return to_route('admin.shop.coupons.index')->withSuccess('SUCCESS !! Coupon is successfully deleted.');
    }
}
