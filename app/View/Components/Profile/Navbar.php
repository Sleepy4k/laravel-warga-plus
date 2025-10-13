<?php

namespace App\View\Components\Profile;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * The navigation items for the profile navbar.
     *
     * @var array
     */
    protected array $navItems;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->navItems = [
            'profile' => [
                'route' => 'profile.setting.index',
                'icon' => 'bx bx-user',
                'label' => 'Profile',
            ],
            'security' => [
                'route' => 'profile.security.index',
                'icon' => 'bx bx-lock-alt',
                'label' => 'Security',
            ],
            'shortcut' => [
                'route' => 'profile.shortcut.index',
                'icon' => 'bx bx-link-external',
                'label' => 'Shortcut',
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile.navbar', [
            'navItems' => $this->navItems,
            'currentRouteName' => request()->route()?->getName(),
        ]);
    }
}
