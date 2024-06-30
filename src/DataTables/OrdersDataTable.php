<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Ashop\Models\Shop\Order;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            ->editColumn('action', function ($order) {
                return '<a href="' . route('admin.shop.orders.destroy', [$order]) . '" class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
                    <i class="fas fa-trash"></i>
                </a>';
            })
            ->editColumn('checkbox', function ($order) {
                return '
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input selected_items" type="checkbox" name="selected_items[]" value="' . $order->id . '">
                        </label>
                    </div>
                ';
            })
            ->editColumn('order_no', function ($item) {
                return '<a href="#">#' . $item->order_no . '</a>';
            })
            ->editColumn('user', fn ($item) => $item->user?->name)
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
                return $item->order_products_count;
            })
            ->editColumn('amount', function ($item) {
                return $item->total_amount;
            })
            ->editColumn('payment', function ($item) {
                $mode = '<span class="fw-bold">' . $item->paymentMode() . '</span>';
                $status = '<span>' . $item->paymentStatus() . '</span>';
                return $mode . '<span class="text-nowrap"> (' . $status . ')</span>';
            })
            ->editColumn('order_status', function ($item) {
                return '<span>' . $item->orderStatus() . '</span>';
            })
            ->editColumn('address', function ($item) {
                return '<span class="fs-12">' . $item->address(2) . '</span>';
            })
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at?->format('Y-m-d h:i A') . '</span>';
            })
            ->rawColumns(['action', 'order_no', 'checkbox', 'payment', 'order_status', 'address', 'created_at']);
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
            ->setTableId('order-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-flex mb-3"B><"d-flex justify-content-between flex-wrap gap-3"lf>rt<"d-flex justify-content-between flex-wrap gap-3 mt-3"ip>')
            ->selectStyleSingle()
            ->responsive(true)
            ->pageLength(100)
            ->serverSide(true) // Enable server-side processing
            ->processing(true)
            ->buttons([
                Button::make('excel')->text('<i class="fas fa-file-excel"></i> Excel'),
                Button::make('pdf')->text('<i class="fas fa-file-pdf"></i> PDF'),
                Button::make('print')->text('<i class="fas fa-print"></i> Print'),
                Button::make('delete')->text('<i class="fas fa-trash"></i> Delete')->addClass('btn-danger'),
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

            Column::make('order_no')->addClass('text-nowrap'),
            Column::make('user'),
            Column::make('items'),
            Column::make('amount')->width(100),
            Column::make('payment')->width(100),
            Column::make('order_status')->width(100)->addClass('text-nowrap'),
            Column::make('address')->width(250)->orderable(false),
            Column::make('created_at')->orderable(false),
            Column::computed('action')
                ->width(60)
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
