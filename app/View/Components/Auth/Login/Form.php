<?php

namespace App\View\Components\Auth\Login;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    /**
     * Indicates whether the form is disabled due to rate limiting.
     *
     * @var bool
     */
    public bool $disabled = false;

    /**
     * Create a new component instance.
     */
    public function __construct(int $remaining)
    {
        $this->disabled = $remaining === 0;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auth.login.form');
    }
}
