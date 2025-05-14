<?php

namespace Takshak\Ashop\Traits;

use Yajra\DataTables\Html\Button;

trait AshopDataTableTrait
{
    public function getButtons(): array
    {
        $buttons = [];

        if (config('ashop.dataTables.actionBtns.exportExcel', false)) {
            $buttons[] = Button::make('excel');
        }
        if (config('ashop.dataTables.actionBtns.exportCSV', true)) {
            $buttons[] = Button::make('csv');
        }
        if (config('ashop.dataTables.actionBtns.exportPDF', false)) {
            $buttons[] = Button::make('pdf');
        }
        if (config('ashop.dataTables.actionBtns.print', true)) {
            $buttons[] = Button::make('print');
        }
        if (config('ashop.dataTables.actionBtns.reset', false)) {
            $buttons[] = Button::make('reset');
        }
        if (config('ashop.dataTables.actionBtns.reload', true)) {
            $buttons[] = Button::make('reload');
        }

        return $buttons;
    }
}
