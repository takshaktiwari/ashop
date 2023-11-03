<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Product;

class ProductsGroup extends Component
{
    public $products;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $title,
        public $type = 'latest',
        public $subtitle = null,
        public $categories = [],
        public $limit = 10,
        public $columns = 'row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5'
    ) {
        if ($type) {
            $this->$type();
        } else {
            $this->latest();
        }
    }

    public function latest()
    {
        $this->products = Product::query()
            ->when(count($this->categories), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', $this->categories);
                });
            })
            ->parent()
            ->active()
            ->latest()
            ->limit($this->limit)
            ->get();
    }


    public function featured()
    {
        $this->products = Product::query()
            ->when(count($this->categories), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', $this->categories);
                });
            })
            ->active()
            ->featured()
            ->parent()
            ->latest()
            ->limit($this->limit)
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
            'components.ashop.products-group',
            'ashop::components.ashop.products-group'
        ]);
    }
}
