<?php

namespace Takshak\Ashop\Traits;

trait AshopDataTableTrait
{
    public function rawButtonActionUrl(string $url)
    {
        return "
            let selectedValues = [];
            $('.selected_items:checked').each(function() {
                selectedValues.push($(this).val());
            });

            let baseUrl = '" . $url . "';
            let params = selectedValues.map(value => `item_ids[]=`+value).join('&');
            let fullUrl = baseUrl+`?`+params;

            window.location.href = fullUrl;
        ";
    }
}
