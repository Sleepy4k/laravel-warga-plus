<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Error extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $code = '404',
        public ?string $title = 'Page not found',
        public ?string $message = 'The page you are looking for was moved, removed, renamed or might never existed.',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.error');
    }
}
