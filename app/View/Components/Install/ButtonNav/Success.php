<?php

namespace App\View\Components\Install\ButtonNav;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Success extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $url
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.install.button-nav.success');
    }
}
