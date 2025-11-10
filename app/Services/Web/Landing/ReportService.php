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

        $reports = Report::when($search, function ($query, $search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        })->when($type, function ($query, $type) {
            $query->where('category_id', $type);
        })->when($status, function ($query, $status) {
            $query->where('status', $status);
        })->latest()->with('category:id,name', 'user:id', 'user.personal:id,user_id,first_name,last_name')->paginate(9)->withQueryString();

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
