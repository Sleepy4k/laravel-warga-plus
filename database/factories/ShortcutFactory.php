<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shortcut>
 */
class ShortcutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [
            [
                'icon' => 'home',
                'label' => 'Dashboard',
                'route' => 'dashboard.index',
                'description' => 'View the dashboard',
            ],
            [
                'icon' => 'cog',
                'label' => 'Application Settings',
                'route' => 'dashboard.settings.application.index',
                'description' => 'Manage application settings',
                'permissions' => ['setting.index'],
            ],
            [
                'icon' => 'cog',
                'label' => 'Uploader Settings',
                'route' => 'dashboard.settings.uploader.index',
                'description' => 'Manage uploader settings',
                'permissions' => ['setting.uploader.index'],
            ],
            [
                'icon' => 'archive',
                'label' => 'System Log',
                'route' => 'dashboard.log.system.index',
                'description' => 'View system logs',
                'permissions' => ['log.system.index'],
            ],
            [
                'icon' => 'archive',
                'label' => 'Auth Log',
                'route' => 'dashboard.log.auth.index',
                'description' => 'View auth logs',
                'permissions' => ['log.auth.index'],
            ],
            [
                'icon' => 'archive',
                'label' => 'Model Log',
                'route' => 'dashboard.log.model.index',
                'description' => 'View model logs',
                'permissions' => ['log.model.index'],
            ],
            [
                'icon' => 'memory-card',
                'label' => 'Database Backup',
                'route' => 'dashboard.misc.backup.index',
                'description' => 'Create and manage database backups',
                'permissions' => ['misc.backup.index'],
            ],
        ];

        $currentTime = now();
        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        foreach ($data as $index => &$item) {
            $item = array_merge([
                'permissions' => [],
            ], $item);

            $item['id'] = $uuids[$index];
            $item['permissions'] = json_encode($item['permissions']);
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
