<?php

namespace App\DataTables\Menu;

use App\Models\Shortcut;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ShortcutDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Shortcut> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'menu.shortcut.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'edit' => [
                'permission' => 'menu.shortcut.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'menu.shortcut.delete',
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
            ->editColumn('icon_display', function ($query) {
                return '<i class="bx bx-' . e($query->icon) . '"></i>';
            })
            ->editColumn('route_display', function ($query) {
                return route($query->route);
            })
            ->editColumn('permissions_display', function ($query) {
                $permissions = collect($query->permissions ?? []);
                if ($permissions->isEmpty()) {
                    return '<span class="badge rounded-pill bg-light text-white border">None</span>';
                }
                return $permissions->map(function ($permission) {
                    return '<span class="badge rounded-pill bg-info text-black border me-2 mb-2">' . e($permission) . '</span>';
                })->implode(' ');
            })
            ->editColumn('permissions', function ($query) {
                return implode(', ', $query->permissions ?? []);
            })
            ->rawColumns(['action', 'permissions_display', 'icon_display'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Shortcut>
     */
    public function query(Shortcut $model): QueryBuilder
    {
        return $model
            ->select('id', 'label', 'route', 'icon', 'description', 'permissions', 'created_at', 'updated_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('shortcut-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->lengthChange(true)
            ->autoFill(true)
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
            Column::computed('created_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::computed('DT_RowIndex')
                ->title('No')
                ->addClass('text-center'),
            Column::computed('icon')
                ->title('Icon')
                ->addClass('text-center')
                ->hidden(),
            Column::make('icon_display')
                ->exportable(false)
                ->orderable(false)
                ->printable(false)
                ->title('Icon')
                ->addClass('text-center'),
            Column::make('label')
                ->title('Label')
                ->addClass('text-center'),
            Column::computed('route')
                ->title('Route')
                ->addClass('text-center')
                ->hidden(),
            Column::make('route_display')
                ->exportable(false)
                ->orderable(false)
                ->printable(false)
                ->title('Route')
                ->addClass('text-center')
                ->hidden(),
            Column::make('description')
                ->title('Description')
                ->addClass('text-center'),
            Column::computed('permissions')
                ->title('Permissions')
                ->addClass('text-center')
                ->hidden(),
            Column::make('permissions_display')
                ->exportable(false)
                ->orderable(false)
                ->printable(false)
                ->title('Permissions')
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
        return 'Shortcut_Menu_' . date('YmdHis');
    }
}
