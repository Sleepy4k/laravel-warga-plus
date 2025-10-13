<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "creating" event.
     */
    public function creating(Article $article): void
    {
        $article->image = $article->image
            ? File::saveSingleFile(UploadFileType::ARTICLE, $article->image)
            : null;
    }

    /**
     * Handle the Article "updating" event.
     */
    public function updating(Article $article): void
    {
        if ($article->isDirty('image') && ($article->image != null || $article->image != '')) {
            $oldImage = $article->getOriginal('image', null);

            if ($oldImage == null) {
                $article->image = $article->image
                    ? File::saveSingleFile(UploadFileType::ARTICLE, $article->image)
                    : null;
            } else {
                $article->image = $article->image
                    ? File::updateSingleFile(UploadFileType::ARTICLE, $article->image, $oldImage)
                    : File::deleteFile(UploadFileType::ARTICLE, $oldImage);
            }
        }
    }

    /**
     * Handle the Article "deleting" event.
     */
    public function deleting(Article $article): void
    {
        $article->image
            ? File::deleteFile(UploadFileType::ARTICLE, $article->image)
            : null;
    }
}
