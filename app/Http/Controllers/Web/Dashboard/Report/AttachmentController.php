<?php

namespace App\Http\Controllers\Web\Dashboard\Report;

use App\Foundations\Controller;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Services\Web\Dashboard\Report\AttachmentService;
use App\Traits\Authorizable;

class AttachmentController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private AttachmentService $service,
        private $policy = Report::class,
        private $abilities = [
            'destroy' => 'deleteAttachments',
        ]
    ) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportAttachment $attachment)
    {
        if (!$this->service->destroy($attachment)) {
            return $this->sendResponse(null, 'Failed to delete attachment. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Attachment successfully deleted.', 200);
    }
}
