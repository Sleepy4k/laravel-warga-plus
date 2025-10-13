<?php

namespace App\Services\Web\Dashboard\Administration\Transaction;

use App\Foundations\Service;
use App\Models\LetterAttachment;

class AttachmentService extends Service
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterAttachment $attachment): bool
    {
        return $attachment->delete();
    }
}
