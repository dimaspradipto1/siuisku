<?php

namespace App\DataTables;

use App\Models\JamKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JamKerjaDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('is_normal', function (JamKerja $item) {
                if ($item->is_normal) {
                    return '<span class="text-success fw-bold" title="Jam Normal"><i class="fa-solid fa-check"></i></span>';
                }

                return '<span class="text-danger fw-bold" title="Bukan Jam Normal"><i class="fa-solid fa-xmark"></i></span>';
            })
            ->editColumn('jam_datang', function (JamKerja $item) {
                return '<span class="fw-semibold">' . e($item->jam_datang) . '</span>';
            })
            ->editColumn('jam_pulang', function (JamKerja $item) {
                return '<span class="fw-semibold">' . e($item->jam_pulang) . '</span>';
            })
            ->addColumn('action', function (JamKerja $item) {
                $updateUrl = route('jam-kerja.update', $item);
                $deleteUrl = route('jam-kerja.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-jamkerja"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-nama="' . e($item->nama) . '"
                            data-is-normal="' . ($item->is_normal ? '1' : '0') . '"
                            data-jam-datang="' . e($item->jam_datang) . '"
                            data-jam-pulang="' . e($item->jam_pulang) . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus jam kerja ini?\')">
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
            ->rawColumns(['is_normal', 'jam_datang', 'jam_pulang', 'action'])
            ->setRowId('id');
    }

    public function query(JamKerja $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jam-kerja-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jam-kerja.index', [], false))
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
            Column::make('nama')
                ->title('Jenis Jam Kerja'),
            Column::make('is_normal')
                ->title('Jam Normal')
                ->width(110)
                ->addClass('text-center'),
            Column::make('jam_datang')
                ->title('Jam Datang')
                ->width(120)
                ->addClass('text-center text-nowrap'),
            Column::make('jam_pulang')
                ->title('Jam Pulang')
                ->width(120)
                ->addClass('text-center text-nowrap'),
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
        return 'JamKerja_' . date('YmdHis');
    }
}
