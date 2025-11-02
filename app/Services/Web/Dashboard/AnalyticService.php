<?php

namespace App\Services\Web\Dashboard;

use App\Contracts\Models;
use App\Enums\ReportType;
use App\Foundations\Service;
use Carbon\Carbon;

class AnalyticService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ReportInterface $reportInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function invoke(): array
    {
        $reportsData = $this->reportInterface->all(['id', 'status', 'user_id', 'created_at']);

        $statuses = ReportType::toArray();
        $currentYear = Carbon::now()->year;
        $userId = auth('web')->id();

        $months = range(1, 12);
        $zeros = array_combine($months, array_fill(0, count($months), 0));

        $chartDataRaw = $myReportsRaw = [];
        foreach ($statuses as $status) {
            $chartDataRaw[$status] = $zeros;
            $myReportsRaw[$status] = $zeros;
        }

        foreach ($reportsData as $r) {
            $createdAt = Carbon::parse($r->created_at);
            if ($createdAt->year !== $currentYear) {
                continue;
            }

            $month = $createdAt->month;
            $status = (string) $r->status;

            if (!isset($chartDataRaw[$status])) continue;

            $chartDataRaw[$status][$month]++;

            if ((string) $r->user_id === (string) $userId) {
                $myReportsRaw[$status][$month]++;
            }
        }

        $totalMyReports = array_sum(array_map('array_sum', $myReportsRaw));

        $formatForChart = function (array $raw) {
            $out = [];
            foreach ($raw as $status => $data) {
                $out[] = [
                    'name' => ucfirst(strtolower($status)),
                    'data' => array_values($data),
                ];
            }
            return $out;
        };

        $chartData = $formatForChart($chartDataRaw);
        $myReports = $formatForChart($myReportsRaw);

        $chartColorData = array_values([
            ReportType::CREATED->value    => '#f6ad55',
            ReportType::PROCCESSED->value => '#4299e1',
            ReportType::DENIED->value     => '#48bb78',
            ReportType::COMPLETED->value  => '#fc8181',
        ]);

        return compact('chartData', 'myReports', 'totalMyReports', 'chartColorData');
    }
}
