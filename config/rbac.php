<?php

return [
    /* Configurations for application */
    'role' => [
        // Default role assigned to users when they are created
        // This should match the default role in your RBAC configuration
        'default' => 'user',

        // Highest role in the hierarchy, typically for administrators
        // This should match the highest role in your RBAC configuration
        // It is used to determine the highest level of access in the system
        'highest' => 'admin',
    ],

    /* List of roles and permissions */
    'list' => [
        'roles' => [
            'admin',
            'pengurus',
            'user',
        ],
        'permissions' => [
            'dashboard.menu',
            'dashboard.index',

            'letter_transaction.menu',

            'letter_transaction.incoming.index',
            'letter_transaction.incoming.create',
            'letter_transaction.incoming.store',
            'letter_transaction.incoming.show',
            'letter_transaction.incoming.edit',
            'letter_transaction.incoming.update',
            'letter_transaction.incoming.delete',

            'letter_transaction.outgoing.index',
            'letter_transaction.outgoing.create',
            'letter_transaction.outgoing.store',
            'letter_transaction.outgoing.show',
            'letter_transaction.outgoing.edit',
            'letter_transaction.outgoing.update',
            'letter_transaction.outgoing.delete',

            'letter_transaction.disposition.index',
            'letter_transaction.disposition.create',
            'letter_transaction.disposition.store',
            'letter_transaction.disposition.edit',
            'letter_transaction.disposition.update',
            'letter_transaction.disposition.delete',

            'book_agenda.menu',

            'book_agenda.incoming.index',

            'book_agenda.outgoing.index',

            'document.menu',

            'document.index',
            'document.create',
            'document.store',
            'document.show',
            'document.edit',
            'document.update',
            'document.delete',

            'document.category.index',
            'document.category.create',
            'document.category.store',
            'document.category.show',
            'document.category.edit',
            'document.category.update',
            'document.category.delete',

            'document.version.create',
            'document.version.store',
            'document.version.show',
            'document.version.delete',
            'document.version.download',

            'document.gallery.index',
            'document.gallery.show',

            'letter_references.menu',

            'letter_references.classification.index',
            'letter_references.classification.create',
            'letter_references.classification.store',
            'letter_references.classification.show',
            'letter_references.classification.edit',
            'letter_references.classification.update',
            'letter_references.classification.delete',

            'letter_references.status.index',
            'letter_references.status.create',
            'letter_references.status.store',
            'letter_references.status.show',
            'letter_references.status.edit',
            'letter_references.status.update',
            'letter_references.status.delete',

            'user.menu',

            'user.index',
            'user.create',
            'user.store',
            'user.show',
            'user.edit',
            'user.update',
            'user.delete',

            'rbac.menu',

            'rbac.permission.index',
            'rbac.permission.create',
            'rbac.permission.store',
            'rbac.permission.show',
            'rbac.permission.edit',
            'rbac.permission.update',
            'rbac.permission.delete',

            'rbac.role.index',
            'rbac.role.create',
            'rbac.role.store',
            'rbac.role.show',
            'rbac.role.edit',
            'rbac.role.update',
            'rbac.role.delete',

            'log.menu',

            'log.auth.index',
            'log.auth.show',
            'log.auth.store',

            'log.model.index',
            'log.model.show',
            'log.model.store',

            'log.query.index',
            'log.query.show',
            'log.query.store',

            'log.system.index',
            'log.system.show',
            'log.system.store',

            'log.cache.index',
            'log.cache.show',
            'log.cache.store',

            'setting.menu',

            'setting.index',
            'setting.store',
            'setting.update',

            'setting.uploader.index',
            'setting.uploader.store',

            'misc.menu',

            'misc.sitemap.index',
            'misc.sitemap.create',
            'misc.sitemap.store',
            'misc.sitemap.show',
            'misc.sitemap.edit',
            'misc.sitemap.update',
            'misc.sitemap.delete',

            'misc.backup.index',
            'misc.backup.store',
            'misc.backup.show',
            'misc.backup.delete',

            'misc.job.index',
            'misc.job.store',

            'menu.menu',

            'menu.sidebar.index',
            'menu.sidebar.create',
            'menu.sidebar.store',
            'menu.sidebar.show',
            'menu.sidebar.edit',
            'menu.sidebar.update',
            'menu.sidebar.delete',

            'menu.navbar.index',
            'menu.navbar.create',
            'menu.navbar.store',
            'menu.navbar.show',
            'menu.navbar.edit',
            'menu.navbar.update',
            'menu.navbar.delete',

            'menu.shortcut.index',
            'menu.shortcut.create',
            'menu.shortcut.store',
            'menu.shortcut.show',
            'menu.shortcut.edit',
            'menu.shortcut.update',
            'menu.shortcut.delete',
        ],
    ],

    /* Roles that can assign other roles */
    'assign' => [

    ],

    /* Permissions for each role */
    'permissions' => [
        'admin' => 'all',
        'pengurus' => [
            'dashboard.menu',
            'dashboard.index',

            'letter_transaction.menu',

            'letter_transaction.incoming.index',
            'letter_transaction.incoming.create',
            'letter_transaction.incoming.store',
            'letter_transaction.incoming.show',
            'letter_transaction.incoming.edit',
            'letter_transaction.incoming.update',
            'letter_transaction.incoming.delete',

            'letter_transaction.outgoing.index',
            'letter_transaction.outgoing.create',
            'letter_transaction.outgoing.store',
            'letter_transaction.outgoing.show',
            'letter_transaction.outgoing.edit',
            'letter_transaction.outgoing.update',
            'letter_transaction.outgoing.delete',

            'letter_transaction.disposition.index',
            'letter_transaction.disposition.create',
            'letter_transaction.disposition.store',
            'letter_transaction.disposition.edit',
            'letter_transaction.disposition.update',
            'letter_transaction.disposition.delete',

            'book_agenda.menu',

            'book_agenda.incoming.index',

            'book_agenda.outgoing.index',

            'document.menu',

            'document.index',
            'document.create',
            'document.store',
            'document.show',
            'document.edit',
            'document.update',
            'document.delete',

            'document.category.index',
            'document.category.create',
            'document.category.store',
            'document.category.show',
            'document.category.edit',
            'document.category.update',
            'document.category.delete',

            'document.version.create',
            'document.version.store',
            'document.version.show',
            'document.version.delete',
            'document.version.download',

            'document.gallery.index',
            'document.gallery.show',

            'letter_references.menu',

            'letter_references.classification.index',
            'letter_references.classification.create',
            'letter_references.classification.store',
            'letter_references.classification.show',
            'letter_references.classification.edit',
            'letter_references.classification.update',
            'letter_references.classification.delete',

            'letter_references.status.index',
            'letter_references.status.create',
            'letter_references.status.store',
            'letter_references.status.show',
            'letter_references.status.edit',
            'letter_references.status.update',
            'letter_references.status.delete',

            'user.menu',

            'user.index',
            'user.create',
            'user.store',
            'user.show',
            'user.edit',
            'user.update',
            'user.delete',
        ],
        'user' => [
            'dashboard.menu',
            'dashboard.index',
        ],
    ],
];
