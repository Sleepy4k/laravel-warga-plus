<?php

namespace App\View\Components\Install\Table\Head;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Wrapper extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.install.table.head.wrapper');
    }
}
