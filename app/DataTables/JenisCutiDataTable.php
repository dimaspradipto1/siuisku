<?php

namespace App\DataTables;

use App\Models\JenisCuti;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisCutiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (JenisCuti $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->editColumn('format_nomor_surat', function (JenisCuti $item) {
                return $item->format_nomor_surat
                    ? '<code>' . e($item->format_nomor_surat) . '</code>'
                    : '<span class="text-muted">-</span>';
            })
            ->editColumn('keterangan', function (JenisCuti $item) {
                return $item->keterangan ? e($item->keterangan) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function (JenisCuti $item) {
                $updateUrl = route('jenis-cuti.update', $item);
                $deleteUrl = route('jenis-cuti.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $editBtn = '
                    <button type="button"
                        class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-cuti"
                        style="width: 32px; height: 32px;"
                        title="Edit"
                        data-id="' . $item->id . '"
                        data-kode="' . e($item->kode) . '"
                        data-nama="' . e($item->nama) . '"
                        data-standar-cuti="' . $item->standar_cuti . '"
                        data-format-nomor-surat="' . e($item->format_nomor_surat ?? '') . '"
                        data-keterangan="' . e($item->keterangan ?? '') . '"
                        data-action="' . e($updateUrl) . '">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                ';

                $deleteBtn = '';
                if (! $item->is_system) {
                    $deleteBtn = '
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus jenis cuti ini?\')">
                            ' . $csrf . $method . '
                            <button type="submit"
                                class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;"
                                title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    ';
                }

                return '<div class="d-inline-flex align-items-center justify-content-center gap-1">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['kode', 'format_nomor_surat', 'keterangan', 'action'])
            ->setRowId('id');
    }

    public function query(JenisCuti $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode')->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-cuti-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenis-cuti.index', [], false))
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
                ->title('Nama Jenis Cuti'),
            Column::make('standar_cuti')
                ->title('Standar Cuti (hari)')
                ->width(120)
                ->addClass('text-center text-nowrap'),
            Column::make('format_nomor_surat')
                ->title('Format Nomor Surat')
                ->width(200),
            Column::make('keterangan')
                ->title('Keterangan'),
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
        return 'JenisCuti_' . date('YmdHis');
    }
}
