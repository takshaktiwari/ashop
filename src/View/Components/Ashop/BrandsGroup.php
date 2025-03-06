<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;

class BrandsGroup extends Component
{
    public $brands;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $title,
        public $subtitle = null,
        public $buttons = [],
        public $order = 'latest', //latest, oldest, rand
        public $ids = [],
        public $categories = [],
        public $limit = 10,
        public $columns = 'row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6'
    ) {
        $this->brands = Brand::query()
            ->active()
            ->when($this->ids && count($this->ids), function ($query) {
                $query->whereIn('id', $this->ids);
            })
            ->when(count($this->categories), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', $this->categories);
                    $query->orWhereIn('categories.name', $this->categories);
                    $query->orWhereIn('categories.slug', $this->categories);
                });
            })
            ->when($this->order == 'latest', fn ($q) => $q->latest())
            ->when($this->order == 'oldest', fn ($q) => $q->oldest())
            ->when($this->order == 'rand', fn ($q) => $q->inRandomOrder())
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
            'components.ashop.brands-group',
            'ashop::components.ashop.brands-group'
        ]);
    }
}
