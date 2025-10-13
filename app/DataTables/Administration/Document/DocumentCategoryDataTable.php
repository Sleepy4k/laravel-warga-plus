<?php

namespace App\DataTables\Administration\Document;

use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DocumentCategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<DocumentCategory> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'document.category.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'edit' => [
                'permission' => 'document.category.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'document.category.delete',
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
            ->rawColumns(['action'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<DocumentCategory>
     */
    public function query(DocumentCategory $model): QueryBuilder
    {
        return $model->select('id', 'name', 'description', 'created_at', 'updated_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('document-category-table')
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
            Column::make('name')
                ->title('Nama')
                ->addClass('text-center'),
            Column::make('description')
                ->title('Deskripsi')
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
        return 'Document_Category_' . date('YmdHis');
    }
}
