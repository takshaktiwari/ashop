<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Category;

class ShopHeader extends Component
{
    public $categories;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->categories = Category::query()
            ->select('id', 'category_id', 'name', 'slug')
            ->isParent()
            ->with(['children' => function ($query) {
                $query->select('id', 'category_id', 'name', 'slug');
                $query->with('children:id,category_id,name,slug');
            }])
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
            'components.ashop.shop-header',
            'ashop::components.ashop.shop-header'
        ]);
    }
}
