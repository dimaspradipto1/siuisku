<?php

namespace App\DataTables;

use App\Models\JabatanStruktural;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JabatanStrukturalDataTable extends DataTable
{
    /**
     * @param  QueryBuilder<JabatanStruktural>  $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (JabatanStruktural $row) => '<span class="fw-semibold">'.e($row->kode).'</span>')
            ->editColumn('nama', function (JabatanStruktural $row) {
                $badge = $row->is_pimpinan
                    ? ' <span class="badge bg-primary-subtle text-primary ms-1">Pimpinan</span>'
                    : '';

                return '<span class="fw-medium">'.e($row->nama).'</span>'.$badge;
            })
            ->addColumn('parent_nama', fn (JabatanStruktural $row) => $row->parent ? e($row->parent->nama) : '<span class="text-muted">-</span>')
            ->addColumn('unit_nama', fn (JabatanStruktural $row) => $row->unitKerja ? e($row->unitKerja->nama) : '<span class="text-muted">-</span>')
            ->editColumn('is_active', fn (JabatanStruktural $row) => $row->is_active
                ? '<span class="badge bg-success-subtle text-success">Aktif</span>'
                : '<span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>')
            ->addColumn('action', function (JabatanStruktural $row) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <a href="'.route('jabatan-struktural.edit', $row->id).'" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="'.route('jabatan-struktural.destroy', $row->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus '.e($row->nama).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'nama', 'parent_nama', 'unit_nama', 'is_active', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<JabatanStruktural>
     */
    public function query(JabatanStruktural $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['parent', 'unitKerja', 'jenisJabatanStruktural'])
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jabatan-struktural-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jabatan-struktural.index', [], false))
            ->parameters([
                'order' => [],
                'responsive' => true,
                'autoWidth' => false,
                'pageLength' => 25,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 data',
                    'zeroRecords' => 'Data jabatan struktural tidak ditemukan',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->addClass('text-center')->width('5%')->orderable(false)->searchable(false),
            Column::make('kode')->title('Kode')->addClass('text-center text-nowrap')->width('10%'),
            Column::make('nama')->title('Nama Jabatan Struktural')->addClass('text-start'),
            Column::computed('parent_nama')->title('Parent Jabatan Struktural')->addClass('text-start')->width('20%'),
            Column::computed('unit_nama')->title('Unit Kerja')->addClass('text-start')->width('22%'),
            Column::make('is_active')->title('Status')->addClass('text-center text-nowrap')->width('9%'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width('10%')->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'JabatanStruktural_'.date('YmdHis');
    }
}
