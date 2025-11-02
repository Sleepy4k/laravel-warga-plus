<?php

namespace App\DataTables\User;

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
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('content', function ($query) {
                return strlen($query->content) > 50 ? substr($query->content, 0, 50) . '...' : $query->content;
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format('d M Y H:i:s');
            })
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Report $model): QueryBuilder
    {
        $user = request()->route('user');

        return $model
            ->select('reports.id', 'reports.title', 'reports.content', 'reports.location', 'reports.status', 'reports.category_id', 'reports.user_id', 'reports.created_at', 'reports.updated_at')
            ->with('category:id,name')
            ->where('reports.user_id', $user?->id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
                ->title('Judul')
                ->addClass('text-center'),
            Column::make('content')
                ->title('Isi Laporan')
                ->addClass('text-center'),
            Column::make('location')
                ->title('Lokasi')
                ->addClass('text-center'),
            Column::make('status')
                ->title('Status')
                ->addClass('text-center'),
            Column::make('category.name')
                ->title('Kategori')
                ->addClass('text-center'),
            Column::make('created_at')
                ->title('Dibuat Pada')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Report_' . date('YmdHis');
    }
}
