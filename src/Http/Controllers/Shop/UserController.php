<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Address;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Imager\Facades\Imager;

class UserController extends Controller
{
    public function dashboard()
    {
        $defaultAddr = Address::where('user_id', auth()->id())->where('default_addr', true)->first();
        $billingAddr = Address::where('user_id', auth()->id())->where('billing_addr', true)->first();
        if (!$defaultAddr) {
            $defaultAddr = Address::where('user_id', auth()->id())->first();
            if ($defaultAddr) {
                $defaultAddr->default_addr = true;
                $defaultAddr->save();
            }
        }

        if (!$billingAddr) {
            $billingAddr = Address::where('user_id', auth()->id())->first();
            if ($billingAddr) {
                $billingAddr->billing_addr = true;
                $billingAddr->save();
            }
        }


        $wishlistItemsCount = auth()->user()->wishlistItems?->count();
        $pendingOrdersCount = Order::query()
            ->where('user_id', auth()->id())
            ->where('order_status', config('ashop.order.initial_status'))
            ->count();

        $deliveredOrdersCount = Order::query()
            ->where('user_id', auth()->id())
            ->where('order_status', config('ashop.order.initial_status'))
            ->count();

        $recentOrders = Order::query()
            ->withCount('orderProducts')
            ->whereNotNull('order_status')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();

        return View::first(['shop.user.dashboard', 'ashop::shop.user.dashboard'])->with([
            'defaultAddr' => $defaultAddr,
            'billingAddr' => $billingAddr,
            'wishlistItemsCount' => $wishlistItemsCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function profile()
    {
        return View::first(['shop.user.profile', 'ashop::shop.user.profile']);
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'password' => 'nullable',
            'profile_img' => 'nullable|file',
        ]);

        $user = auth()->user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        if ($request->file('profile_img')) {
            $user->profile_img = 'users/' . time() . '.jpg';
            $filePath = Storage::disk('public')->path($user->profile_img);
            Imager::init($request->file('profile_img'))
                ->resizeFit(300, 300)
                ->save($filePath);
        }
        $user->save();

        return to_route('shop.user.profile');
    }
}
