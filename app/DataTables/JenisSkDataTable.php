<?php

namespace App\DataTables;

use App\Models\JenisSk;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisSkDataTable extends DataTable
{
    /**
     * @param  QueryBuilder<JenisSk>  $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (JenisSk $row) => '<span class="fw-semibold">'.e($row->kode).'</span>')
            ->editColumn('nama', fn (JenisSk $row) => e($row->nama))
            ->addColumn('action', function (JenisSk $row) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';
                $updateUrl = route('jenis-sk.update', $row->id);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit"
                            style="'.$btnStyle.'"
                            data-id="'.$row->id.'"
                            data-kode="'.e($row->kode).'"
                            data-nama="'.e($row->nama).'"
                            data-action="'.e($updateUrl).'"
                            title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="'.route('jenis-sk.destroy', $row->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus '.e($row->nama).'?\')">
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
     * @return QueryBuilder<JenisSk>
     */
    public function query(JenisSk $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-sk-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenis-sk.index', [], false))
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
                    'zeroRecords' => 'Data Jenis SK tidak ditemukan',
                ],
            ]);
    }

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
                ->width('20%'),
            Column::make('nama')
                ->title('Jenis SK')
                ->addClass('text-start'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width('14%')
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'JenisSK_'.date('YmdHis');
    }
}
