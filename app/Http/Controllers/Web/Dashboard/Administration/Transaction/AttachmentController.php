<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Transaction;

use App\Foundations\Controller;
use App\Models\LetterAttachment;
use App\Policies\Web\Administration\Transaction\IncomingPolicy;
use App\Services\Web\Dashboard\Administration\Transaction\AttachmentService;
use App\Traits\Authorizable;

class AttachmentController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private AttachmentService $service,
        private $policy = IncomingPolicy::class,
        private $abilities = [
            'destroy' => 'update',
        ]
    ) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterAttachment $attachment)
    {
        if (!$this->service->destroy($attachment)) {
            return $this->sendResponse(null, 'Failed to delete attachment. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Attachment successfully deleted.', 200);
    }
}
