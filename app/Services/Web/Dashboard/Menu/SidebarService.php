<?php

namespace App\Services\Web\Dashboard\Menu;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Menu;
use App\Models\MenuMeta;
use App\Models\Permission;

class SidebarService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $selectedChildFields = implode(',', [
            'id', 'route', 'icon', 'permissions',
            'parameters', 'active_routes', 'is_sortable'
        ]);

        $menus = Menu::with([
                'meta:'.$selectedChildFields,
                'children.meta:'.$selectedChildFields,
            ])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get()
            ->map(function ($menu) {
                $menu->setRelation(
                    'children',
                    $menu->children->sortBy('order')->values()
                );
                return $menu;
            });

        $parentMenus = Menu::select('id', 'name', 'order', 'parent_id')
            ->whereNull('parent_id')
            ->where('is_spacer', false)
            ->orderBy('order')
            ->get();

        $permissions = Permission::select('name', 'guard_name')
            ->where('guard_name', 'web')
            ->get();

        $routeNameList = getWebRoutes();

        return compact('menus', 'parentMenus', 'permissions', 'routeNameList');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $metaPayload = [
                'route' => $request['route'] ?? null,
                'icon' => $request['icon'] ?? null,
                'active_routes' => $request['active_routes'] ?? null,
                'parameters' => $request['parameters'] ?? [],
                'is_sortable' => $request['is_sortable'] ?? true,
                'permissions' => $request['permissions'] ?? []
            ];

            $meta = MenuMeta::create($metaPayload);

            if ($meta) {
                $menuPayload = [
                    'name' => $request['name'],
                    'is_spacer' => $request['is_spacer'],
                    'parent_id' => $request['is_spacer'] ? null : ($request['parent_id'] ?? null),
                    'meta_id' => $meta->id,
                ];

                if (!empty($request['parent_id']) && !$request['is_spacer']) {
                    $children = Menu::where('parent_id', $request['parent_id'])
                        ->where('is_spacer', false)
                        ->orderBy('order', 'desc')
                        ->get();

                    $highestOrder = $children->isNotEmpty() ? $children->first()->order : 0;
                    $menuPayload['order'] = $highestOrder + 1;
                } else {
                    $menuPayload['order'] = Menu::whereNull('parent_id')
                        ->max('order') + 1;
                }

                Menu::create($menuPayload);
            }

            return (bool) $meta;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create menu: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Menu $sidebar): bool
    {
        try {
            $meta = $sidebar->meta;

            if ($meta) {
                $meta->update([
                    'route' => $request['route'] ?? null,
                    'icon' => $request['icon'] ?? null,
                    'active_routes' => $request['active_routes'] ?? null,
                    'parameters' => $request['parameters'] ?? [],
                    'is_sortable' => $request['is_sortable'] ?? true,
                    'permissions' => $request['permissions'] ?? []
                ]);
            }

            $sidebar->update([
                'name' => $request['name'],
                'is_spacer' => $request['is_spacer'],
                'parent_id' => $request['is_spacer'] ? null : ($request['parent_id'] ?? null),
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update menu: ' . $th->getMessage(), [
                'menu_id' => $sidebar->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $sidebar): bool
    {
        try {
            $sidebar->children()->delete();
            $sidebar->meta()->delete();
            return $sidebar->delete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete menu: ' . $th->getMessage(), [
                'menu_id' => $sidebar->id,
            ]);
            return false;
        }
    }
}
