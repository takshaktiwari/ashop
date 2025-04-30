<?php

namespace Takshak\Ashop\Traits;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Invoice;
use Takshak\Ashop\Models\Shop\Order;

trait AshopInvoiceTrait
{
    public function getInvoice(Order $order)
    {
        $invoice = Invoice::firstOrCreate([
            'order_id' => $order->id,
            'invoice_no' => $order->getInvoiceNo()
        ]);

        $invoiceFile = 'invoices/' . $invoice->invoice_no_prefixed . '.pdf';
        if (Storage::disk('public')->exists($invoiceFile)) {
            return $invoiceFile;
        }

        return $this->generateInvoice($order);
    }

    public function generateInvoice(Order $order)
    {
        $invoice = Invoice::firstOrCreate([
            'order_id' => $order->id,
            'invoice_no' => $order->getInvoiceNo()
        ]);

        $pdf = SnappyPdf::loadHTML(
            View::first([
                'shop.user.orders.invoice',
                'ashop::shop.user.orders.invoice'
            ])->with([
                'order' => $order,
                'invoice' => $invoice
            ])->render()
        );

        $invoiceFile = 'invoices/' . $invoice->invoice_no_prefixed . '.pdf';
        Storage::disk('public')->put($invoiceFile, $pdf->output());

        return $invoiceFile;
    }
}
