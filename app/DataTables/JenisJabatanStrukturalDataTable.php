<?php

namespace App\DataTables;

use App\Models\JenisJabatanStruktural;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisJabatanStrukturalDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<JenisJabatanStruktural>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (JenisJabatanStruktural $row) => '<span class="fw-semibold">'.e($row->kode).'</span>')
            ->editColumn('nama', fn (JenisJabatanStruktural $row) => e($row->nama))
            ->addColumn('action', function (JenisJabatanStruktural $row) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <a href="'.route('jenis-jabatan-struktural.edit', $row->id).'" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="'.route('jenis-jabatan-struktural.destroy', $row->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus '.e($row->nama).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<JenisJabatanStruktural>
     */
    public function query(JenisJabatanStruktural $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('urutan')->orderBy('id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-jabatan-struktural-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenis-jabatan-struktural.index', [], false))
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
                    'zeroRecords' => 'Data tidak ditemukan',
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
                ->width('6%')
                ->orderable(false)
                ->searchable(false),
            Column::make('kode')
                ->title('Kode')
                ->addClass('text-center text-nowrap')
                ->width('14%'),
            Column::make('nama')
                ->title('Jenis Jabatan Struktural')
                ->addClass('text-start'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width('12%')
                ->addClass('text-center text-nowrap'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'JenisJabatanStruktural_'.date('YmdHis');
    }
}
