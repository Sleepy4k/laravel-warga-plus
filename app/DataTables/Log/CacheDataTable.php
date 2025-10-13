<?php

namespace App\DataTables\Log;

use App\Enums\LogReaderType;
use App\Facades\LogReader;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CacheDataTable extends DataTable
{
    /**
     * Init log file.
     *
     * @return Collection
     */
    public function customData()
    {
        return collect(
            (object) LogReader::getFileList(LogReaderType::DAILY, 'cache')
        );
    }

    /**
     * Build the DataTable class.
     */
    public function dataTable(): CollectionDataTable
    {
        return datatables()
            ->of($this->customData())
            ->addColumn('action', function ($query) {
                if (!canUserPerformAction('log.cache.show')) {
                    return '-';
                }

                $name = explode('.', $query['name'])[0];
                $date = substr($name, 6, 10);

                return '<a href="' . route("dashboard.log.cache.show", $date) . '"><button class="btn btn-info btn-sm me-1 mb-1"><i class="fa-solid fa-eye"></i></button></a>';
            })
            ->editColumn('name', function ($query) {
                $name = explode('.', $query['name'])[0];
                $date = substr($name, 6, 10);
                return 'Cache - ' . date('d M Y', strtotime($date));
            })
            ->editColumn('type', function ($query) {
                return ucfirst($query['type']);
            })
            ->editColumn('last_updated', function ($query) {
                return $query['last_updated'] ? date('d M Y H:i:s', strtotime($query['last_updated'])) : 'N/A';
            })
            ->addColumn('date', function ($query) {
                $name = explode('.', $query['name'])[0];
                return substr($name, 6, 10);
            })
            ->setRowId('id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->serverSide(false)
            ->setTableId('cache-table')
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
            Column::make('name')
                ->title('Name')
                ->addClass('text-center'),
            Column::make('size')
                ->title('Size')
                ->addClass('text-center'),
            Column::make('type')
                ->title('Type')
                ->addClass('text-center'),
            Column::make('content')
                ->title('Content')
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
        return 'Cache_Log_' . date('YmdHis');
    }
}
