<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class AdminSidebarLinks extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return View::first([
            'components.ashop.admin-sidebar-links',
            'ashop::components.ashop.admin-sidebar-links'
        ]);
    }
}
