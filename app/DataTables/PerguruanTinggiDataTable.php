<?php

namespace App\DataTables;

use App\Models\PerguruanTinggi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PerguruanTinggiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (PerguruanTinggi $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->editColumn('alamat', function (PerguruanTinggi $item) {
                return $item->alamat ? e($item->alamat) : '<span class="text-muted">-</span>';
            })
            ->editColumn('no_telp', function (PerguruanTinggi $item) {
                return $item->no_telp ? e($item->no_telp) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function (PerguruanTinggi $item) {
                $updateUrl = route('perguruan-tinggi.update', $item);
                $deleteUrl = route('perguruan-tinggi.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-pt"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-alamat="' . e($item->alamat ?? '') . '"
                            data-no-telp="' . e($item->no_telp ?? '') . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus perguruan tinggi ini?\')">
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
            ->rawColumns(['kode', 'alamat', 'no_telp', 'action'])
            ->setRowId('id');
    }

    public function query(PerguruanTinggi $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderByRaw("CASE WHEN kode = '101031' THEN 0 WHEN kode = '051024' THEN 1 ELSE 2 END")
            ->orderBy('nama')
            ->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('perguruan-tinggi-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('perguruan-tinggi.index', [], false))
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
                ->title('Kode')
                ->width(90)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Nama Universitas'),
            Column::make('alamat')
                ->title('Alamat'),
            Column::make('no_telp')
                ->title('No Telp')
                ->width(130)
                ->addClass('text-nowrap'),
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
        return 'PerguruanTinggi_' . date('YmdHis');
    }
}
