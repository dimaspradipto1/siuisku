<?php

namespace App\DataTables;

use App\Models\KabupatenKota;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KabupatenKotaDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (KabupatenKota $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->addColumn('provinsi_nama', function (KabupatenKota $item) {
                return $item->provinsi ? e($item->provinsi->nama) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function (KabupatenKota $item) {
                $updateUrl = route('kabupaten-kota.update', $item);
                $deleteUrl = route('kabupaten-kota.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-kabkota"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-provinsi-id="' . $item->provinsi_id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus kabupaten/kota ini?\')">
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
            ->rawColumns(['kode', 'provinsi_nama', 'action'])
            ->setRowId('id');
    }

    public function query(KabupatenKota $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with('provinsi')
            ->orderBy('kode')
            ->orderBy('id');

        if ($provinsiId = request('provinsi_id')) {
            $query->where('provinsi_id', $provinsiId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kabupaten-kota-table')
            ->columns($this->getColumns())
            ->minifiedAjax(
                route('kabupaten-kota.index', [], false),
                'data.provinsi_id = $("#filterProvinsi").val();'
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
                ->width(100)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Nama Kabupaten / Kota'),
            Column::computed('provinsi_nama')
                ->title('Provinsi')
                ->searchable(false)
                ->orderable(false)
                ->width(210),
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
        return 'KabupatenKota_' . date('YmdHis');
    }
}
