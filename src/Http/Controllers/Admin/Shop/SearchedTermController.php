<?php

namespace Takshak\Ashop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\DataTables\SearchedTermsDataTable;
use Takshak\Ashop\Models\Shop\SearchedTerm;

class SearchedTermController extends Controller
{
    public function index(SearchedTermsDataTable $dataTable)
    {
        return $dataTable->render(
            View::exists('admin.shop.searched-terms.index') ? 'admin.shop.searched-terms.index' : 'ashop::admin.shop.searched-terms.index'
        );
    }

    public function destroy(SearchedTerm $searchedTerm)
    {
        $searchedTerm->delete();
        return back()->withSuccess('SUCCESS !! Searched Term is successfully deleted.');
    }

    public function destroyChecked(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array|min:1'
        ]);
        SearchedTerm::whereIn('id', $request->input('item_ids'))->delete();
        return back()->withSuccess('SUCCESS !! Searched Terms are successfully deleted.');
    }
}
