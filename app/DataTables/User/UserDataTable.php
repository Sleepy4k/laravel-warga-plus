<?php

namespace App\DataTables\User;

use App\Enums\UserOnlineStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rolesConfig = config('rbac.role');
        $iconMap = [
            $rolesConfig['highest'] => ['desktop', 'text-danger'],
            $rolesConfig['default'] => ['user', 'text-success'],
        ];

        $buttons = [
            'edit' => [
                'permission' => 'user.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'user.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

                if (canUserPerformAction('user.show')) {
                    $actions .= sprintf(
                        '<a class="btn btn-info btn-sm me-1 mb-1" href="%s"><i class="fa-solid fa-circle-info"></i></a>',
                        route('dashboard.user.show', $query->id)
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
            ->editColumn('user', function ($query) {
                return '<div class="d-flex justify-content-start align-items-center text-start">'.
                    '<div class="avatar-wrapper">'.
                        '<div class="avatar avatar-sm me-4">'.
                            '<img src="'.$query->userAvatar().'" alt="Avatar" class="rounded-circle" loading="lazy" />'.
                        '</div>'.
                    '</div>'.
                    '<div class="d-flex flex-column">'.
                        '<a href="'.route('dashboard.user.show', $query->id).'" class="text-heading text-truncate">'.
                            '<span class="fw-medium">'.$query->personal->full_name.'</span>'.
                        '</a>'.
                        '<small>'.$query->phone.'</small>'.
                    '</div>'.
                '</div>';
            })
            ->editColumn('role', function ($query) {
                $userRole = $query->getRoleNames()->first();
                return ucfirst($userRole ?? '');
            })
            ->editColumn('role_display', function ($query) use ($iconMap) {
                $role = $query->getRoleNames()->first();
                [$icon, $color] = $iconMap[$role] ?? ['edit', 'text-warning'];

                return '<span class="text-truncate d-flex align-items-center text-heading">'.
                    '<i class="icon-base bx bx-'.$icon.' '.$color.' me-2"></i>'.
                    ucfirst($role ?? '').
                '</span>';
            })
            ->editColumn('last_seen', function ($query) {
                if ($query->last_seen_status === UserOnlineStatus::OFFLINE) {
                    return $query->last_seen ? $query->last_seen->diffForHumans() : ucfirst(UserOnlineStatus::OFFLINE->value);
                }

                return ucfirst($query->last_seen_status->value);
            })
            ->editColumn('is_active', function ($query) {
                return $query->is_active ? 'Yes' : 'No';
            })
            ->editColumn('is_active_display', function ($query) {
                $class = $query->is_active ? 'bg-label-primary' : 'bg-label-secondary';
                return '<span class="badge ' . $class . '" text-capitalized="">' . ($query->is_active ? 'Active' : 'Inactive') . '</span>';
            })
            ->editColumn('personal.gender', function ($query) {
                return ucfirst($query->personal->gender);
            })
            ->rawColumns(['action', 'user', 'role_display', 'is_active_display'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $assignableRoles = config('rbac.assign.' . getUserRole(), []);

        return $model
            ->select('users.id', 'users.phone', 'users.identity_number', 'users.last_seen', 'users.is_active', 'users.created_at', 'users.updated_at')
            ->with('personal:id,user_id,first_name,last_name,avatar,gender,job,birth_date,address', 'roles:id,name')
            ->whereHas('personal')
            ->where(function ($query) use ($assignableRoles) {
                if (!empty($assignableRoles)) {
                    $query->whereHas('roles', function ($roleQuery) use ($assignableRoles) {
                        $roleQuery->whereIn('name', $assignableRoles);
                    });
                }
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
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
            Column::computed('updated_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::computed('DT_RowIndex')
                ->title('No')
                ->addClass('text-center'),
            Column::computed('user')
                ->exportable(false)
                ->orderable(false)
                ->printable(false)
                ->title('User'),
            Column::make('phone')
                ->title('Phone')
                ->addClass('text-center')
                ->hidden(),
            Column::make('personal.first_name')
                ->title('First Name')
                ->addClass('text-center')
                ->hidden(),
            Column::make('personal.last_name')
                ->title('Last Name')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('role')
                ->title('Role')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('role_display')
                ->exportable(false)
                ->printable(false)
                ->title('Role'),
            Column::make('personal.gender')
                ->title('Gender')
                ->addClass('text-center'),
            Column::make('personal.job')
                ->title('Job')
                ->addClass('text-center')
                ->hidden(),
            Column::make('personal.address')
                ->title('Address')
                ->addClass('text-center')
                ->hidden(),
            Column::make('last_seen')
                ->exportable(false)
                ->printable(false)
                ->title('Last Seen')
                ->addClass('text-center'),
            Column::make('is_active')
                ->title('Is Active')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('is_active_display')
                ->exportable(false)
                ->orderable(false)
                ->printable(false)
                ->title('Is Active')
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
        return 'User_' . date('YmdHis');
    }
}
