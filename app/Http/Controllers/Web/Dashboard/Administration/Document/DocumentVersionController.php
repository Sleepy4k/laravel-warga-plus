<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Document;

use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Document\Version\StoreRequest;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Services\Web\Dashboard\Administration\Document\DocumentVersionService;
use App\Traits\Authorizable;

class DocumentVersionController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DocumentVersionService $service,
        private $policy = DocumentVersion::class,
        private $abilities = [
            'store' => 'store',
            'show' => 'view',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Document $document, StoreRequest $request)
    {
        if (!$this->service->store($document, $request->validated())) {
            return $this->sendResponse(null, 'Failed to create document. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Document successfully created.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document, DocumentVersion $version)
    {
        $file = $this->service->show($version);
        if (empty($file)) {
            abort(404, 'Document file not found');
        }

        return response()->stream(function () use ($file) {
            echo $file['file'];
        }, 200, [
            'Content-Type' => $file['type'],
            'Content-Disposition' => 'attachment; filename="' . $file['name'] . '"',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document, DocumentVersion $version)
    {
        if (!$this->service->destroy($version)) {
            return $this->sendResponse(null, 'Failed to delete document. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Document successfully deleted.', 200);
    }
}
