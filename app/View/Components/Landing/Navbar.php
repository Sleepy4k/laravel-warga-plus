<?php

namespace App\View\Components\Landing;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * The navigation menus.
     *
     * @var array
     */
    private array $menus = [];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->menus = [
            [
                'name' => 'Home',
                'route' => 'landing.home',
            ],
            [
                'name' => 'Tentang Kami',
                'route' => 'cookie.policy',
            ],
            [
                'name' => 'Laporan',
                'route' => 'cookie.policy',
            ],
            [
                'name' => 'Informasi RT',
                'route' => 'cookie.policy',
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.landing.navbar', [
            'menus' => $this->menus,
            'isLoggedIn' => auth('web')->check(),
            'currentRouteName' => request()->route()?->getName(),
        ]);
    }
}
