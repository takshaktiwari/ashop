<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Adash\Traits\AdashDataTableTrait;
use Takshak\Ashop\Models\Shop\WishlistItem;
use Takshak\Ashop\Traits\AshopDataTableTrait;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WishlistDataTable extends DataTable
{
    use AdashDataTableTrait, AshopDataTableTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($wishlist) {
                return '<a href="' . route('admin.shop.wishlist.destroy', [$wishlist]) . '" class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
                    <i class="fas fa-trash"></i>
                </a>';
            })
            ->addColumn('checkbox', function ($wishlist) {
                return '
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input selected_items" type="checkbox" name="selected_items[]" value="' . $wishlist->id . '">
                        </label>
                    </div>
                ';
            })
            ->editColumn('user.name', function ($item) {
                if ($item->user) {
                    return '<a href="' . route('admin.users.show', [$item->user]) . '" target="_blank">' . $item->user?->name . '</a>';
                }
            })
            ->editColumn('product', function ($item) {
                return '<a href="' . route('shop.products.show', [$item->product]) . '" class="lc-2" target="_blank">' . $item->product?->name . '</a>';
            })
            ->orderColumn('product', function ($query, $order) {
                $query->orderByRaw('products.name ' . $order);
            })
            ->filterColumn('product', function ($query, $keyword) {
                $query->whereRaw('products.name like ?', ["%{$keyword}%"]);
            })
            ->editColumn('sell_price', fn($item) => config('ashop.currency.sign', '₹') . $item->product->sell_price)
            ->orderColumn('sell_price', function ($query, $order) {
                $query->orderByRaw('products.sell_price ' . $order);
            })
            ->filterColumn('sell_price', function ($query, $keyword) {
                $query->whereRaw('products.sell_price like ?', ["%{$keyword}%"]);
            })
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at?->format('Y-m-d h:i A') . '</span>';
            })
            ->rawColumns(['action', 'checkbox', 'product', 'created_at', 'user.name']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return WishlistItem::query()
            ->select('wishlist_items.*')
            ->with('product')
            ->with('user')
            ->join('products', 'products.id', '=', 'wishlist_items.product_id')
            ->leftJoin('users', 'users.id', '=', 'wishlist_items.user_id')
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('wishlist-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-flex mb-2 justify-content-between flex-wrap gap-3"<"d-flex gap-3"lB>f>rt<"d-flex justify-content-between flex-wrap gap-3 mt-3"ip>')
            ->selectStyleSingle()
            ->responsive(true)
            ->pageLength(100)
            ->serverSide(true) // Enable server-side processing
            ->processing(true)
            ->buttons([
                ...$this->getButtons(),
                Button::raw('deleteItems')
                    ->text('<i class="bi bi-archive" title="Delete Items"></i>')
                    ->action($this->rawButtonActionUrl(route('admin.shop.wishlist.destroy.checked'))),
            ])
            ->initComplete('function(settings, json) {
                $("#check_all_items").click(function(){
                    $(".selected_items").prop("checked", $(this).is(":checked"));
                });
            }');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(true)
                ->width(30)
                ->addClass('text-center text-nowrap'),

            Column::computed('checkbox')
                ->title('
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input" type="checkbox" id="check_all_items" value="1">
                        </label>
                    </div>
                ')
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(true)
                ->width(20)
                ->addClass('text-center'),

            Column::computed('action')
                ->width(60)
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false),

            Column::make('user.name'),
            Column::make('product'),
            Column::make('sell_price')->width(120),
            Column::make('created_at')->addClass('text-nowrap'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Wishlist_' . date('YmdHis');
    }
}
