<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('name', function (User $user) {
                $url = route('users.show', $user);
                $avatar = e($user->avatar_url);
                $name = e($user->name);
                $email = e($user->email);

                return '<div class="users-user">'
                    .'<div class="users-avatar-wrap">'
                    .'<img src="'.$avatar.'" alt="'.$name.'" class="users-avatar">'
                    .'<span class="users-avatar-status online"></span>'
                    .'</div>'
                    .'<div class="users-user-info">'
                    .'<a href="'.$url.'" class="users-user-name">'.$name.'</a>'
                    .'<span class="users-user-email">'.$email.'</span>'
                    .'</div>'
                    .'</div>';
            })
            ->filterColumn('name', function (QueryBuilder $query, string $keyword) {
                $query->where(function (QueryBuilder $q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('username', fn (User $user) => '<code>'.e($user->username).'</code>')
            ->editColumn('roles', function (User $user) {
                $roleLower = strtolower((string) $user->roles);
                $roleClass = str_contains($roleLower, 'admin')
                    ? 'admin'
                    : (str_contains($roleLower, 'ka.') || str_contains($roleLower, 'manager') ? 'manager' : 'user');
                $roleIcon = $roleClass === 'admin'
                    ? 'bi-shield-check'
                    : ($roleClass === 'manager' ? 'bi-person-gear' : 'bi-person');

                return '<span class="users-role '.$roleClass.'">'
                    .'<i class="bi '.$roleIcon.'"></i> '.e(ucwords((string) $user->roles))
                    .'</span>';
            })
            ->addColumn('status', fn () => '<span class="users-status active"><span class="users-status-dot"></span> Active</span>')
            ->editColumn('created_at', fn (User $user) => $user->created_at?->format('d M Y') ?? '-')
            ->addColumn('action', fn (User $user) => view('layouts.pages.users.action', compact('user'))->render())
            ->rawColumns(['name', 'username', 'roles', 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (filled($role = $this->request()->get('role'))) {
            $query->where('roles', $role);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $ajaxUrl = route('users.index', array_filter([
            'role' => $this->request()->get('role'),
        ]), false);

        return $this->builder()
            ->setTableId('users-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax($ajaxUrl)
            ->orderBy(5, 'desc')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ user',
                    'infoEmpty' => 'Menampilkan 0 data',
                    'zeroRecords' => 'Data user tidak ditemukan',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#')
                ->width(50)
                ->searchable(false)
                ->orderable(false),
            Column::make('name')->title('User'),
            Column::make('username')->title('Username'),
            Column::make('roles')->title('Role'),
            Column::computed('status')
                ->title('Status')
                ->searchable(false)
                ->orderable(false),
            Column::make('created_at')->title('Joined'),
            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('users-th-actions'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_'.date('YmdHis');
    }
}
