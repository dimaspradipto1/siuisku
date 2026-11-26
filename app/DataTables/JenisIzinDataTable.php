<?php

namespace App\DataTables;

use App\Models\JenisIzin;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisIzinDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (JenisIzin $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->addColumn('status_presensi', function (JenisIzin $item) {
                return $item->jenisKehadiran ? e($item->jenisKehadiran->nama) : '<span class="text-muted">-</span>';
            })
            ->editColumn('maksimal_hari', function (JenisIzin $item) {
                return $item->maksimal_hari !== null ? (string) $item->maksimal_hari : '';
            })
            ->editColumn('potong_cuti', function (JenisIzin $item) {
                if ($item->potong_cuti) {
                    return '<span class="text-success fw-bold" title="Potong Cuti"><i class="fa-solid fa-check"></i></span>';
                }

                return '<span class="text-danger fw-bold" title="Tidak Potong Cuti"><i class="fa-solid fa-xmark"></i></span>';
            })
            ->addColumn('action', function (JenisIzin $item) {
                $updateUrl = route('jenis-izin.update', $item);
                $deleteUrl = route('jenis-izin.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-izin"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-jenis-kehadiran-id="' . ($item->jenis_kehadiran_id ?? '') . '"
                            data-maksimal-hari="' . ($item->maksimal_hari ?? '') . '"
                            data-potong-cuti="' . ($item->potong_cuti ? '1' : '0') . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus jenis izin ini?\')">
                            ' . $csrf . $method . '
                            <button type="submit"
                                class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;"
                                title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'status_presensi', 'potong_cuti', 'action'])
            ->setRowId('id');
    }

    public function query(JenisIzin $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('jenisKehadiran')
            ->orderBy('kode')
            ->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-izin-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenis-izin.index', [], false))
            ->pageLength(25)
            ->lengthMenu([[10, 25, 50, 100], [10, 25, 50, 100]])
            ->parameters([
                'ordering' => false,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                    'infoFiltered' => '(disaring dari _MAX_ total data)',
                    'zeroRecords' => 'Tidak ada data yang cocok',
                    'emptyTable' => 'Data kosong',
                    'paginate' => [
                        'first' => 'Pertama',
                        'last' => 'Terakhir',
                        'next' => 'Berikutnya',
                        'previous' => 'Sebelumnya',
                    ],
                ],
            ])
            ->addTableClass('table table-hover align-middle w-100');
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->width(50)
                ->addClass('text-center'),
            Column::make('kode')
                ->title('Kode')
                ->width(85)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Jenis Izin'),
            Column::computed('status_presensi')
                ->title('Status Presensi')
                ->searchable(false)
                ->orderable(false)
                ->width(190),
            Column::make('maksimal_hari')
                ->title('Maksimal (hari)')
                ->width(115)
                ->addClass('text-center text-nowrap'),
            Column::make('potong_cuti')
                ->title('Potong Cuti')
                ->width(95)
                ->addClass('text-center'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(105)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'JenisIzin_' . date('YmdHis');
    }
}
