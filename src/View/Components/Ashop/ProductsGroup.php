<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Product;

class ProductsGroup extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $title = null,
        public $subtitle = null,
        public $heading = null,
        public $buttons = [],
        public $parent = true,
        public $type = null, //featured
        public $order = 'latest', //latest, oldest, rand
        public $ids = [],
        public $categories = [],
        public $products = [],
        public $limit = 10,
        public $container = true,
        public $columns = 'row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5'
    ) {
        if (!count($this->products)) {
            $this->products = Product::query()
                ->loadCardDetails()
                ->when(count($this->categories), function ($query) {
                    $query->whereHas('categories', function ($query) {
                        $query->whereIn('categories.id', $this->categories);
                        $query->orWhereIn('categories.name', $this->categories);
                        $query->orWhereIn('categories.slug', $this->categories);
                    });
                })
                ->when($this->ids && count($this->ids), function ($query) {
                    $query->whereIn('products.id', $this->ids);
                })
                ->when($this->parent, function ($query) {
                    $query->parent();
                })
                ->when($this->type == 'featured', function ($query) {
                    $query->featured();
                })
                ->when($this->order == 'latest', function ($query) {
                    $query->latest();
                })
                ->when($this->order == 'oldest', function ($query) {
                    $query->oldest();
                })
                ->when($this->order == 'rand', function ($query) {
                    $query->inRandomOrder();
                })
                ->limit($this->limit)
                ->get();
        }
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
