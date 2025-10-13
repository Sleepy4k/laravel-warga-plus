<?php

namespace App\DataTables\Administration\Agenda;

use App\Enums\LetterType;
use App\Models\Letter;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IncomingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Letter> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('letter_date', fn ($model) => $model->letter_date?->format('j F Y'))
            ->editColumn('received_date', fn ($model) => $model->received_date?->format('j F Y'))
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Letter>
     */
    public function query(Letter $model): QueryBuilder
    {
        $sinceFilter = request()->query('since', null);
        $untilFilter = request()->query('until', null);
        $columnFilter = request()->query('column', null);

        return $model->where('type', LetterType::INCOMING)
            ->select('id', 'reference_number', 'agenda_number', 'letter_date', 'received_date', 'from', 'classification_id', 'created_at')
            ->when($sinceFilter && $untilFilter && $columnFilter, function ($query) use ($sinceFilter, $untilFilter, $columnFilter) {
                $query->whereBetween(DB::raw('DATE(' . $columnFilter . ')'), [$sinceFilter, $untilFilter]);
            })
            ->with('classification:id,name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('incoming-table')
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
            Column::computed('letter_date')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::computed('DT_RowIndex')
                ->title('No')
                ->addClass('text-center'),
            Column::make('agenda_number')
                ->title('Agenda Number')
                ->addClass('text-center'),
            Column::make('reference_number')
                ->title('Reference Number')
                ->addClass('text-center'),
            Column::make('from')
                ->title('From')
                ->addClass('text-center'),
            Column::make('classification.name')
                ->title('Classification')
                ->addClass('text-center'),
            Column::make('letter_date')
                ->title('Letter Date')
                ->addClass('text-center'),
            Column::make('received_date')
                ->title('Received Date')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Incoming_' . date('YmdHis');
    }
}
