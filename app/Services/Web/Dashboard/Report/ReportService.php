<?php

namespace App\Services\Web\Dashboard\Report;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Enums\ReportType;
use App\Foundations\Service;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\ReportCategory;
use App\Models\ReportProgress;
use Illuminate\Support\Facades\DB;

class ReportService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ReportInterface $reportInterface
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $isUser = isUserHasRole(config('rbac.role.default'));
        $whereClause = $isUser ? [['user_id', auth('web')->id()]] : [];
        $reports = $this->reportInterface->all(['id', 'status', 'user_id'], [], $whereClause);
        $totalReports = $reports->count();
        $totalReportsProcessed = $reports->filter(fn ($report) => $report->status === ReportType::PROCCESSED->value)->count();
        $totalReportsDeclined = $reports->filter(fn ($report) => $report->status === ReportType::DENIED->value)->count();
        $totalReportsSolved = $reports->filter(fn ($report) => $report->status === ReportType::COMPLETED->value)->count();

        $statuses = ReportType::cases();
        $categories = ReportCategory::select('id', 'name', 'created_at')->orderBy('created_at', 'asc')->get();

        return compact(
            'totalReports',
            'totalReportsProcessed',
            'totalReportsDeclined',
            'totalReportsSolved',
            'categories',
            'statuses',
            'isUser'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $userId = auth('web')->id();

            $attachments = $request['file'] ?? [];
            unset($request['file']);

            DB::beginTransaction();

            if ($request['category_id'] === 'other' && isset($request['new-category'])) {
                $category = ReportCategory::firstOrCreate([
                    'name' => ucwords(strtolower($request['new-category'])),
                ]);
                $request['category_id'] = $category->id;
                unset($request['new-category']);
            }

            $report = Report::create(array_merge($request, [
                'user_id' => $userId,
                'status' => ReportType::CREATED,
            ]));

            ReportProgress::create([
                'report_id' => $report->id,
                'title' => 'Report Created',
                'description' => 'The report has been created by the user.',
            ]);

            if ($attachments && !empty($attachments)) {
                foreach ($attachments as $attachment) {
                    ReportAttachment::create([
                        'report_id' => $report->id,
                        'user_id' => $userId,
                        'path' => $attachment,
                        'file_name' => $attachment->getClientOriginalName(),
                        'file_size' => $attachment->getSize(),
                        'extension' => $attachment->getClientOriginalExtension(),
                    ]);
                }
            }

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Error creating report: '.$th->getMessage(), [
                'request' => $request
            ]);
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): array
    {
        $report->load([
            'user:id,phone,identity_number,is_active',
            'user.personal:user_id,first_name,last_name,birth_date,job,gender,address,avatar',
            'category:id,name',
            'attachments:id,report_id,path,file_name,file_size,extension,created_at',
            'progress:report_id,title,description,created_at',
        ]);
        $user = $report->user;
        $personal = $user->personal;
        $role = $user->getRoleNames()->first() ?? 'N/A';
        $isCanAddProgress = !in_array($report->status, [
            ReportType::COMPLETED->value,
            ReportType::DENIED->value,
            ReportType::CREATED->value,
        ], true);

        // sort progress by created_at descending
        $progresses = $report->progress->sortByDesc('created_at')->values();

        return compact('report', 'user', 'personal', 'role', 'isCanAddProgress', 'progresses');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Report $report): bool
    {
        try {
            $attachments = $request['file'] ?? [];
            unset($request['file']);

            DB::beginTransaction();

            if ($request['category_id'] === 'other' && isset($request['new-category'])) {
                $category = ReportCategory::firstOrCreate([
                    'name' => ucwords(strtolower($request['new-category'])),
                ]);
                $request['category_id'] = $category->id;
                unset($request['new-category']);
            }

            if (!isUserHasRole(config('rbac.role.default')) && isset($request['status'])) {
                $request['status'] = ReportType::from($request['status'])?->value;
                $status = $request['status'] ?? null;

                $progressMap = [
                    ReportType::PROCCESSED->value => [
                        'title' => 'Report Processed',
                        'description' => 'The report is being processed by the management.',
                    ],
                    ReportType::DENIED->value => [
                        'title' => 'Report Denied',
                        'description' => 'The report has been denied by the management.',
                    ],
                    ReportType::COMPLETED->value => [
                        'title' => 'Report Completed',
                        'description' => 'The report has been completed by the management.',
                    ],
                ];

                if ($status !== null && isset($progressMap[$status])) {
                    ReportProgress::create(array_merge(['report_id' => $report->id], $progressMap[$status]));
                }
            } else {
                unset($request['status']);
            }

            $updated = $report->update($request);

            if ($attachments && !empty($attachments)) {
                foreach ($attachments as $attachment) {
                    ReportAttachment::create([
                        'report_id' => $report->id,
                        'user_id' => auth('web')->id(),
                        'path' => $attachment,
                        'file_name' => $attachment->getClientOriginalName(),
                        'file_size' => $attachment->getSize(),
                        'extension' => $attachment->getClientOriginalExtension(),
                    ]);
                }
            }

            DB::commit();

            return $updated;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Error updating report: '.$th->getMessage(), [
                'request' => $request,
                'report_id' => $report->id
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report): bool
    {
        try {
            if ($report->attachments()->count() > 0) {
                foreach ($report->attachments as $attachment) {
                    $attachment->delete();
                }
            }

            return $report->delete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Error deleting report: '.$th->getMessage(), [
                'report_id' => $report->id
            ]);
            return false;
        }
    }

    /**
     * Get report statistics.
     *
     * @return array
     */
    public function getReportStats(): array
    {
        $reports = $this->reportInterface->all(['id', 'status']);

        $totalReports = $reports->count();
        $totalReportsProcessed = $reports->filter(fn ($report) => $report->status === ReportType::PROCCESSED->value)->count();
        $totalReportsDeclined = $reports->filter(fn ($report) => $report->status === ReportType::DENIED->value)->count();
        $totalReportsSolved = $reports->filter(fn ($report) => $report->status === ReportType::COMPLETED->value)->count();

        return compact(
            'totalReports',
            'totalReportsProcessed',
            'totalReportsDeclined',
            'totalReportsSolved'
        );
    }
}
