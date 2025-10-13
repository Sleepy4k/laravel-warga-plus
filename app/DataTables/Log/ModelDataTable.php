<?php

namespace App\DataTables\Log;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ModelDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Model> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return canUserPerformAction('log.model.show')
                    ? '<button class="btn btn-info btn-sm me-1 mb-1 show-record" data-id="' . $query->id . '" data-target="#show-record"><i class="fa-solid fa-circle-info"></i></button>'
                    : '-';
            })
            ->editColumn('log_name', function ($query) {
                return ucfirst($query->log_name);
            })
            ->editColumn('event', function ($query) {
                return ucfirst($query->event);
            })
            ->editColumn('subject', function ($query) {
                $subject = trim(str_replace('App\\Models\\', '', $query->subject_type ?? ''), '\\');
                $text = ($subject ?: 'N/A') . ' # ' . ($query->subject_id ?? 'N/A');
                return strlen($text) > 35 ? mb_substr($text, 0, 32) . '...' : $text;
            })
            ->filterColumn('subject', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('subject_type', 'like', "%{$keyword}%")
                      ->orWhere('subject_id', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('subject', function ($query, $order) {
                $query->orderByRaw("COALESCE(subject_type, '') {$order}, COALESCE(subject_id, '') {$order}");
            })
            ->editColumn('causer', function ($query) {
                if (!$query->causer || $query->causer_type !== 'App\Models\User') {
                    return 'N/A';
                }

                if (!$query->causer->relationLoaded('personal')) {
                    $query->causer->load('personal');
                }

                return $query->causer->personal
                    ? $query->causer->personal->full_name
                    : ($query->causer->username ?? 'N/A');
            })
            ->filterColumn('causer', function ($query, $keyword) {
                $query->whereHas('causer.personal', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$keyword}%"]);
                })
                ->orWhereHas('causer', function ($q) use ($keyword) {
                    $q->where('username', 'like', "%{$keyword}%");
                })
                ->orWhere('causer_id', 'like', "%{$keyword}%");
            })
            ->orderColumn('causer', function ($query, $order) {
                $query->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
                    ->leftJoin('user_personal_data', 'users.id', '=', 'user_personal_data.user_id')
                    ->orderByRaw("COALESCE(CONCAT(user_personal_data.first_name, ' ', user_personal_data.last_name), users.username, activity_log.causer_id) {$order}");
            })
            ->editColumn('properties', function ($query) {
                return $query->properties;
            })
            ->filterColumn('properties', function ($query, $keyword) {
                $query->whereRaw("JSON_EXTRACT(properties, '$') LIKE ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['action'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Activity>
     */
    public function query(Activity $model): QueryBuilder
    {
        return $model->select(
                'activity_log.id',
                'activity_log.log_name',
                'activity_log.description',
                'activity_log.event',
                'activity_log.subject_type',
                'activity_log.subject_id',
                'activity_log.causer_type',
                'activity_log.causer_id',
                'activity_log.properties',
                'activity_log.created_at',
                'activity_log.updated_at'
            )
            ->with('causer', 'subject')
            ->where('log_name', 'model')
            ->where('causer_id', '!=', null)
            ->where('subject_id', '!=', null);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('model-table')
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
                Button::make('reload'),
                Button::make('copy'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('updated_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::computed('DT_RowIndex')
                ->title('No')
                ->addClass('text-center'),
            Column::make('log_name')
                ->searchable(false)
                ->hidden(),
            Column::make('event')
                ->title('Event')
                ->addClass('text-center'),
            Column::make('description')
                ->title('Description')
                ->addClass('text-center'),
            Column::make('subject')
                ->title('Subject')
                ->addClass('text-center'),
            Column::make('causer')
                ->title('Causer Name')
                ->addClass('text-center'),
            Column::make('properties')
                ->title('Properties')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Model_Log_' . date('YmdHis');
    }
}
