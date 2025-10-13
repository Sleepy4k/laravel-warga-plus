<?php

namespace App\View\Components\Menu;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sub extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $isActive = false,
        public ?string $route = null,
        public ?string $name = null,
        public ?string $id = null,
        public ?string $suffix = null,
    ) {
        $this->suffix = rand(100, 999);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu.sub');
    }
}
