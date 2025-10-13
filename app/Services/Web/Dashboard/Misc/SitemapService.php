<?php

namespace App\Services\Web\Dashboard\Misc;

use App\Enums\SitemapChangeFreqEnum;
use App\Foundations\Service;
use App\Models\SitemapData;
use Illuminate\Support\Facades\Artisan;

class SitemapService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $changeFrequencies = SitemapChangeFreqEnum::cases();

        return compact('changeFrequencies');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): bool
    {
        try {
            Artisan::call('make:sitemap');

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        $sitemapData = new SitemapData();
        $sitemapData->fill($request);
        $sitemapData->save();

        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param int $id
     *
     * @return bool
     */
    public function update(array $request, SitemapData $sitemapData): bool
    {
        $sitemapData->fill($request);
        $sitemapData->save();

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(SitemapData $sitemapData): bool
    {
        return $sitemapData->delete();
    }
}
