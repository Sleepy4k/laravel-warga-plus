<?php

namespace App\DataTables\Report;

use App\Enums\ReportType;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReportDataTable extends DataTable
{
    /**
     * Indicates if the current user is a default user.
     */
    protected bool $isUser = false;

    /**
     * Initialize the data table class.
     */
    public function __construct()
    {
        $this->isUser = isUserHasRole(config('rbac.role.default'));
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Report> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'edit' => [
                'permission' => 'report.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'report.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        $table = (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

                if (canUserPerformAction('report.show')) {
                    $actions .= sprintf(
                        '<a class="btn btn-info btn-sm me-1 mb-1" href="%s"><i class="fa-solid fa-circle-info"></i></a>',
                        route('dashboard.report.show', $query->id)
                    );
                }

                foreach ($buttons as $btn) {
                    if (canUserPerformAction($btn['permission'])) {
                        $actions .= sprintf(
                            '<button class="btn %s btn-sm me-1 mb-1" data-id="%s"%s><i class="fa-solid %s"></i></button>',
                            $btn['class'],
                            $query->id,
                            $btn['target'] ? ' data-target="' . $btn['target'] . '"' : '',
                            $btn['icon']
                        );
                    }
                }

                return $actions ?: '-';
            })
            ->addColumn('content_display', function ($query) {
                $content = $query->content ?: '-';
                return strlen($content) > 50 ? substr($content, 0, 47) . '...' : $content;
            })
            ->editColumn('status', function ($query) {
                $status = ReportType::from($query->status)?->value ?? '-';
                return ucwords(strtolower(str_replace('_', ' ', $status)));
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format('d, M Y H:i');
            })
            ->orderColumn('full_name', function ($query, $order) {
                $query->join('user_personal_data', 'reports.user_id', '=', 'user_personal_data.user_id')
                    ->orderBy('user_personal_data.first_name', $order)
                    ->orderBy('user_personal_data.last_name', $order);
            });

        if (!$this->isUser) {
            $table->editColumn('full_name', function ($query) {
                return $query->user->personal->full_name ?? '-';
            });
        }

        return $table->rawColumns(['action'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Report>
     */
    public function query(Report $model): QueryBuilder
    {
        $query = $model
            ->select('reports.id', 'reports.title', 'reports.content', 'reports.location', 'reports.status', 'category_id', 'reports.user_id', 'reports.created_at', 'reports.updated_at')
            ->with('category:id,name', 'attachments:id,report_id,path,file_name,file_size,extension');

        if ($this->isUser) {
            $query->where('reports.user_id', auth('web')->id());
        } else {
            $query->with('user:id', 'user.personal:id,user_id,first_name,last_name');
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('report-table')
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
        $columns = [
            Column::computed('updated_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::computed('DT_RowIndex')
                ->title('No')
                ->addClass('text-center'),
            Column::make('title')
                ->title('Title')
                ->addClass('text-center'),
            Column::make('content')
                ->title('Content')
                ->addClass('text-center')
                ->hidden(),
            Column::make('content_display')
                ->exportable(false)
                ->printable(false)
                ->title('Content')
                ->addClass('text-center'),
            Column::make('category.name')
                ->title('Category')
                ->addClass('text-center'),
            Column::make('location')
                ->title('Location')
                ->addClass('text-center'),
            Column::make('status')
                ->title('Status')
                ->addClass('text-center'),
        ];

        if (!$this->isUser) {
            $columns = array_merge($columns, [
                Column::make('user.personal.first_name')
                    ->title('First Name')
                    ->addClass('text-center')
                    ->exportable(false)
                    ->printable(false)
                    ->hidden(),
                Column::make('user.personal.last_name')
                    ->title('Last Name')
                    ->addClass('text-center')
                    ->exportable(false)
                    ->printable(false)
                    ->hidden(),
                Column::make('full_name')
                    ->title('Reported By')
                    ->addClass('text-center')
                    ->searchable(false),
            ]);
        }

        $columns = array_merge($columns, [
            Column::make('created_at')
                ->title('Created At')
                ->addClass('text-center'),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
        ]);

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Report_' . date('YmdHis');
    }
}
