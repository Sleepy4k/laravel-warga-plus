<?php

namespace App\DataTables\Misc;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JobDataTable extends DataTable
{
    /**
     * Init log file.
     *
     * @return Collection<object>
     */
    public function customData(): Collection
    {
        $jobs = DB::table('jobs')->orderBy('created_at', 'desc')->get();
        $failedJobs = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->get();

        $pendingJobs = $jobs->map(function ($job) {
            $job->status = 'pending';
            $job->payload = json_decode($job->payload, true);
            $job->reserved_at = $job->reserved_at ? date('Y-m-d H:i:s', $job->reserved_at) : null;
            $job->available_at = $job->available_at ? date('Y-m-d H:i:s', $job->available_at) : null;
            $job->created_at = $job->created_at ? date('Y-m-d H:i:s', $job->created_at) : null;
            $job->name = $job->payload['displayName'] ?? 'Unknown Job';
            $job->job = $job->payload['job'] ?? 'Unknown Job Class';
            $job->queue = $job->queue ?: 'default';
            return $job;
        });

        $failedJobsData = $failedJobs->map(function ($job) {
            $job->status = 'failed';
            $job->payload = json_decode($job->payload, true);
            $job->exception = $job->exception ?: 'No exception message';
            $job->queue = $job->queue ?: 'default';
            $job->name = $job->payload['displayName'] ?? 'Unknown Job';
            $job->job = $job->payload['job'] ?? 'Unknown Job Class';
            $job->created_at = $job->failed_at;
            return $job;
        });

        return $pendingJobs->concat($failedJobsData);
    }

    /**
     * Build the DataTable class.
     */
    public function dataTable(): CollectionDataTable
    {
        return datatables()
            ->of($this->customData())
            ->editColumn('status', function ($query) {
                return ucfirst($query->status);
            })
            ->editColumn('status_label', function ($query) {
                return $query->status === 'pending'
                    ? '<span class="badge bg-primary">Pending</span>'
                    : '<span class="badge bg-danger">Failed</span>';
            })
            ->editColumn('queue', function ($query) {
                return ucfirst($query->queue);
            })
            ->editColumn('queue_label', function ($query) {
                $queueName = $query->queue === 'default'
                    ? ucfirst(config('queue.default', 'default'))
                    : ucfirst($query->queue);

                return '<span class="badge bg-primary">' . e($queueName) . '</span>';
            })
            ->rawColumns(['status_label', 'queue_label'])
            ->setRowId('id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->serverSide(false)
            ->setTableId('job-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->lengthChange(true)
            ->pageLength(10)
            ->autoWidth(true)
            ->responsive(true)
            ->selectStyleSingle()
            ->layout([
                'top1Start' => "buttons",
                'bottomStart' => "info",
                'bottomEnd' => "paging",
            ])
            ->buttons([
                Button::make('export'),
                Button::make('print'),
                Button::make('copy'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('created_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::make('id')
                ->title('Job ID')
                ->addClass('text-center'),
            Column::make('name')
                ->title('Name')
                ->addClass('text-center'),
            Column::make('status_label')
                ->title('Status')
                ->addClass('text-center'),
            Column::make('status')
                ->title('Status')
                ->addClass('text-center')
                ->hidden(),
            Column::make('queue_label')
                ->title('Queue')
                ->addClass('text-center'),
            Column::make('queue')
                ->title('Queue')
                ->addClass('text-center')
                ->hidden(),
            Column::make('job')
                ->title('Job')
                ->addClass('text-center'),
            Column::make('created_at')
                ->title('Created At')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Job_' . date('YmdHis');
    }
}
