<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Product;

class CategoriesGroup extends Component
{
    public $categories;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $title,
        public $subtitle = null,
        public $buttons = [],
        public $parent = true,
        public $type = null, //featured, is_top
        public $order = 'latest', //latest, oldest, rand
        public $ids = [],
        public $category_id = null,
        public $limit = 10,
        public $columns = 'row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5'
    ) {
        $this->categories = Category::query()
            ->active()
            ->when($this->ids && count($this->ids), function ($query) {
                $query->whereIn('id', $this->ids);
            })
            ->when($this->parent, fn ($q) => $q->isParent())
            ->when($this->category_id, fn ($q) => $q->where('category_id', $this->category_id))
            ->when($this->type == 'featured', fn ($q) => $q->where('featured', true))
            ->when($this->type == 'is_top', fn ($q) => $q->where('is_top', true))
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
            'components.ashop.categories-group',
            'ashop::components.ashop.categories-group'
        ]);
    }
}
