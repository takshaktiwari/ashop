<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Adash\Traits\AdashDataTableTrait;
use Takshak\Ashop\Actions\ProductAction;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Traits\AshopDataTableTrait;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    use AdashDataTableTrait;
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
                return '
                    <a href="' . route('admin.shop.products.copy', [$item]) . '" class="btn btn-sm btn-info load-circle">
                        <i class="fas fa-copy"></i>
                    </a>
                    <a href="' . route('admin.shop.products.edit', [$item]) . '" class="btn btn-sm btn-success load-circle">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="' . route('admin.shop.products.delete', [$item]) . '" class="btn btn-sm btn-danger load-circle">
                        <i class="fas fa-trash"></i>
                    </a>
                ';
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
            ->editColumn('image', function ($item) {
                return '
                    <a href="' . route('shop.products.show', [$item]) . '" target="_blank">
                        <img src="' . $item->image_sm() . '" alt="" style="max-height: 50px;" class="rounded">
                    </a>
                ';
            })
            ->editColumn('categories', function ($item) {
                return $item->categories->pluck('name')->implode(', ');
            })
            ->editColumn('net_price', function ($item) {
                return $item->formattedNetPrice();
            })
            ->editColumn('sell_price', function ($item) {
                return $item->formattedSellPrice();
            })
            ->editColumn('deal_price', function ($item) {
                return $item->formattedDealPrice();
            })
            ->editColumn('status', function ($item) {
                $colorClass = $item->status ? "text-success" : "text-danger";

                $html = '<span class="font-weight-bold ' . $colorClass . '">';
                $html .= $item->status ? 'Active' : 'In-Active';
                $html .= '</span>';

                return $html;
            })
            ->editColumn('featured', function ($item) {
                $colorClass = $item->featured ? "text-success" : "text-danger";

                $html = '<span class="font-weight-bold ' . $colorClass . '">';
                $html .= $item->featured ? 'Featured' : 'Not-featured';
                $html .= '</span>';

                return $html;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d-M-Y h:i A');
            })
            ->rawColumns(['action', 'checkbox', 'image', 'categories', 'status', 'featured']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        $query = Product::query();
        $query = (new ProductAction())->searchFilter($query);
        return $query->newQuery();
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
            ->stateSave(true)
            ->buttons([
                ...$this->getButtons(),
                Button::raw([
                    'extend' => 'colvis',
                    'text' => '<i class="fas fa-columns"></i>',
                    'className' => 'btn btn-secondary btn-sm'
                ]),
                Button::raw('deleteItems')
                    ->text('<i class="bi bi-archive" title="Delete Items"></i>')
                    ->action($this->rawButtonActionUrl(route('admin.shop.products.bulk.delete'), true)),
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
                ->addClass('text-nowrap'),
            Column::make('image')->searchable(false)->orderable(false),
            Column::make('name')->width(300),
            Column::make('sku'),
            Column::make('categories')->searchable(false)->orderable(false)->width(400),
            Column::make('net_price')->addClass('text-nowrap'),
            Column::make('sell_price')->addClass('text-nowrap'),
            Column::make('deal_price')->addClass('text-nowrap'),
            Column::make('status'),
            Column::make('featured'),
            Column::make('created_at')->addClass('text-nowrap'),
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
