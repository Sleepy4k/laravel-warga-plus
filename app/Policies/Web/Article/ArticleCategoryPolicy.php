<?php

namespace App\Policies\Web\Article;

use App\Models\ArticleCategory;
use App\Models\User;

class ArticleCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('article.category.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ArticleCategory $articleCategory): bool
    {
        return $user->can('article.category.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('article.category.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ArticleCategory $articleCategory): bool
    {
        return $user->can('article.category.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ArticleCategory $articleCategory): bool
    {
        return $user->can('article.category.delete');
    }
}
