<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NavbarMenuMetaFactory extends Factory
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
                'route'         => 'profile.account.index',
                'active_routes' => 'profile.account.index',
                'icon'          => 'user',
                'is_sortable'   => false,
            ],
            [
                'route'         => 'profile.setting.index',
                'active_routes' => 'profile.setting.index',
                'icon'          => 'cog',
                'is_sortable'   => false,
            ],
            [
                'route'         => null,
                'active_routes' => null,
                'icon'          => null,
            ],
            [
                'route'         => 'profile.security.index',
                'active_routes' => 'profile.security.index',
                'icon'          => 'lock-alt',
            ],
            [
                'route'         => 'profile.shortcut.index',
                'active_routes' => 'profile.shortcut.index',
                'icon'          => 'link-external',
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
                'icon' => null,
                'route' => null,
                'is_sortable' => true,
                'parameters' => [],
                'permissions' => [],
                'active_routes' => null,
            ], $item);

            $item['id'] = $uuids[$index];
            $item['permissions'] = json_encode($item['permissions']);
            $item['parameters'] = json_encode($item['parameters']);
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
