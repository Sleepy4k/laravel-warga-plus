<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Document;

use App\DataTables\Administration\Document\DocumentDataTable;
use App\DataTables\Administration\Document\DocumentVersionDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Document\StoreRequest;
use App\Http\Requests\Web\Dashboard\Administration\Document\UpdateRequest;
use App\Models\Document;
use App\Services\Web\Dashboard\Administration\Document\DocumentService;
use App\Traits\Authorizable;

class DocumentController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DocumentService $service,
        private $policy = Document::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(DocumentDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.administration.document.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create document. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getDocumentStats(), 'Document successfully created.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentVersionDataTable $dataTable, Document $document)
    {
        $dataTable->setDocumentId($document->id);
        return $dataTable->render('pages.dashboard.administration.document.detail', $this->service->show($document));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Document $document)
    {
        if (!$this->service->update($request->validated(), $document)) {
            return $this->sendResponse(null, 'Failed to update document. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getDocumentStats(), 'Document successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if (!$this->service->destroy($document)) {
            return $this->sendResponse(null, 'Failed to delete document. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getDocumentStats(), 'Document successfully deleted.', 200);
    }
}
