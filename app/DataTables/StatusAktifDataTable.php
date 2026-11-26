<?php

namespace App\DataTables;

use App\Models\StatusAktif;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StatusAktifDataTable extends DataTable
{
    /**
     * @param  QueryBuilder<StatusAktif>  $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (StatusAktif $row) => '<span class="fw-semibold">'.e($row->kode).'</span>')
            ->editColumn('nama', fn (StatusAktif $row) => e($row->nama))
            ->editColumn('status_keluar', fn (StatusAktif $row) => $row->status_keluar
                ? '<i class="fa-solid fa-check text-success fs-6" title="Status Keluar"></i>'
                : '<i class="fa-solid fa-xmark text-danger fs-6" title="Bukan Status Keluar"></i>')
            ->addColumn('action', function (StatusAktif $row) {
                if ($row->isSystemStatus()) {
                    return '<span class="text-muted">-</span>';
                }

                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';
                $updateUrl = route('status-aktif.update', $row->id);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-status"
                            style="'.$btnStyle.'"
                            data-id="'.$row->id.'"
                            data-kode="'.e($row->kode).'"
                            data-nama="'.e($row->nama).'"
                            data-status-keluar="'.($row->status_keluar ? '1' : '0').'"
                            data-action="'.e($updateUrl).'"
                            title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="'.route('status-aktif.destroy', $row->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus '.e($row->nama).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'status_keluar', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<StatusAktif>
     */
    public function query(StatusAktif $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('status-aktif-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('status-aktif.index', [], false))
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
                    'zeroRecords' => 'Data Status Aktif tidak ditemukan',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->addClass('text-center')->width('6%')->orderable(false)->searchable(false),
            Column::make('kode')->title('Kode')->addClass('text-center text-nowrap')->width('14%'),
            Column::make('nama')->title('Nama Status Aktif')->addClass('text-start'),
            Column::make('status_keluar')->title('Status Keluar')->addClass('text-center text-nowrap')->width('15%'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width('14%')->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'StatusAktif_'.date('YmdHis');
    }
}
