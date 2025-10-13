<?php

namespace App\View\Components\Dashboard\Canvas;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Wrapper extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $canvasId = 'add-new-record',
        public ?string $canvasPermission = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.canvas.wrapper');
    }
}
