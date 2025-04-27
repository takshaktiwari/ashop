<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Invoice extends Model
{
    protected $guarded = [];

    protected function address(): Attribute
    {
        return Attribute::make(
            get: function () {
                return config('ashop.invoices.prefix') . '-' . $this->invoice_no;
            }
        );
    }

    /**
     * Get the order that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
