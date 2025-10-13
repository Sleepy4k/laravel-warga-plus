<?php

namespace App\Http\Controllers\Web\Dashboard\Misc;

use App\DataTables\Misc\SitemapDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Misc\SitemapRequest;
use App\Models\SitemapData;
use App\Services\Web\Dashboard\Misc\SitemapService;
use App\Traits\Authorizable;

class SitemapController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SitemapService $service,
        private $policy = SitemapData::class,
        private $abilities = [
            'index' => 'viewAny',
            'create' => 'create',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(SitemapDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.misc.sitemap.index', $this->service->index());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!$this->service->create()) {
            return $this->sendResponse(null, 'Failed to create sitemap.', 500);
        }

        return $this->sendResponse(null, 'Sitemap created successfully.', 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SitemapRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to store sitemap data.', 500);
        }

        return $this->sendResponse(null, 'Sitemap data stored successfully.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SitemapRequest $request, SitemapData $sitemapData)
    {
        if (!$this->service->update($request->validated(), $sitemapData)) {
            return $this->sendResponse(null, 'Failed to update sitemap data.', 500);
        }

        return $this->sendResponse(null, 'Sitemap data updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SitemapData $sitemapData)
    {
        if (!$this->service->destroy($sitemapData)) {
            return $this->sendResponse(null, 'Failed to delete sitemap data.', 500);
        }

        return $this->sendResponse(null, 'Sitemap data deleted successfully.', 200);
    }
}
