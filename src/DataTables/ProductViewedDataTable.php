<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Ashop\Actions\ProductAction;
use Takshak\Ashop\Models\Shop\ProductViewed;
use Takshak\Ashop\Traits\AshopDataTableTrait;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductViewedDataTable extends DataTable
{
    use AshopDataTableTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.shop.products.viewed.history.delete', [$item]) . '" class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
                    <i class="fas fa-trash"></i>
                </a>';
            })
            ->addColumn('checkbox', function ($item) {
                return '
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input selected_items" type="checkbox" name="item_ids[]" value="' . $item->id . '">
                        </label>
                    </div>
                ';
            })
            ->editColumn('user.name', function ($item) {
                if ($item->user) {
                    return '<a href="' . route('admin.users.show', [$item->user]) . '" target="_blank">' . $item->user?->name . '</a>';
                }
            })
            ->editColumn('product.name', function ($item) {
                if ($item) {
                    return '<a href="' . route('admin.shop.products.edit', [$item->product]) . '" target="_blank">' . $item->product?->name . '</a>';
                }
            })
            ->editColumn('product.net_price', fn($item) => $item->product?->priceFormat('net_price'))
            ->editColumn('product.sell_price', fn($item) => $item->product?->priceFormat('sell_price'))
            ->editColumn('product.deal_price', fn($item) => $item->product?->priceFormat('deal_price'))
            ->editColumn('product.deal_expiry', fn($item) => $item->product?->deal_expiry?->format('d-m-Y h:i A'))
            ->editColumn('product.featured', fn($item) => $item->product?->featured ? 'Featured' : '')
            ->editColumn('product.status', fn($item) => $item->product?->status ? 'Active' : '')
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at?->format('Y-m-d h:i A') . '</span>';
            })
            ->rawColumns(['action', 'checkbox', 'created_at', 'user.name', 'product.name']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return ProductViewed::query()
            ->select('product_vieweds.*')
            ->with('user')
            ->with('product')
            ->whereHas('product', function ($query) {
                return  $query = (new ProductAction())->searchFilter($query);
            })
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-viewed-table')
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
                    ->action($this->rawButtonActionUrl(route('admin.shop.products.viewed.history.bulk.delete'))),
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
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->width(60)
                ->addClass('text-center'),

            Column::make('user.name')->title('User'),
            Column::make('user_ip'),
            Column::make('product.name')->title('Product'),
            Column::make('product.net_price')->title('Net Price')->addClass('text-nowrap'),
            Column::make('product.sell_price')->title('Sell Price')->addClass('text-nowrap'),
            Column::make('product.deal_price')->title('Deal Price')->addClass('text-nowrap'),
            Column::make('product.deal_expiry')->title('Deal Expiry')->addClass('text-nowrap'),
            Column::make('product.stock')->title('Stock'),
            Column::make('product.sku')->title('SKU'),
            Column::make('product.featured')->title('Featured'),
            Column::make('product.status')->title('Status'),
            Column::make('created_at'),

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
