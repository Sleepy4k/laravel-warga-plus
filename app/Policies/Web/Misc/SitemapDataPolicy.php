<?php

namespace App\Policies\Web\Misc;

use App\Models\SitemapData;
use App\Models\User;

class SitemapDataPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('misc.sitemap.index');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('misc.sitemap.create');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('misc.sitemap.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SitemapData $sitemapData): bool
    {
        return $user->can('misc.sitemap.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SitemapData $sitemapData): bool
    {
        return $user->can('misc.sitemap.delete');
    }
}
