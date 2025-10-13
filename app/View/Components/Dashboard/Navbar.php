<?php

namespace App\View\Components\Dashboard;

use App\Contracts\Models\NavbarMenuInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * The collection of shortcuts.
     *
     * @var Illuminate\Support\Collection
     */
    protected $shortcuts;

    /**
     * The collection of menus.
     *
     * @var Illuminate\Database\Eloquent\Collection
     */
    protected $menus;

    /**
     * The authenticated user.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new component instance.
     */
    public function __construct(NavbarMenuInterface $navbarMenuInterface)
    {
        $this->user = auth('web')->user()->load('personal:user_id,first_name,last_name,avatar', 'shortcuts:id,label,icon,route,permissions');
        $this->shortcuts = $this->user->shortcuts;
        $this->menus = $navbarMenuInterface
            ->all(
                ['id', 'name', 'order', 'is_spacer', 'meta_id'],
                [
                    'meta:id,route,icon,permissions,parameters,active_routes',
                ], [], 'order', false
            );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.navbar', [
            'user' => $this->user,
            'role' => $this->user->getRoleNames()->first() ?? "Guest",
            'personal' => $this->user->personal,
            'dropdownItems' => $this->menus,
            'shortcuts' => $this->shortcuts,
            'currentRouteName' => request()->route()?->getName() ?? '',
        ]);
    }
}
