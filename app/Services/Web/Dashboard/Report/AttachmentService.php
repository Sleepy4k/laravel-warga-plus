<?php

namespace App\Services\Web\Dashboard\Report;

use App\Foundations\Service;
use App\Models\ReportAttachment;

class AttachmentService extends Service
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportAttachment $attachment): bool
    {
        return $attachment->delete();
    }
}
