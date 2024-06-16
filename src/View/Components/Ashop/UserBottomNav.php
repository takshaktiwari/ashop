<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class UserBottomNav extends Component
{
    public $title = '';
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = null)
    {

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return View::first([
            'components.ashop.user-bottom-nav',
            'ashop::components.ashop.user-bottom-nav'
        ]);
    }
}
