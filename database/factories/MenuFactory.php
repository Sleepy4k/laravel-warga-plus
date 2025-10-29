<?php

namespace Database\Factories;

use App\Models\MenuMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parentMenus = [
            'dashboard'             => ['name' => 'Dashboard'],
            'spacer_resident'       => ['name' => 'Resident'],
            'reports'               => ['name' => 'Reports'],
            'spacer_secretary'      => ['name' => 'Administration'],
            'documents'             => ['name' => 'Documents'],
            'letter_transaction'    => ['name' => 'Letter Transactions'],
            'book_agenda'           => ['name' => 'Book Agenda'],
            'letter_references'     => ['name' => 'Letter References'],
            'spacer_admin'          => ['name' => 'User Management'],
            'user'                  => ['name' => 'Users'],
            'rbac'                  => ['name' => 'RBAC'],
            'spacer_control'        => ['name' => 'Control Panel'],
            'settings'              => ['name' => 'Settings'],
            'menu'                  => ['name' => 'Menus'],
            'log'                   => ['name' => 'System Logs'],
            'miscs'                 => ['name' => 'Miscellaneous'],
        ];

        $order = 1;

        foreach ($parentMenus as &$menu) {
            $menu['id'] = \Illuminate\Support\Str::uuid();
            $menu['order'] = $order++;
        }

        unset($menu);

        $data = [
            [
                'id'        => $parentMenus['dashboard']['id'],
                'name'      => $parentMenus['dashboard']['name'],
                'order'     => $parentMenus['dashboard']['order'],
            ],
            [
                'name'      => 'Home',
                'order'     => 1,
                'parent_id' => $parentMenus['dashboard']['id'],
            ],
            [
                'id'        => $parentMenus['spacer_resident']['id'],
                'name'      => $parentMenus['spacer_resident']['name'],
                'order'     => $parentMenus['spacer_resident']['order'],
                'is_spacer' => true,
            ],
            [
                'id'        => $parentMenus['reports']['id'],
                'name'      => $parentMenus['reports']['name'],
                'order'     => $parentMenus['reports']['order'],
            ],
            [
                'name'      => 'Reports',
                'order'     => 1,
                'parent_id' => $parentMenus['reports']['id'],
            ],
            [
                'name'      => 'Report Categories',
                'order'     => 2,
                'parent_id' => $parentMenus['reports']['id'],
            ],
            [
                'id'        => $parentMenus['spacer_secretary']['id'],
                'name'      => $parentMenus['spacer_secretary']['name'],
                'order'     => $parentMenus['spacer_secretary']['order'],
                'is_spacer' => true,
            ],
            [
                'id'        => $parentMenus['documents']['id'],
                'name'      => $parentMenus['documents']['name'],
                'order'     => $parentMenus['documents']['order'],
            ],
            [
                'name'      => 'Documents',
                'order'     => 1,
                'parent_id' => $parentMenus['documents']['id'],
            ],
            [
                'name'      => 'Document Categories',
                'order'     => 2,
                'parent_id' => $parentMenus['documents']['id'],
            ],
            [
                'name'      => 'Document Gallery',
                'order'     => 3,
                'parent_id' => $parentMenus['documents']['id'],
            ],
            [
                'id'        => $parentMenus['letter_transaction']['id'],
                'name'      => $parentMenus['letter_transaction']['name'],
                'order'     => $parentMenus['letter_transaction']['order'],
            ],
            [
                'name'      => 'Incoming Letters',
                'order'     => 1,
                'parent_id' => $parentMenus['letter_transaction']['id'],
            ],
            [
                'name'      => 'Outgoing Letters',
                'order'     => 2,
                'parent_id' => $parentMenus['letter_transaction']['id'],
            ],
            [
                'id'        => $parentMenus['book_agenda']['id'],
                'name'      => $parentMenus['book_agenda']['name'],
                'order'     => $parentMenus['book_agenda']['order'],
            ],
            [
                'name'      => 'Incoming Letters',
                'order'     => 1,
                'parent_id' => $parentMenus['book_agenda']['id'],
            ],
            [
                'name'      => 'Outgoing Letters',
                'order'     => 2,
                'parent_id' => $parentMenus['book_agenda']['id'],
            ],
            [
                'id'        => $parentMenus['letter_references']['id'],
                'name'      => $parentMenus['letter_references']['name'],
                'order'     => $parentMenus['letter_references']['order'],
            ],
            [
                'name'      => 'Letter Classification',
                'order'     => 1,
                'parent_id' => $parentMenus['letter_references']['id'],
            ],
            [
                'name'      => 'Letter Status',
                'order'     => 2,
                'parent_id' => $parentMenus['letter_references']['id'],
            ],
            [
                'id'        => $parentMenus['spacer_admin']['id'],
                'name'      => $parentMenus['spacer_admin']['name'],
                'order'     => $parentMenus['spacer_admin']['order'],
                'is_spacer' => true,
            ],
            [
                'id'        => $parentMenus['user']['id'],
                'name'      => $parentMenus['user']['name'],
                'order'     => $parentMenus['user']['order'],
            ],
            [
                'name'      => 'List',
                'order'     => 1,
                'parent_id' => $parentMenus['user']['id'],
            ],
            [
                'id'        => $parentMenus['rbac']['id'],
                'name'      => $parentMenus['rbac']['name'],
                'order'     => $parentMenus['rbac']['order'],
            ],
            [
                'name'      => 'Roles',
                'order'     => 1,
                'parent_id' => $parentMenus['rbac']['id'],
            ],
            [
                'name'      => 'Permissions',
                'order'     => 2,
                'parent_id' => $parentMenus['rbac']['id'],
            ],
            [
                'id'        => $parentMenus['spacer_control']['id'],
                'name'      => $parentMenus['spacer_control']['name'],
                'order'     => $parentMenus['spacer_control']['order'],
                'is_spacer' => true,
            ],
            [
                'id'        => $parentMenus['settings']['id'],
                'name'      => $parentMenus['settings']['name'],
                'order'     => $parentMenus['settings']['order'],
            ],
            [
                'name'      => 'Application',
                'order'     => 1,
                'parent_id' => $parentMenus['settings']['id'],
            ],
            [
                'name'      => 'File Upload',
                'order'     => 2,
                'parent_id' => $parentMenus['settings']['id'],
            ],
            [
                'id'        => $parentMenus['menu']['id'],
                'name'      => $parentMenus['menu']['name'],
                'order'     => $parentMenus['menu']['order'],
            ],
            [
                'name'      => 'Sidebar',
                'order'     => 1,
                'parent_id' => $parentMenus['menu']['id'],
            ],
            [
                'name'      => 'Navbar',
                'order'     => 2,
                'parent_id' => $parentMenus['menu']['id'],
            ],
            [
                'name'      => 'Shortcut',
                'order'     => 3,
                'parent_id' => $parentMenus['menu']['id'],
            ],
            [
                'id'        => $parentMenus['log']['id'],
                'name'      => $parentMenus['log']['name'],
                'order'     => $parentMenus['log']['order'],
            ],
            [
                'name'      => 'Authentication Logs',
                'order'     => 1,
                'parent_id' => $parentMenus['log']['id'],
            ],
            [
                'name'      => 'Model Logs',
                'order'     => 2,
                'parent_id' => $parentMenus['log']['id'],
            ],
            [
                'name'      => 'System Logs',
                'order'     => 3,
                'parent_id' => $parentMenus['log']['id'],
            ],
            [
                'name'      => 'Database Query Logs',
                'order'     => 4,
                'parent_id' => $parentMenus['log']['id'],
            ],
            [
                'name'      => 'Database Cache Logs',
                'order'     => 5,
                'parent_id' => $parentMenus['log']['id'],
            ],
            [
                'id'        => $parentMenus['miscs']['id'],
                'name'      => $parentMenus['miscs']['name'],
                'order'     => $parentMenus['miscs']['order'],
            ],
            [
                'name'      => 'Sitemap',
                'order'     => 1,
                'parent_id' => $parentMenus['miscs']['id'],
            ],
            [
                'name'      => 'Database Backup',
                'order'     => 2,
                'parent_id' => $parentMenus['miscs']['id'],
            ],
            [
                'name'      => 'Jobs Monitoring',
                'order'     => 3,
                'parent_id' => $parentMenus['miscs']['id'],
            ],
        ];

        $currentTime = now();
        $menuMetaIds = MenuMeta::pluck('id')->toArray();

        foreach ($data as $index => &$item) {
            $item = array_merge([
                'id' => $item['id'] ?? \Illuminate\Support\Str::uuid(),
                'is_spacer' => false,
                'parent_id' => $item['parent_id'] ?? null,
            ], $item);

            $item['meta_id'] = $menuMetaIds[$index] ?? null;
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
