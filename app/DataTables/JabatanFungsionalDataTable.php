<?php

namespace App\DataTables;

use App\Models\JabatanFungsional;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JabatanFungsionalDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (JabatanFungsional $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->addColumn('pangkat_label', function (JabatanFungsional $item) {
                if (! $item->pangkat) {
                    return '<span class="text-muted">-</span>';
                }

                return e("{$item->pangkat->pangkat} - {$item->pangkat->nama_golongan}");
            })
            ->editColumn('referensi_sister', function (JabatanFungsional $item) {
                return $item->referensi_sister ? e($item->referensi_sister) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function (JabatanFungsional $item) {
                $editUrl = route('jabatan-fungsional.edit', $item);
                $deleteUrl = route('jabatan-fungsional.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <a href="' . e($editUrl) . '"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 32px; height: 32px;"
                            title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus jabatan fungsional ini?\')">
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
            ->rawColumns(['kode', 'pangkat_label', 'referensi_sister', 'action'])
            ->setRowId('id');
    }

    public function query(JabatanFungsional $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['klasifikasiSdm', 'pangkat'])
            ->orderBy('kode')
            ->orderBy('id');

        if ($klasifikasiSdmId = request('klasifikasi_sdm_id')) {
            $query->where('klasifikasi_sdm_id', $klasifikasiSdmId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jabatan-fungsional-table')
            ->columns($this->getColumns())
            ->minifiedAjax(
                route('jabatan-fungsional.index', [], false),
                'data.klasifikasi_sdm_id = $("#filterKlasifikasiSdm").val();'
            )
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
                    'emptyTable' => 'Belum ada data jabatan fungsional',
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
                ->width(80)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Jabatan Fungsional'),
            Column::computed('pangkat_label')
                ->title('Golongan/Pangkat')
                ->searchable(false)
                ->orderable(false),
            Column::make('angka_kredit')
                ->title('Angka Kredit')
                ->width(105)
                ->addClass('text-center text-nowrap'),
            Column::make('usia_pensiun')
                ->title('Usia Pensiun')
                ->width(100)
                ->addClass('text-center text-nowrap'),
            Column::make('referensi_sister')
                ->title('Referensi SISTER')
                ->width(160)
                ->addClass('text-nowrap'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(110)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'JabatanFungsional_' . date('YmdHis');
    }
}
