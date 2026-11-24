<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
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
                $url = route('users.show', $user->id);

                return '<div class="d-flex align-items-center gap-2 flex-nowrap">'
                    .'<img src="'.e($user->avatar_url).'" alt="'.e($user->name).'" class="rounded-circle border" style="width: 30px; height: 30px; object-fit: cover; flex-shrink: 0;">'
                    .'<a href="'.$url.'" class="fw-semibold text-decoration-none text-nowrap">'.e($user->name).'</a>'
                    .'</div>';
            })
            ->editColumn('username', fn (User $user) => '<code>'.e($user->username).'</code>')
            ->editColumn('roles', function (User $user) {
                $roleLower = strtolower((string) $user->roles);
                $badgeClass = match (true) {
                    str_contains($roleLower, 'super') => 'bg-danger-subtle text-danger',
                    str_contains($roleLower, 'admin') => 'bg-primary-subtle text-primary',
                    str_contains($roleLower, 'ka.') => 'bg-info-subtle text-info',
                    default => 'bg-secondary-subtle text-secondary',
                };

                return '<span class="badge '.$badgeClass.' px-2 py-1">'.e(ucwords((string) $user->roles)).'</span>';
            })
            ->editColumn('created_at', fn (User $user) => $user->created_at?->format('d M Y') ?? '-')
            ->addColumn('action', function (User $user) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';
                $deleteForm = '';

                if (Auth::id() !== $user->id) {
                    $deleteForm = '
                        <form action="'.route('users.destroy', $user->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus user '.e($user->name).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus Pengguna"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    ';
                }

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <a href="'.route('users.show', $user->id).'" class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Detail Pengguna"><i class="fa-solid fa-eye"></i></a>
                        <a href="'.route('users.edit', $user->id).'" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Edit Pengguna"><i class="fa-solid fa-pen-to-square"></i></a>
                        '.$deleteForm.'
                    </div>
                ';
            })
            ->rawColumns(['name', 'username', 'roles', 'action'])
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
            ->orderBy(1, 'asc')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ pengguna',
                    'infoEmpty' => 'Menampilkan 0 data',
                    'zeroRecords' => 'Data pengguna tidak ditemukan',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('No')
                ->addClass('text-center')
                ->width('4%')
                ->orderable(false)
                ->searchable(false),
            Column::make('name')
                ->title('Nama Pengguna')
                ->addClass('text-start text-nowrap')
                ->width('32%'),
            Column::make('username')
                ->title('Username')
                ->addClass('text-start text-nowrap')
                ->width('10%'),
            Column::make('email')
                ->title('Email')
                ->addClass('text-start text-nowrap')
                ->width('16%'),
            Column::make('roles')
                ->title('Role Akses')
                ->addClass('text-center text-nowrap')
                ->width('18%'),
            Column::make('created_at')
                ->title('Terdaftar')
                ->addClass('text-center text-nowrap')
                ->width('10%'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width('10%')
                ->addClass('text-center text-nowrap'),
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
