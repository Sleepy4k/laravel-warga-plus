<?php

namespace App\DataTables\Misc;

use App\Facades\Format;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BackupDataTable extends DataTable
{
    /**
     * Init log file.
     *
     * @return Collection<object>
     */
    public function customData(): Collection
    {
        $backupPath = storage_path('backups');
        $files = collect(glob("{$backupPath}/backup-*.sql", GLOB_BRACE))
            ->filter(fn($file) => File::isFile($file))
            ->map(function ($file) use ($backupPath) {
                return [
                    'name' => basename($file),
                    'size' => Format::formatFileSize(File::size($file)),
                    'type' => File::type($file),
                    'last_updated' => date('Y-m-d H:i:s', File::lastModified($file)),
                ];
            });
        return $files;
    }

    /**
     * Build the DataTable class.
     */
    public function dataTable(): CollectionDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'misc.backup.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-cloud-arrow-down',
                'target' => null,
            ],
            'delete' => [
                'permission' => 'misc.backup.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        return datatables()
            ->of($this->customData())
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

                foreach ($buttons as $btn) {
                    if (canUserPerformAction($btn['permission'])) {
                        $date = substr($query['name'], 7, 17);
                        $actions .= sprintf(
                            '<button class="btn %s btn-sm me-1 mb-1" data-id="%s"%s><i class="fa-solid %s"></i></button>',
                            $btn['class'],
                            $date,
                            $btn['target'] ? ' data-target="' . $btn['target'] . '"' : '',
                            $btn['icon']
                        );
                    }
                }

                return $actions ?: '-';
            })
            ->editColumn('name', function ($query) {
                $name = explode('-', $query['name'])[0];
                return ucfirst($name);
            })
            ->editColumn('type', function ($query) {
                return ucfirst($query['type']);
            })
            ->editColumn('last_updated', function ($query) {
                return $query['last_updated'] ? date('d, M Y H:i', strtotime($query['last_updated'])) : 'N/A';
            })
            ->editColumn('updated_at', function ($query) {
                return $query['last_updated'] ? date('d M Y H:i:s', strtotime($query['last_updated'])) : 'N/A';
            })
            ->rawColumns(['action', 'assigned_to'])
            ->setRowId('id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->serverSide(false)
            ->setTableId('backup-table')
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
            Column::computed('updated_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::make('name')
                ->title('Name')
                ->addClass('text-center'),
            Column::make('size')
                ->title('Size')
                ->addClass('text-center'),
            Column::make('type')
                ->title('Type')
                ->addClass('text-center'),
            Column::make('last_updated')
                ->title('Last Updated')
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
        return 'Backup_Database_' . date('YmdHis');
    }
}
