<?php

namespace App\Services\Web\Dashboard\Menu;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Permission;
use App\Models\Shortcut;

class ShortcutService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $permissions = Permission::select('name', 'guard_name')
            ->where('guard_name', 'web')
            ->get();

        $routeNameList = getWebRoutes();
        $routeNameList = array_filter($routeNameList, fn($name) => str_starts_with($name, 'dashboard.') && str_ends_with($name, '.index'));

        return compact('permissions', 'routeNameList');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            Shortcut::create($request);
            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create shortcut: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Shortcut $shortcut): bool
    {
        try {
            if (empty($request['permissions'])) {
                $request['permissions'] = [];
            }

            $shortcut->update($request);
            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update shortcut: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut): bool
    {
        try {
            $shortcut->delete();
            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete shortcut: ' . $th->getMessage(), [
                'shortcut' => $shortcut,
            ]);
            return false;
        }
    }
}
