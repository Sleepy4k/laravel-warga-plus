<?php

namespace App\DataTables\Administration\Document;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DocumentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Document> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'edit' => [
                'permission' => 'document.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'document.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

                if (canUserPerformAction('document.show')) {
                    $actions .= sprintf(
                        '<a class="btn btn-info btn-sm me-1 mb-1" href="%s"><i class="fa-solid fa-circle-info"></i></a>',
                        route('dashboard.document.show', $query->id)
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
            ->addColumn('description_display', function ($query) {
                $desc = $query->description ?: '-';
                return strlen($desc) > 50 ? substr($desc, 0, 47) . '...' : $desc;
            })
            ->editColumn('is_archived', function ($query) {
                return $query->is_archived ? 'Yes' : 'No';
            })
            ->editColumn('last_modified_at', function ($query) {
                return $query->last_modified_at ? $query->last_modified_at->format('d, M Y H:i') : '-';
            })
            ->rawColumns(['action'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Document>
     */
    public function query(Document $model): QueryBuilder
    {
        return $model
            ->select('id', 'title', 'description', 'is_archived', 'category_id', 'last_modified_at', 'created_at', 'updated_at')
            ->with('category:id,name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('document-table')
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
            Column::make('title')
                ->title('Title')
                ->addClass('text-center'),
            Column::make('description')
                ->title('Description')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('description_display')
                ->exportable(false)
                ->printable(false)
                ->title('Description')
                ->addClass('text-center'),
            Column::make('category.name')
                ->title('Category')
                ->addClass('text-center'),
            Column::make('is_archived')
                ->title('Archived')
                ->addClass('text-center'),
            Column::make('last_modified_at')
                ->title('Last Modified At')
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
        return 'Document_' . date('YmdHis');
    }
}
