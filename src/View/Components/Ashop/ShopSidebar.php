<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;

class ShopSidebar extends Component
{
    public $categories;
    public $primaryCategory;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $category = null, public $filterAttributes = [])
    {

        $this->primaryCategory  = request('category')
            ? Category::where('slug', request('category'))->first()
            : $this->category;

        if (!count($this->filterAttributes) && $this->primaryCategory) {
            $this->filterAttributes = $this->primaryCategory->attributes->groupBy('name')->map(function ($item) {
                return $item->pluck('options')->collapse();
            });
        }

        $this->categories = Category::query()
            ->select('id', 'category_id', 'name', 'slug')
            ->isParent()
            ->with('children:id,category_id,name,slug')
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
            'components.ashop.shop-sidebar',
            'ashop::components.ashop.shop-sidebar'
        ]);
    }
}
