<?php

namespace App\View\Components\Auth\Verification;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Resend extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $remaining
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auth.verification.resend');
    }
}
