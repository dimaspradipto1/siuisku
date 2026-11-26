<?php

namespace App\DataTables;

use App\Models\HubunganKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HubunganKerjaDataTable extends DataTable
{
    /**
     * @param  QueryBuilder<HubunganKerja>  $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', fn (HubunganKerja $row) => '<span class="fw-semibold">'.e($row->kode).'</span>')
            ->editColumn('nama', fn (HubunganKerja $row) => e($row->nama))
            ->editColumn('status_aktif', fn (HubunganKerja $row) => $row->status_aktif
                ? '<i class="fa-solid fa-check text-success fs-6" title="Aktif"></i>'
                : '<i class="fa-solid fa-xmark text-danger fs-6" title="Nonaktif"></i>')
            ->editColumn('pegawai_tetap', fn (HubunganKerja $row) => $row->pegawai_tetap
                ? '<i class="fa-solid fa-check text-success fs-6" title="Pegawai Tetap"></i>'
                : '<i class="fa-solid fa-xmark text-danger fs-6" title="Bukan Pegawai Tetap"></i>')
            ->editColumn('jenis_asn', function (HubunganKerja $row) {
                $cls = match ($row->jenis_asn) {
                    'PNS' => 'bg-primary-subtle text-primary',
                    'PPPK' => 'bg-info-subtle text-info',
                    default => 'bg-light text-dark border',
                };

                return '<span class="badge '.$cls.'">'.e($row->jenis_asn).'</span>';
            })
            ->addColumn('action', function (HubunganKerja $row) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';
                $updateUrl = route('hubungan-kerja.update', $row->id);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-hk"
                            style="'.$btnStyle.'"
                            data-id="'.$row->id.'"
                            data-kode="'.e($row->kode).'"
                            data-nama="'.e($row->nama).'"
                            data-status-aktif="'.($row->status_aktif ? '1' : '0').'"
                            data-pegawai-tetap="'.($row->pegawai_tetap ? '1' : '0').'"
                            data-jenis-asn="'.e($row->jenis_asn).'"
                            data-action="'.e($updateUrl).'"
                            title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="'.route('hubungan-kerja.destroy', $row->id).'" method="POST" style="display: inline" class="form-action-delete" onsubmit="return confirm(\'Yakin ingin menghapus '.e($row->nama).'?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="'.$btnStyle.'" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'status_aktif', 'pegawai_tetap', 'jenis_asn', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<HubunganKerja>
     */
    public function query(HubunganKerja $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('hubungan-kerja-table')
            ->addTableClass('table table-hover align-middle w-100 mb-0')
            ->columns($this->getColumns())
            ->minifiedAjax(route('hubungan-kerja.index', [], false))
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
                    'zeroRecords' => 'Data Hubungan Kerja tidak ditemukan',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->addClass('text-center')->width('5%')->orderable(false)->searchable(false),
            Column::make('kode')->title('Kode')->addClass('text-center text-nowrap')->width('10%'),
            Column::make('nama')->title('Nama Hubungan Kerja')->addClass('text-start'),
            Column::make('status_aktif')->title('Status Aktif')->addClass('text-center text-nowrap')->width('12%'),
            Column::make('pegawai_tetap')->title('Pegawai Tetap')->addClass('text-center text-nowrap')->width('13%'),
            Column::make('jenis_asn')->title('Jenis ASN')->addClass('text-center text-nowrap')->width('12%'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width('12%')->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'HubunganKerja_'.date('YmdHis');
    }
}
