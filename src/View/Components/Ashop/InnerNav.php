<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class InnerNav extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $title, public $links)
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
            'components.ashop.inner-nav',
            'ashop::components.ashop.inner-nav'
        ]);

        return view('components.admin.inner-nav');
    }
}
