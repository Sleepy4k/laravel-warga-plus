<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Permission::query()->count() > 0) return;

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = config('rbac.list.permissions');

        if (empty($permissions)) {
            throw new \Exception('Error: config/rbac.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        $permission = collect($permissions)->map(function ($name) {
            return [
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        Permission::insert($permission->toArray());
    }
}
