<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Adash\Traits\AdashDataTableTrait;
use Takshak\Ashop\Models\Shop\Cart;
use Takshak\Ashop\Models\Shop\Coupon;
use Takshak\Ashop\Traits\AshopDataTableTrait;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CouponsDataTable extends DataTable
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
            ->addColumn('action', function ($item) {
                $html = view('components.admin.btns.action-show', [
                    'url' => route('admin.shop.coupons.show', [$item])
                ]);

                $html .= view('components.admin.btns.action-edit', [
                    'url' => route('admin.shop.coupons.edit', [$item])
                ]);

                $html .= view('components.admin.btns.action-delete', [
                    'url' => route('admin.shop.coupons.destroy', [$item])
                ]);

                return $html;
            })
            ->addColumn('checkbox', function ($cart) {
                return '
                    <div class="form-check">
                        <label class="form-check-label mb-0">
                            <input class="form-check-input selected_items" type="checkbox" name="item_ids[]" value="' . $cart->id . '">
                        </label>
                    </div>
                ';
            })
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at?->format('Y-m-d h:i A') . '</span>';
            })
            ->editColumn('discount_type', fn ($item) =>  ucfirst($item->discount_type))
            ->editColumn('percent', fn ($item) => $item->percent ? $item->percent . '%' : '')
            ->editColumn('amount', fn ($item) => $item->priceFormat('amount', true))
            ->editColumn('min_purchase', fn ($item) => $item->priceFormat('min_purchase', true))
            ->editColumn('max_discount', fn ($item) => $item->priceFormat('max_discount', true))
            ->editColumn('expires_at', fn ($item) => $item->expires_at?->format('d-m-Y h:i A'))
            ->editColumn('status', fn ($q) => $q->status ? 'Active' : '')
            ->editColumn('featured', fn ($q) => $q->featured ? 'Featured' : '')
            ->rawColumns(['action', 'checkbox', 'created_at']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return Coupon::query()->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('coupons-table')
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
                    ->addClass('bg-danger text-white')
                    ->action($this->rawButtonActionUrl(route('admin.shop.coupons.bulk.delete'), true)),

                Button::raw('activeItems')
                    ->text('<i class="bi bi-check-circle-fill" title="Make Items Active"></i>')
                    ->addClass('bg-primary text-white')
                    ->action($this->rawButtonActionUrl(route('admin.shop.coupons.bulk.active'))),

                Button::raw('inactiveItems')
                    ->text('<i class="bi bi-circle" title="Make Items Inactive"></i>')
                    ->addClass('bg-primary text-white')
                    ->action($this->rawButtonActionUrl(route('admin.shop.coupons.bulk.inactive'))),

                Button::raw('featuredItems')
                    ->text('<i class="bi bi-bookmark-star-fill" title="Make Items featured"></i>')
                    ->addClass('bg-secondary text-white')
                    ->action($this->rawButtonActionUrl(route('admin.shop.coupons.bulk.featured'))),

                Button::raw('notFeaturedItems')
                    ->text('<i class="bi bi-bookmark" title="Make Items not featured"></i>')
                    ->addClass('bg-secondary text-white')
                    ->action($this->rawButtonActionUrl(route('admin.shop.coupons.bulk.not-featured'))),
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
                ->addClass('text-center text-nowrap'),

            Column::make('code'),
            Column::make('discount_type')->title('Type'),
            Column::make('percent'),
            Column::make('amount'),
            Column::make('min_purchase')->addClass('text-nowrap'),
            Column::make('max_discount')->addClass('text-nowrap'),
            Column::make('expires_at')->addClass('text-nowrap'),
            Column::make('max_usable')->title('Usable'),
            Column::make('status'),
            Column::make('featured'),
            Column::make('title'),
            Column::make('created_at'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Coupons_' . date('YmdHis');
    }
}
