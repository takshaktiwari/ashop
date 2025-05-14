<?php

namespace Takshak\Ashop\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Takshak\Adash\Traits\AdashDataTableTrait;
use Takshak\Ashop\Models\Shop\SearchedTerm;
use Takshak\Ashop\Traits\AshopDataTableTrait;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SearchedTermsDataTable extends DataTable
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
                return '<a href="' . route('admin.shop.searched-terms.destroy', [$item]) . '" class="load-circle btn btn-sm btn-danger delete-alert" title="Delete this">
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
            ->editColumn('created_at', function ($item) {
                return '<span class="text-nowrap">' . $item->created_at->format('Y-m-d h:i A') . '</span>';
            })
            ->rawColumns(['action', 'checkbox', 'created_at']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return SearchedTerm::query()
            ->with('user:id,name,email,mobile')
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('searched-terms-table')
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
                    ->action($this->rawButtonActionUrl(url: route('admin.shop.searched-terms.destroy.checked'), confirm: true)),
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
            Column::make('term'),
            Column::make('count'),
            Column::make('user.name'),
            Column::make('user.email'),
            Column::make('user.mobile'),
            Column::make('user_ip')->width(120),
            Column::make('created_at'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SearchedTerms_' . date('YmdHis');
    }
}
