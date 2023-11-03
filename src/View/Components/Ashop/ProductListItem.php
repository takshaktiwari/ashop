<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class ProductListItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $product)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return View::first([
            'components.ashop.product-list-item',
            'ashop::components.ashop.product-list-item'
        ]);
    }
}
