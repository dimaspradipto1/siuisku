<?php

namespace App\DataTables;

use App\Models\GelarAkademik;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GelarAkademikDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('gelar', function (GelarAkademik $item) {
                return '<span class="fw-semibold">' . e($item->gelar) . '</span>';
            })
            ->addColumn('action', function (GelarAkademik $item) {
                $updateUrl = route('gelar-akademik.update', $item);
                $deleteUrl = route('gelar-akademik.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-gelar"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-gelar="' . e($item->gelar) . '"
                            data-nama="' . e($item->nama) . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus gelar akademik ini?\')">
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
            ->rawColumns(['gelar', 'action'])
            ->setRowId('id');
    }

    public function query(GelarAkademik $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('gelar-akademik-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('gelar-akademik.index', [], false))
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
                    'emptyTable' => 'Belum ada data gelar akademik',
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
            Column::make('gelar')
                ->title('Gelar')
                ->width(140)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Nama Gelar'),
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
        return 'GelarAkademik_' . date('YmdHis');
    }
}
