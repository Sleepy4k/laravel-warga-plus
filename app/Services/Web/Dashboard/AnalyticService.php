<?php

namespace App\Services\Web\Dashboard;

use App\Contracts\Models;
use App\Foundations\Service;
use Carbon\Carbon;

class AnalyticService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ArticleInterface $articleInterface,
        private Models\ProductInterface $productInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function invoke(): array
    {
        $totalProducts = collect(
            $this->productInterface->all(
            ['id', 'created_at'],
            [],
            [['created_at', '>=', now()->startOfYear()], ['user_id', '=', auth('web')->id()]]
            ) ?: []
        );

        $totalArticles = collect(
            $this->articleInterface->all(
                ['id', 'created_at'],
                [],
                [['created_at', '>=', now()->startOfYear()], ['author_id', '=', auth('web')->id()]]
            ) ?: []
        );

        /**
         * @var \Illuminate\Database\Eloquent\Collection $totalProducts
         */
        $productsPerMonth = $totalProducts
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format('n'))
            ->mapWithKeys(fn($items, $month) => [(int)$month => $items->count()]);

        /**
         * @var \Illuminate\Database\Eloquent\Collection $totalArticles
         */
        $articlesPerMonth = $totalArticles
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format('n'))
            ->mapWithKeys(fn($items, $month) => [(int)$month => $items->count()]);

        $productsPerMonth = collect(range(1, 12))
            ->map(fn($month) => $productsPerMonth->get($month, 0))
            ->values()
            ->toArray();

        $articlesPerMonth = collect(range(1, 12))
            ->map(fn($month) => $articlesPerMonth->get($month, 0))
            ->values()
            ->toArray();

        return [
            'totalArticles' => $totalArticles->count(),
            'productChartData' => json_encode($productsPerMonth),
            'articleChartData' => json_encode($articlesPerMonth),
        ];
    }
}
