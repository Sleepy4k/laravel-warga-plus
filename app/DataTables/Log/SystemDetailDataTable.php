<?php

namespace App\DataTables\Log;

use App\Facades\LogReader;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SystemDetailDataTable extends DataTable
{
    /**
     * Init log file.
     *
     * @return Collection
     */
    public function customData()
    {
        $date = basename(request()->path());

        return collect(
            (object) LogReader::getFileContent('laravel-'.$date)
        );
    }

    /**
     * Build the DataTable class.
     */
    public function dataTable(): CollectionDataTable
    {
        return datatables()
            ->of($this->customData())
            ->editColumn('env', function ($query) {
                return ucfirst($query['env'] ?? 'N/A');
            })
            ->editColumn('timestamp', function ($query) {
                return $query['timestamp'] ? date('d M Y H:i:s', strtotime($query['timestamp'])) : 'N/A';
            })
            ->editColumn('message', function ($query) {
                return nl2br(e($query['message'] ?? 'N/A'));
            })
            ->rawColumns(['message'])
            ->setRowId('id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->serverSide(false)
            ->setTableId('system-detail-table')
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
            Column::make('env')
                ->title('Env')
                ->addClass('text-center'),
            Column::make('type')
                ->title('Type')
                ->addClass('text-center'),
            Column::make('timestamp')
                ->title('Timestamp')
                ->addClass('text-center'),
            Column::make('message')
                ->title('Message')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'System_Detail_Log_' . date('YmdHis');
    }
}
