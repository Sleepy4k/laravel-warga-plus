<?php

namespace App\Policies\Web\Administration\Document;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;

class DocumentVersionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document, DocumentVersion $documentVersion): bool
    {
        if ($document->id !== $documentVersion->document_id) {
            return false;
        }

        return $user->can('document.version.download');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user, Document $document): bool
    {
        return $user->can('document.version.store');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document, DocumentVersion $documentVersion): bool
    {
        if ($document->id !== $documentVersion->document_id) {
            return false;
        }

        return $user->can('document.version.delete');
    }
}
