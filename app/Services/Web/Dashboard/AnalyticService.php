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
        private Models\InformationInterface $informationInterface
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function invoke(): array
    {
        $statuses = ReportType::toArray();
        $currentYear = Carbon::now()->year;
        $userIdStr = (string) auth('web')->id();
        $isRoleUser = isUserHasRole(config('rbac.role.default'));

        $startOfYear = Carbon::create($currentYear, 1, 1)->startOfDay();
        $endOfYear = Carbon::create($currentYear, 12, 31)->endOfDay();

        $reportsData = $this->reportInterface->all(
            ['id', 'status', 'user_id', 'created_at'],
            [],
            [
            ['created_at', '>=', $startOfYear],
            ['created_at', '<=', $endOfYear],
            ]
        ) ?? collect();

        if (!$isRoleUser) {
            $informationsList = $this->informationInterface->all(
                ['id', 'created_at'],
                [],
                [
                    ['created_at', '>=', $startOfYear],
                    ['created_at', '<=', $endOfYear],
                ]
            ) ?? collect();
        }

        $months = range(1, 12);
        $zeros = array_fill_keys($months, 0);

        $chartDataRaw = [];
        $myReportsRaw = [];

        foreach ($statuses as $status) {
            $chartDataRaw[$status] = $zeros;
            if ($isRoleUser) {
                $myReportsRaw[$status] = $zeros;
            }
        }

        if (!$isRoleUser) {
            $informationChartDataRaw = $zeros;
            foreach ($informationsList as $info) {
                $createdAt = Carbon::parse($info->created_at);
                if ($createdAt->year === $currentYear) {
                    $informationChartDataRaw[$createdAt->month]++;
                }
            }
        }

        foreach ($reportsData as $r) {
            $createdAt = Carbon::parse($r->created_at);
            if ($createdAt->year !== $currentYear) {
                continue;
            }

            $month = $createdAt->month;
            $status = (string) $r->status;

            if (!isset($chartDataRaw[$status])) {
                continue;
            }

            $chartDataRaw[$status][$month]++;

            if ($isRoleUser && (string) $r->user_id === $userIdStr) {
                $myReportsRaw[$status][$month]++;
            }
        }

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

        if ($isRoleUser) {
            $totalMyReports = array_sum(array_map('array_sum', $myReportsRaw));
            $myReports = $formatForChart($myReportsRaw);
        } else {
            $totalInformations = array_sum($informationChartDataRaw);
            $informations[] = [
                'name' => 'Informations',
                'data' => array_values($informationChartDataRaw),
            ];
        }

        $chartColorData = array_values([
            ReportType::CREATED->value    => '#f6ad55',
            ReportType::PROCCESSED->value => '#4299e1',
            ReportType::DENIED->value     => '#48bb78',
            ReportType::COMPLETED->value  => '#fc8181',
        ]);

        if ($isRoleUser) {
            return compact('isRoleUser', 'chartData', 'myReports', 'totalMyReports', 'chartColorData');
        } else {
            return compact('isRoleUser', 'chartData', 'informations', 'totalInformations', 'chartColorData');
        }
    }
}
