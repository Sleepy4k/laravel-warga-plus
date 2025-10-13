<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\ProductDetail;

class ProductDetailObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(ProductDetail $productDetail): void
    {
        $productDetail->image_url = $productDetail->image_url
            ? File::saveSingleFile(UploadFileType::PRODUCT, $productDetail->image_url)
            : null;
    }

    /**
     * Handle the Product "updating" event.
     */
    public function updating(ProductDetail $productDetail): void
    {
        if ($productDetail->isDirty('image_url') && ($productDetail->image_url != null || $productDetail->image_url != '')) {
            $oldImage = $productDetail->getOriginal('image_url', null);

            if ($oldImage == null) {
                $productDetail->image_url = $productDetail->image_url
                    ? File::saveSingleFile(UploadFileType::PRODUCT, $productDetail->image_url)
                    : null;
            } else {
                $productDetail->image_url = $productDetail->image_url
                    ? File::updateSingleFile(UploadFileType::PRODUCT, $productDetail->image_url, $oldImage)
                    : File::deleteFile(UploadFileType::PRODUCT, $oldImage);
            }
        }
    }

    /**
     * Handle the Product "deleting" event.
     */
    public function deleting(ProductDetail $productDetail): void
    {
        $productDetail->image_url
            ? File::deleteFile(UploadFileType::PRODUCT, $productDetail->image_url)
            : null;
    }
}
