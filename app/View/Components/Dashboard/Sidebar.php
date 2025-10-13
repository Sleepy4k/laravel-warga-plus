<?php

namespace App\View\Components\Dashboard;

use App\Contracts\Models\MenuInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * The collection of menus.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $menus;

    /**
     * Create a new component instance.
     */
    public function __construct(MenuInterface $menuInterface)
    {
        $menus = $menuInterface
            ->all(
                ['id', 'name', 'order', 'is_spacer', 'parent_id', 'meta_id'],
                [
                    'meta:id,route,icon,permissions,parameters,active_routes',
                    'children.meta:id,route,icon,permissions,parameters,active_routes',
                ],
                [['parent_id', '=', null]]
            );

        $this->menus = collect($menus ?: [])->sortBy('order')->values();
        $this->menus = $this->menus->map(function ($menu) {
            $menu = $this->sortMenuChildren($menu);
            return $menu;
        });
    }

    /**
     * Recursively sort menu children by 'order'.
     */
    private function sortMenuChildren($menu)
    {
        if ($menu->relationLoaded('children') && $menu->children->isNotEmpty()) {
            $menu->setRelation(
                'children',
                $menu->children->sortBy('order')->values()
            );
        }

        return $menu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.sidebar', [
            'menus' => $this->menus,
            'currentRouteName' => request()->route()?->getName(),
        ]);
    }
}
