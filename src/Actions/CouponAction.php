<?php

namespace Takshak\Ashop\Actions;

class CouponAction
{
    public function save($request, $coupon)
    {
        $coupon->code           =  $request->post('code');
        $coupon->discount_type  =  $request->post('discount_type');
        $coupon->percent        =  ($request->post('discount_type') == 'percent')
            ? $request->post('percent')
            : null;
        $coupon->amount         =  ($request->post('discount_type') == 'amount')
            ? $request->post('amount')
            : null;
        $coupon->expires_at     =  $request->post('expires_at');
        $coupon->min_purchase   =  $request->post('min_purchase');
        $coupon->max_discount   =  $request->post('max_discount');
        $coupon->max_usable     =  ($request->post('discount_type') == 'percent')
            ? $request->post('max_discount')
            : null;
        $coupon->status         =  $request->boolean('status');
        $coupon->featured       =  $request->boolean('featured');
        $coupon->title          =  $request->post('title');
        $coupon->description    =  $request->post('description');
        $coupon->save();
        return $coupon;
    }
}
