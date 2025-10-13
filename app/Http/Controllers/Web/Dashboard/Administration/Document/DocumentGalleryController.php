<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Document;

use App\Foundations\Controller;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Policies\Web\Administration\Document\DocumentGalleryPolicy;
use App\Services\Web\Dashboard\Administration\Document\DocumentGalleryService;
use App\Traits\Authorizable;

class DocumentGalleryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DocumentGalleryService $service,
        private $policy = DocumentGalleryPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'show' => 'view',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.administration.document.gallery.index', $this->service->index());
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
}
