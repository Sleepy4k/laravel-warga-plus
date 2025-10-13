<?php

namespace App\View\Components\Auth\Register;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * The steps for the registration process.
     *
     * @var array
     */
    private array $steps;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->steps = [
            [
                'title' => 'Get Started',
                'description' => 'Let’s set up your account',
                'icon' => 'home-alt',
                'target' => 'accountDetailsValidation',
            ],
            [
                'title' => 'Your Info',
                'description' => 'Tell us about yourself',
                'icon' => 'user',
                'target' => 'personalInfoValidation',
            ],
            [
                'title' => 'Confirm',
                'description' => 'Review & accept terms',
                'icon' => 'detail',
                'target' => 'tosLinksValidation',
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auth.register.header', [
            'steps' => $this->steps,
        ]);
    }
}
