<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\DataTables\CartsDataTable;
use Takshak\Ashop\Models\Shop\Cart;

class CartController extends Controller
{
    public function index(CartsDataTable $dataTable)
    {
        return $dataTable->render(
            View::exists('admin.shop.carts.index') ? 'admin.shop.carts.index' : 'ashop::admin.shop.carts.index'
        );
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return back();
    }

    public function destroyChecked(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array|min:1'
        ]);

        Cart::whereIn('id', $request->input('cart_ids'))->delete();
        return back()->withSuccess('Carts has been deleted');
    }
}
