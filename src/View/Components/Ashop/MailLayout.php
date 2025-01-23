<?php

namespace Takshak\Ashop\View\Components\Ashop;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class MailLayout extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return View::first([
            'mails._layout',
            'ashop::mails._layout'
        ]);
    }
}
