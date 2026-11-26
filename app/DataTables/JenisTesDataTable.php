<?php

namespace App\DataTables;

use App\Models\JenisTes;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisTesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (JenisTes $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->editColumn('nilai_minimal', function (JenisTes $item) {
                return $item->nilai_minimal !== null
                    ? number_format((float) $item->nilai_minimal, 2, '.', '')
                    : '';
            })
            ->editColumn('nilai_maksimal', function (JenisTes $item) {
                return $item->nilai_maksimal !== null
                    ? number_format((float) $item->nilai_maksimal, 2, '.', '')
                    : '';
            })
            ->addColumn('action', function (JenisTes $item) {
                $updateUrl = route('jenis-tes.update', $item);
                $deleteUrl = route('jenis-tes.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-tes"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-nilai-minimal="' . ($item->nilai_minimal !== null ? number_format((float) $item->nilai_minimal, 2, '.', '') : '') . '"
                            data-nilai-maksimal="' . ($item->nilai_maksimal !== null ? number_format((float) $item->nilai_maksimal, 2, '.', '') : '') . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus jenis tes ini?\')">
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
            ->rawColumns(['kode', 'action'])
            ->setRowId('id');
    }

    public function query(JenisTes $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-tes-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenis-tes.index', [], false))
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
                    'emptyTable' => 'Belum ada data jenis tes',
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
                ->title('Jenis Tes'),
            Column::make('nilai_minimal')
                ->title('Nilai Minimal')
                ->width(130)
                ->addClass('text-end text-nowrap'),
            Column::make('nilai_maksimal')
                ->title('Nilai Maksimal')
                ->width(130)
                ->addClass('text-end text-nowrap'),
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
        return 'JenisTes_' . date('YmdHis');
    }
}
