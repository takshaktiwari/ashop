<?php

namespace Takshak\Ashop\Traits;

trait AshopModelTrait
{
    public function numberFormat($key)
    {
        return number_format($this->{$key}, 2);
    }

    public function priceFormat($key)
    {
        return config('ashop.currency.sign', '₹') . number_format($this->{$key}, 2);
    }
}
