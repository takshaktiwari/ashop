<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\ProductViewed;

class ProductsViewedHistory extends Component
{
    public $productsViewed;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $title = null, public $subtitle = null, public $limit = 25)
    {
        $this->productsViewed = ProductViewed::query()
            ->where('user_id', auth()->id())
            ->orWhere('user_ip', request()->ip())
            ->with('product:id,name,slug,image_sm')
            ->limit($this->limit)
            ->latest()
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return View::first([
            'components.ashop.products-viewed-history',
            'ashop::components.ashop.products-viewed-history'
        ]);
    }
}
