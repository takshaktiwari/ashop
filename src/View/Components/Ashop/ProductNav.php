<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class ProductNav extends Component
{
    public $product;
    public $links;
    public function __construct($product)
    {
        $this->product = $product;
        $this->links = [];

        array_push(
            $this->links,
            ['text' =>  'Edit Info', 'url' => route('admin.shop.products.edit', [$product])]
        );

        array_push(
            $this->links,
            ['text' =>  'Details', 'url' => route('admin.shop.products.detail', [$product])]
        );

        array_push(
            $this->links,
            ['text' =>  'Attributes', 'url' => route('admin.shop.products.attributes', [$product])]
        );

        array_push(
            $this->links,
            ['text' =>  'Images', 'url' => route('admin.shop.products.images', [$product])]
        );
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return View::first([
            'components.ashop.product-nav',
            'ashop::components.ashop.product-nav'
        ]);
    }
}
