<?php

namespace App\Policies\Web\Administration\Document;

use App\Models\User;

class DocumentGalleryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('document.gallery.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('document.gallery.show');
    }
}
