<?php

namespace App\DataTables;

use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UnitKerjaDataTable extends DataTable
{
    /**
     * @param  QueryBuilder<UnitKerja>  $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (UnitKerja $row) => '<span class="fw-semibold">'.e($row->kode).'</span>')
            ->editColumn('jenis', function (UnitKerja $row) {
                $colors = [
                    'universitas' => 'bg-primary-subtle text-primary',
                    'lembaga' => 'bg-info-subtle text-info',
                    'biro' => 'bg-warning-subtle text-warning',
                    'fakultas' => 'bg-success-subtle text-success',
                    'prodi' => 'bg-secondary-subtle text-secondary',
                    'unit' => 'bg-dark-subtle text-dark',
                ];
                $cls = $colors[$row->jenis] ?? 'bg-secondary-subtle text-secondary';

                return '<span class="badge '.$cls.'">'.e(ucfirst($row->jenis)).'</span>';
            })
            ->addColumn('parent_nama', fn (UnitKerja $row) => $row->parent ? e($row->parent->nama) : '<span class="text-muted">-</span>')
            ->addColumn('action', function (UnitKerja $row) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <a href="'.route('unit-kerja.edit', $row->id).'" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="'.route('unit-kerja.destroy', $row->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus unit '.e($row->nama).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'jenis', 'parent_nama', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<UnitKerja>
     */
    public function query(UnitKerja $model): QueryBuilder
    {
        return $model->newQuery()->with('parent')->orderBy('urutan')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('unit-kerja-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('unit-kerja.index', [], false))
            ->parameters([
                'order' => [],
                'responsive' => true,
                'autoWidth' => false,
                'pageLength' => 25,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ unit kerja',
                    'infoEmpty' => 'Menampilkan 0 data',
                    'zeroRecords' => 'Data unit kerja tidak ditemukan',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->addClass('text-center')->width('5%')->orderable(false)->searchable(false),
            Column::make('kode')->title('Kode')->addClass('text-center text-nowrap')->width('10%'),
            Column::make('nama')->title('Nama Unit Kerja')->addClass('text-start'),
            Column::make('jenis')->title('Jenis')->addClass('text-center text-nowrap')->width('12%'),
            Column::computed('parent_nama')->title('Induk (OTK)')->addClass('text-start')->width('24%'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width('11%')->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'UnitKerja_'.date('YmdHis');
    }
}
