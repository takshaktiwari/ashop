<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Ashop\Models\Shop\Cart;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CouponsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($cart) {
                return '<a href="' . route('admin.shop.carts.destroy', [$cart]) . '" class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
                    <i class="fas fa-trash"></i>
                </a>';
            })
            ->addColumn('checkbox', function ($cart) {
                return '
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input selected_carts" type="checkbox" name="selected_carts[]" value="' . $cart->id . '">
                        </label>
                    </div>
                ';
            })
            ->editColumn('ip', fn ($item) => $item->user_ip)
            ->orderColumn('ip', function ($query, $order) {
                $query->orderByRaw('carts.user_ip ' . $order);
            })
            ->filterColumn('ip', function ($query, $keyword) {
                $query->whereRaw('carts.user_ip like ?', ["%{$keyword}%"]);
            })
            ->editColumn('user', function ($item) {
                if ($item->user) {
                    return '<a href="' . route('admin.users.show', [$item->user]) . '" target="_blank">' . $item->user?->name . '</a>';
                }
            })
            ->orderColumn('user', function ($query, $order) {
                $query->orderByRaw('users.name ' . $order);
            })
            ->filterColumn('user', function ($query, $keyword) {
                $query->whereRaw('users.name like ?', ["%{$keyword}%"]);
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
            ->editColumn('qty', fn ($item) => $item->quantity)
            ->orderColumn('qty', function ($query, $order) {
                $query->orderByRaw('carts.quantity ' . $order);
            })
            ->filterColumn('qty', function ($query, $keyword) {
                $query->whereRaw('carts.quantity like ?', ["%{$keyword}%"]);
            })
            ->editColumn('sell_price', fn ($item) => config('ashop.currency.sign', '₹') . $item->product->sell_price)
            ->orderColumn('sell_price', function ($query, $order) {
                $query->orderByRaw('products.sell_price ' . $order);
            })
            ->filterColumn('sell_price', function ($query, $keyword) {
                $query->whereRaw('products.sell_price like ?', ["%{$keyword}%"]);
            })
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at->format('Y-m-d h:i A') . '</span>';
            })
            ->rawColumns(['action', 'checkbox', 'product', 'created_at', 'user']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return Cart::query()
            ->select('carts.*')
            ->with('product')
            ->with('user')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->leftJoin('users', 'users.id', '=', 'carts.user_id')
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('locations-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-flex mb-2 justify-content-between flex-wrap gap-3"<"d-flex gap-3"lB>f>rt<"d-flex justify-content-between flex-wrap gap-3 mt-3"ip>')
            ->selectStyleSingle()
            ->responsive(true)
            ->pageLength(100)
            ->serverSide(true) // Enable server-side processing
            ->processing(true)
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
                Button::raw('deleteItems')
                    ->text('<i class="bi bi-archive" title="Delete Items"></i>')
                    ->action("
                        let selectedValues = [];
                        $('.selected_carts:checked').each(function() {
                            selectedValues.push($(this).val());
                        });

                        let baseUrl = '" . route('admin.shop.carts.destroy.checked') . "';
                        let params = selectedValues.map(value => `cart_ids[]=`+value).join('&');
                        let fullUrl = baseUrl+`?`+params;

                        window.location.href = fullUrl;
                    "),
            ])
            ->initComplete('function(settings, json) {
                $("#check_all_items").click(function(){
                    $(".selected_carts").prop("checked", $(this).is(":checked"));
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
                ->addClass('text-center'),

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

            Column::make('user'),
            Column::make('ip'),
            Column::make('product'),
            Column::make('qty'),
            Column::make('sell_price')->width(120),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Carts_' . date('YmdHis');
    }
}
