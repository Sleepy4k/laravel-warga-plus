<?php

namespace App\Services\Web\Landing;

use App\Foundations\Service;
use App\Models\Report;

class ReportDetailService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(Report $report): array
    {
        $report->load(['category:id,name', 'user:id,phone', 'user.personal:id,user_id,first_name,last_name,avatar', 'progress:id,report_id,title,description,created_at', 'attachments:id,report_id,path']);
        $progresses = $report->progress->sortByDesc('created_at')->values();
        $report->setRelation('progress', $progresses);

        return compact('report');
    }
}
