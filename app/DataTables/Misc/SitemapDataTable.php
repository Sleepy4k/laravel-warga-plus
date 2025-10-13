<?php

namespace App\DataTables\Misc;

use App\Models\SitemapData;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SitemapDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<SitemapData> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'misc.sitemap.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'edit' => [
                'permission' => 'misc.sitemap.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'misc.sitemap.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

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
            ->editColumn('last_modified', function ($query) {
                return $query->last_modified ? date('d, M Y H:i', strtotime($query->last_modified)) : '-';
            })
            ->editColumn('change_frequency', function ($query) {
                return ucfirst($query->change_frequency);
            })
            ->addColumn('change_frequency_plain', function ($query) {
                return $query->change_frequency;
            })
            ->editColumn('priority', function ($query) {
                return number_format($query->priority, 2);
            })
            ->editColumn('status', function ($query) {
                $status = strtolower($query->status);
                $class = match ($status) {
                    'active' => 'badge bg-primary',
                    'inactive' => 'badge bg-danger',
                    default => 'badge bg-light text-dark',
                };
                return '<span class="' . $class . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('status_plain', function ($query) {
                return $query->status;
            })
            ->editColumn('news', function ($query) {
                if (!empty($query->news)) {
                    $news = trim($query->news);
                    return strlen($news) > 47 ? substr($news, 0, 47) . '...' : $news;
                }
                return '-';
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<SitemapData>
     */
    public function query(SitemapData $model): QueryBuilder
    {
        return $model
            ->select('id', 'url', 'last_modified', 'change_frequency', 'priority', 'status', 'created_at', 'updated_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sitemap-table')
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
            Column::make('url')
                ->title('URL')
                ->addClass('text-center'),
            Column::make('last_modified')
                ->title('Last Modified')
                ->addClass('text-center'),
            Column::make('change_frequency')
                ->title('Change Frequency')
                ->addClass('text-center'),
            Column::make('priority')
                ->title('Priority')
                ->addClass('text-center'),
            Column::make('status')
                ->title('Status')
                ->addClass('text-center'),
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
        return 'Sitemap_Data_' . date('YmdHis');
    }
}
