<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\DataTables\WishlistDataTable;
use Takshak\Ashop\Models\Shop\WishlistItem;

class WishlistController extends Controller
{
    public function index(WishlistDataTable $dataTable)
    {
        return $dataTable->render(
            View::exists('admin.shop.wishlist.index') ? 'admin.shop.wishlist.index' : 'ashop::admin.shop.wishlist.index'
        );
    }

    public function destroy(WishlistItem $wishlist)
    {
        $wishlist->delete();
        return back();
    }

    public function destroyChecked(Request $request)
    {
        $request->validate([
            'wishlist_ids' => 'required|array|min:1'
        ]);

        WishlistItem::whereIn('id', $request->input('wishlist_ids'))->delete();
        return back()->withSuccess('Wishlist item has been deleted');
    }
}
