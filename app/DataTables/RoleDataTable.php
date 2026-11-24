<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * @param  QueryBuilder<Role>  $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (Role $role) => '<code>'.e($role->kode).'</code>')
            ->editColumn('nama', fn (Role $role) => '<span class="fw-semibold">'.e($role->nama).'</span>')
            ->editColumn('scope', function (Role $role) {
                $cls = match ($role->scope) {
                    'universitas' => 'bg-primary-subtle text-primary',
                    'unit' => 'bg-success-subtle text-success',
                    default => 'bg-secondary-subtle text-secondary',
                };

                return '<span class="badge '.$cls.'">'.e($role->scopeLabel()).'</span>';
            })
            ->addColumn('permissions_count', fn (Role $role) => '<span class="badge bg-info-subtle text-info">'.$role->permissions_count.' Izin</span>')
            ->addColumn('users_count', fn (Role $role) => '<span class="badge bg-light text-dark border">'.$role->user_roles_count.' Plotting</span>')
            ->addColumn('action', function (Role $role) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';
                $deleteForm = '';

                if ($role->kode !== 'super_admin') {
                    $deleteForm = '
                        <form action="'.route('hak-akses.destroy', $role->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus role '.e($role->nama).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus Role"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    ';
                }

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <a href="'.route('hak-akses.edit', $role->id).'" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Atur Matrix & Edit Role"><i class="fa-solid fa-shield-halved"></i></a>
                        '.$deleteForm.'
                    </div>
                ';
            })
            ->rawColumns(['kode', 'nama', 'scope', 'permissions_count', 'users_count', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()->withCount(['permissions', 'userRoles']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('hak-akses.index', [], false))
            ->orderBy(1, 'asc')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ role',
                    'infoEmpty' => 'Menampilkan 0 data',
                    'zeroRecords' => 'Data role tidak ditemukan',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->addClass('text-center')->width('5%')->orderable(false)->searchable(false),
            Column::make('kode')->title('Kode')->addClass('text-start text-nowrap')->width('14%'),
            Column::make('nama')->title('Nama Role')->addClass('text-start text-nowrap')->width('22%'),
            Column::make('scope')->title('Cakupan OTK (Scope)')->addClass('text-center text-nowrap')->width('20%'),
            Column::computed('permissions_count')->title('Hak Akses')->addClass('text-center text-nowrap')->width('13%'),
            Column::computed('users_count')->title('Pengguna')->addClass('text-center text-nowrap')->width('13%'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width('13%')->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'HakAksesRoles_'.date('YmdHis');
    }
}
