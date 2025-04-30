<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Adash\Traits\AdashDataTableTrait;
use Takshak\Ashop\Models\Shop\Order;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    use AdashDataTableTrait;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('action', function ($item) {
                return '
                    <a href="' . route('admin.shop.orders.show', [$item]) . '" class="load-circle btn btn-sm btn-info" title="Order detail">
                        <i class="fas fa-info-circle"></i>
                    </a>
                    <a href="' . route('admin.shop.orders.destroy', [$item]) . '" class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
                        <i class="fas fa-trash"></i>
                    </a>
                ';
            })
            ->editColumn('checkbox', function ($item) {
                return '
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input selected_items" type="checkbox" name="item_ids[]" value="' . $item->id . '">
                        </label>
                    </div>
                ';
            })
            ->editColumn('order_no', function ($item) {
                return '<a href="' . route('admin.shop.orders.show', [$item]) . '">#' . $item->order_no . '</a>';
            })
            ->editColumn('user', function ($item) {
                return $item->user
                    ? '<a href="' . route('admin.users.show', [$item->user]) . '" class="text-nowrap">' . $item->user?->name . '</a>'
                    : '';
            })
            ->orderColumn('user', function ($query, $order) {
                $query->orderByRaw('users.name ' . $order);
            })
            ->filterColumn('user', function ($query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->whereRaw('users.name like ?', ["%{$keyword}%"]);
                    $query->orWhereRaw('users.email like ?', ["%{$keyword}%"]);
                    $query->orWhereRaw('users.mobile like ?', ["%{$keyword}%"]);
                });
            })
            ->editColumn('items', function ($item) {
                return  $item->order_products_count;
            })
            ->orderColumn('items', function ($query, $order) {
                $query->orderByRaw('order_products_count ' . $order);
            })
            ->editColumn('total_amount', function ($item) {
                return config('ashop.currency.sign') . $item->total_amount;
            })
            ->editColumn('payment_mode', fn($item) => $item->paymentMode())
            ->editColumn('order_status', function ($item) {
                return '<span>' . $item->orderStatus() . '</span>';
            })
            ->editColumn('address', function ($item) {
                return '<span class="fs-12">' . $item->address(2) . '</span>';
            })
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at?->format('Y-m-d h:i A') . '</span>';
            })
            ->rawColumns(['action', 'order_no', 'user', 'checkbox', 'order_status', 'address', 'created_at']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return Order::query()
            ->with('user')
            ->with('orderProducts')
            ->withCount('orderProducts')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
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
                    ->action($this->rawButtonActionUrl(route('admin.shop.orders.bulk.delete'), true)),
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
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->addClass('text-nowrap'),

            Column::make('order_no')->addClass('text-nowrap'),
            Column::make('user'),
            Column::make('items')->searchable(false),
            Column::make('subtotal'),
            Column::make('discount'),
            Column::make('shipping_charge')->title('Shipping'),
            Column::make('total_amount')->width(100)->orderable(false)->searchable(false),
            Column::make('coupon_code'),
            Column::make('payment_mode')->width(100),
            Column::make('payment_status')->width(100),
            Column::make('order_status')->width(100)->addClass('text-nowrap'),
            Column::make('address')->width(250)->orderable(false)->searchable(false),
            Column::make('created_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }

    protected function deleteItems()
    {
    }
}
