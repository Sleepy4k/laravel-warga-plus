<?php

namespace App\Services\Web\Dashboard\Menu;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\NavbarMenu;
use App\Models\NavbarMenuMeta;
use App\Models\Permission;

class NavbarService extends Service
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

        $menus = NavbarMenu::with([
                'meta:'.$selectedChildFields,
            ])
            ->orderBy('order')
            ->get();

        $permissions = Permission::select('name', 'guard_name')
            ->where('guard_name', 'web')
            ->get();

        $routeNameList = getWebRoutes();

        return compact(
            'menus', 'permissions', 'routeNameList'
        );
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

            $meta = NavbarMenuMeta::create($metaPayload);

            if ($meta) {
                $menuPayload = [
                    'name' => $request['name'],
                    'is_spacer' => $request['is_spacer'],
                    'meta_id' => $meta->id,
                    'order' => 0, // Default order, will be updated later
                ];

                $menuPayload['order'] = NavbarMenu::max('order') + 1;

                NavbarMenu::create($menuPayload);
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
    public function update(array $request, NavbarMenu $navbar): bool
    {
        try {
            $meta = $navbar->meta;

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

            $navbar->update([
                'name' => $request['name'],
                'is_spacer' => $request['is_spacer'],
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update menu: ' . $th->getMessage(), [
                'menu_id' => $navbar->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NavbarMenu $navbar): bool
    {
        try {
            $navbar->meta()->delete();
            return $navbar->delete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete menu: ' . $th->getMessage(), [
                'menu_id' => $navbar->id,
            ]);
            return false;
        }
    }
}
