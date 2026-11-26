<?php

namespace App\DataTables;

use App\Models\JenjangPendidikan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenjangPendidikanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (JenjangPendidikan $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->editColumn('nama_en', function (JenjangPendidikan $item) {
                return $item->nama_en ? e($item->nama_en) : '<span class="text-muted">-</span>';
            })
            ->editColumn('is_perguruan_tinggi', function (JenjangPendidikan $item) {
                if ($item->is_perguruan_tinggi) {
                    return '<span class="text-success fw-bold" title="Perguruan Tinggi"><i class="fa-solid fa-check"></i></span>';
                }

                return '<span class="text-danger fw-bold" title="Bukan Perguruan Tinggi"><i class="fa-solid fa-xmark"></i></span>';
            })
            ->editColumn('is_pasca_sarjana', function (JenjangPendidikan $item) {
                if ($item->is_pasca_sarjana) {
                    return '<span class="text-success fw-bold" title="Pasca Sarjana"><i class="fa-solid fa-check"></i></span>';
                }

                return '<span class="text-danger fw-bold" title="Bukan Pasca Sarjana"><i class="fa-solid fa-xmark"></i></span>';
            })
            ->addColumn('action', function (JenjangPendidikan $item) {
                $updateUrl = route('jenjang-pendidikan.update', $item);
                $deleteUrl = route('jenjang-pendidikan.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-jenjang"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-nama-en="' . e($item->nama_en ?? '') . '"
                            data-urutan="' . $item->urutan . '"
                            data-pt="' . ($item->is_perguruan_tinggi ? '1' : '0') . '"
                            data-pasca="' . ($item->is_pasca_sarjana ? '1' : '0') . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus jenjang pendidikan ini?\')">
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
            ->rawColumns(['kode', 'nama_en', 'is_perguruan_tinggi', 'is_pasca_sarjana', 'action'])
            ->setRowId('id');
    }

    public function query(JenjangPendidikan $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('urutan')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenjang-pendidikan-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenjang-pendidikan.index', [], false))
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
                ->width(45)
                ->addClass('text-center'),
            Column::make('kode')
                ->title('Jenjang')
                ->width(85)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Nama Jenjang Pendidikan'),
            Column::make('nama_en')
                ->title('Nama Jenjang (EN)'),
            Column::make('urutan')
                ->title('Urutan')
                ->width(70)
                ->addClass('text-center'),
            Column::make('is_perguruan_tinggi')
                ->title('Perguruan Tinggi?')
                ->width(105)
                ->addClass('text-center'),
            Column::make('is_pasca_sarjana')
                ->title('Pasca Sarjana?')
                ->width(95)
                ->addClass('text-center'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'JenjangPendidikan_' . date('YmdHis');
    }
}
