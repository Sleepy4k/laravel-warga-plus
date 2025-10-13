<?php

namespace App\DataTables\Administration\Document;

use App\Facades\Format;
use App\Models\DocumentVersion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DocumentVersionDataTable extends DataTable
{
    protected string $documentId;

    /**
     * Set the document ID.
     *
     * @param string $id
     *
     * @return void
     */
    public function setDocumentId(string $id): void
    {
        $this->documentId = $id;
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<DocumentVersion> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'document.version.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'delete' => [
                'permission' => 'document.version.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        $types = [
            'application/pdf' => 'PDF',
            'image/jpeg' => 'JPEG',
            'image/png' => 'PNG',
            'application/msword' => 'Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word',
            'application/vnd.ms-excel' => 'Excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel',
            'application/vnd.ms-powerpoint' => 'PowerPoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'PowerPoint',
            'application/zip' => 'ZIP',
            'application/x-rar-compressed' => 'RAR',
            'application/x-7z-compressed' => '7z',
            'application/x-tar' => 'TAR',
            'application/json' => 'JSON',
            'text/plain' => 'Text',
            'text/csv' => 'CSV',
            'application/xml' => 'XML',
        ];

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

                if (canUserPerformAction('document.version.download')) {
                    $actions .= sprintf(
                        '<a class="btn btn-info btn-sm me-1 mb-1" href="%s"><i class="fa-solid fa-cloud-arrow-down"></i></a>',
                        route('dashboard.document.version.show', [
                            'document' => $query->document->id,
                            'version' => $query->id
                        ])
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
            ->addColumn('file_display', function ($query) {
                return sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    $query->file_path,
                    strlen($query->file_name) > 20
                        ? substr($query->file_name, 0, 13) . '...' . substr($query->file_name, -7)
                        : $query->file_name
                );
            })
            ->addColumn('version_number_display', function ($query) {
                return sprintf(
                    '<span class="badge bg-primary">V%s</span>',
                    $query->version_number
                );
            })
            ->editColumn('file_size', function ($query) {
                return Format::formatFileSize($query->file_size);
            })
            ->addColumn('uploaded_by', function ($query) {
                return $query->user->personal->full_name;
            })
            ->editColumn('uploaded_at', function ($query) {
                return $query->uploaded_at ? $query->uploaded_at->format('d, M Y H:i') : '-';
            })
            ->editColumn('file_type', function ($query) use ($types) {
                return $types[$query->file_type] ?? strtoupper(explode('/', $query->file_type)[1] ?? $query->file_type);
            })
            ->rawColumns(['action', 'file_display', 'version_number_display']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<DocumentVersion>
     */
    public function query(DocumentVersion $model): QueryBuilder
    {
        return $model
            ->select('id', 'file_name', 'file_path', 'file_size', 'file_type', 'version_number', 'document_id', 'user_id', 'uploaded_at', 'created_at', 'updated_at')
            ->with('user:id', 'user.personal:id,user_id,first_name,last_name', 'document:id')
            ->where('document_id', $this->documentId);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('document-version-table')
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
            Column::make('file_name')
                ->title('File Name')
                ->addClass('text-center')
                ->hidden(),
            Column::make('file_path')
                ->title('File Path')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('file_display')
                ->exportable(false)
                ->printable(false)
                ->title('File Name')
                ->addClass('text-center'),
            Column::make('version_number')
                ->title('File Version')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('version_number_display')
                ->exportable(false)
                ->printable(false)
                ->title('File Version')
                ->addClass('text-center'),
            Column::make('file_size')
                ->title('File Size')
                ->addClass('text-center'),
            Column::make('file_type')
                ->title('MIME Type')
                ->addClass('text-center'),
            Column::make('user.personal.first_name')
                ->exportable(false)
                ->printable(false)
                ->title('First Name')
                ->addClass('text-center')
                ->hidden(),
            Column::make('user.personal.last_name')
                ->exportable(false)
                ->printable(false)
                ->title('Last Name')
                ->addClass('text-center')
                ->hidden(),
            Column::make('uploaded_by')
                ->title('Uploaded By')
                ->addClass('text-center'),
            Column::make('uploaded_at')
                ->title('Uploaded At')
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
        return 'Document_Version_' . date('YmdHis');
    }
}
