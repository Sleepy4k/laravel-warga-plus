<?php

namespace App\DataTables\RBAC;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Permission> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'rbac.permission.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'edit' => [
                'permission' => 'rbac.permission.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'rbac.permission.delete',
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
            ->editColumn('assigned_to', function ($query) {
                $roles = $query->roles->pluck('name');
                if ($roles->isEmpty()) {
                    return '<span class="badge rounded-pill bg-light text-white border">None</span>';
                }
                return $roles->map(function ($role) {
                    return '<span class="badge rounded-pill bg-info text-black border me-2 mb-2">' . e($role) . '</span>';
                })->implode(' ');
            })
            ->editColumn('guard_name', function ($query) {
                return ucfirst($query->guard_name);
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format('d, M Y H:i');
            })
            ->rawColumns(['action', 'assigned_to'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Permission>
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model
            ->select(['id', 'name', 'guard_name', 'created_at', 'updated_at'])
            ->with('roles');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('permission-table')
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
            Column::computed('assigned_to')
                ->hidden()
                ->title('Digunakan Pada')
                ->addClass('text-center'),
            Column::make('guard_name')
                ->title('Nama Guard')
                ->addClass('text-center'),
            Column::make('created_at')
                ->title('Dibuat Pada')
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
        return 'Permission_' . date('YmdHis');
    }
}
