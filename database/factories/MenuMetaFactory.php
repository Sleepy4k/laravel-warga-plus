<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuMeta>
 */
class MenuMetaFactory extends Factory
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
                'route'         => null,
                'permissions'   => ['dashboard.menu'],
                'icon'          => 'home-circle',
                'is_sortable'   => false,
            ],
            [
                'route'         => 'dashboard.index',
                'permissions'   => ['dashboard.index'],
                'active_routes' => 'dashboard.index',
            ],
            [
                'permissions'   => ['product.menu', 'article.menu', 'activity.menu'],
            ],
            [
                'permissions'   => ['product.menu'],
                'icon'          => 'box',
            ],
            [
                'route'         => 'dashboard.product.index',
                'permissions'   => ['product.index'],
                'active_routes' => 'dashboard.product.index',
            ],
            [
                'route'         => 'dashboard.product.category.index',
                'permissions'   => ['product.category.index'],
                'active_routes' => 'dashboard.product.category.index',
            ],
            [
                'permissions'   => ['article.menu'],
                'icon'          => 'detail',
            ],
            [
                'route'         => 'dashboard.article.index',
                'permissions'   => ['article.index'],
                'active_routes' => 'dashboard.article.index',
            ],
            [
                'route'         => 'dashboard.article.category.index',
                'permissions'   => ['article.category.index'],
                'active_routes' => 'dashboard.article.category.index',
            ],
            [
                'permissions'   => ['activity.menu'],
                'icon'          => 'calendar',
            ],
            [
                'route'         => 'dashboard.activity.index',
                'permissions'   => ['activity.index'],
                'active_routes' => 'dashboard.activity.index',
            ],
            [
                'route'         => 'dashboard.activity.category.index',
                'permissions'   => ['activity.category.index'],
                'active_routes' => 'dashboard.activity.category.index',
            ],
            [
                'permissions'   => ['letter_transaction.menu', 'book_agenda.menu', 'document.menu', 'letter_references.menu'],
            ],
            [
                'permissions'   => ['document.menu'],
                'icon'          => 'book-content',
            ],
            [
                'route'         => 'dashboard.document.index',
                'permissions'   => ['document.index'],
                'active_routes' => 'dashboard.document.index',
            ],
            [
                'route'         => 'dashboard.document.category.index',
                'permissions'   => ['document.category.index'],
                'active_routes' => 'dashboard.document.category.index',
            ],
            [
                'route'         => 'dashboard.document.gallery.index',
                'permissions'   => ['document.gallery.index'],
                'active_routes' => 'dashboard.document.gallery.index',
            ],
            [
                'permissions'   => ['letter_transaction.menu'],
                'icon'          => 'mail-send',
            ],
            [
                'route'         => 'dashboard.letter.transaction.incoming.index',
                'permissions'   => ['letter_transaction.incoming.index'],
                'active_routes' => 'dashboard.letter.transaction.incoming.index',
            ],
            [
                'route'         => 'dashboard.letter.transaction.outgoing.index',
                'permissions'   => ['letter_transaction.outgoing.index'],
                'active_routes' => 'dashboard.letter.transaction.outgoing.index',
            ],
            [
                'permissions'   => ['book_agenda.menu'],
                'icon'          => 'book',
            ],
            [
                'route'         => 'dashboard.letter.agenda.incoming.index',
                'permissions'   => ['book_agenda.incoming.index'],
                'active_routes' => 'dashboard.letter.agenda.incoming.index',
            ],
            [
                'route'         => 'dashboard.letter.agenda.outgoing.index',
                'permissions'   => ['book_agenda.outgoing.index'],
                'active_routes' => 'dashboard.letter.agenda.outgoing.index',
            ],
            [
                'permissions'   => ['letter_references.menu'],
                'icon'          => 'analyse',
            ],
            [
                'route'         => 'dashboard.letter.reference.classification.index',
                'permissions'   => ['letter_references.classification.index'],
                'active_routes' => 'dashboard.letter.reference.classification.index',
            ],
            [
                'route'         => 'dashboard.letter.reference.status.index',
                'permissions'   => ['letter_references.status.index'],
                'active_routes' => 'dashboard.letter.reference.status.index',
            ],
            [
                'permissions'   => ['user.menu', 'rbac.menu'],
            ],
            [
                'permissions'   => ['user.menu'],
                'icon'          => 'user',
            ],
            [
                'route'         => 'dashboard.user.index',
                'permissions'   => ['user.index'],
                'active_routes' => 'dashboard.user.index',
            ],
            [
                'permissions'   => ['rbac.menu'],
                'icon'          => 'check-shield',
            ],
            [
                'route'         => 'dashboard.rbac.role.index',
                'permissions'   => ['rbac.role.index'],
                'active_routes' => 'dashboard.rbac.role.index',
            ],
            [
                'route'         => 'dashboard.rbac.permission.index',
                'permissions'   => ['rbac.permission.index'],
                'active_routes' => 'dashboard.rbac.permission.index',
            ],
            [
                'permissions'   => ['setting.menu', 'menu.menu', 'log.menu', 'misc.menu'],
            ],
            [
                'permissions'   => ['setting.menu'],
                'icon'          => 'cog',
            ],
            [
                'route'         => 'dashboard.settings.application.index',
                'permissions'   => ['setting.index'],
                'active_routes' => 'dashboard.settings.application.index',
            ],
            [
                'route'         => 'dashboard.settings.uploader.index',
                'permissions'   => ['setting.uploader.index'],
                'active_routes' => 'dashboard.settings.uploader.index',
            ],
            [
                'permissions'   => ['menu.menu'],
                'icon'          => 'menu',
            ],
            [
                'route'         => 'dashboard.menu.sidebar.index',
                'permissions'   => ['menu.sidebar.index'],
                'active_routes' => 'dashboard.menu.sidebar.index',
            ],
            [
                'route'         => 'dashboard.menu.navbar.index',
                'permissions'   => ['menu.navbar.index'],
                'active_routes' => 'dashboard.menu.navbar.index',
            ],
            [
                'route'         => 'dashboard.menu.shortcut.index',
                'permissions'   => ['menu.shortcut.index'],
                'active_routes' => 'dashboard.menu.shortcut.index',
            ],
            [
                'permissions'   => ['log.menu'],
                'icon'          => 'archive',
            ],
            [
                'route'         => 'dashboard.log.auth.index',
                'permissions'   => ['log.auth.index'],
                'active_routes' => 'dashboard.log.auth.index',
            ],
            [
                'route'         => 'dashboard.log.model.index',
                'permissions'   => ['log.model.index'],
                'active_routes' => 'dashboard.log.model.index',
            ],
            [
                'route'         => 'dashboard.log.system.index',
                'permissions'   => ['log.system.index'],
                'active_routes' => 'dashboard.log.system.index',
            ],
            [
                'route'         => 'dashboard.log.query.index',
                'permissions'   => ['log.query.index'],
                'active_routes' => 'dashboard.log.query.index',
            ],
            [
                'route'         => 'dashboard.log.cache.index',
                'permissions'   => ['log.cache.index'],
                'active_routes' => 'dashboard.log.cache.index',
            ],
            [
                'permissions'   => ['misc.menu'],
                'icon'          => 'memory-card',
            ],
            [
                'route'         => 'dashboard.misc.sitemap.index',
                'permissions'   => ['misc.sitemap.index'],
                'active_routes' => 'dashboard.misc.sitemap.index',
            ],
            [
                'route'         => 'dashboard.misc.backup.index',
                'permissions'   => ['misc.backup.index'],
                'active_routes' => 'dashboard.misc.backup.index',
            ],
            [
                'route'         => 'dashboard.misc.job.index',
                'permissions'   => ['misc.job.index'],
                'active_routes' => 'dashboard.misc.job.index',
            ],
        ];

        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        $currentTime = now();

        foreach ($data as $index => &$item) {
            $item = array_merge([
                'icon' => null,
                'route' => null,
                'is_sortable' => true,
                'parameters' => [],
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
