<?php

namespace App\Services\Web\Landing;

use App\Enums\ReportType;
use App\Foundations\Service;
use App\Models\Report;
use App\Models\ReportCategory;

class ReportService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(array $params): array
    {
        $statuses = ReportType::cases();
        $types = ReportCategory::select('id', 'name')->where('name', '!=', 'lainnya')->get();

        $search = $params['search'] ?? null;
        $type = $params['type'] ?? null;
        $status = $params['status'] ?? null;

        $reports = Report::when($type, function ($query, $type) {
            $query->where('category_id', $type);
        })->when($status, function ($query, $status) {
            $query->where('status', $status);
        })->latest()->with('category:id,name', 'user:id', 'user.personal:id,user_id,first_name,last_name')->paginate(9)->withQueryString();

        if ($search) {
            $reports = $reports->filter(function ($report) use ($search) {
                return str_contains(strtolower($report->title), strtolower($search))
                    || str_contains(strtolower($report->content), strtolower($search))
                    || str_contains(strtolower($report->location), strtolower($search));
            });
            $reports = Report::setPaginateFromCollection($reports, 9);
        }

        $reportIcons = [];
        foreach (ReportType::cases() as $type) {
            $reportIcons[$type->value] = [
                'icon' => $type->icon(),
                'text' => $type->label(),
                'bg' => $type->bgColor(),
            ];
        }

        return compact('statuses', 'types', 'reports', 'reportIcons');
    }
}
