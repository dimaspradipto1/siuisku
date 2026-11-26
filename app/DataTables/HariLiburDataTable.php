<?php

namespace App\DataTables;

use App\Models\HariLibur;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HariLiburDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('tanggal_mulai', function (HariLibur $item) {
                return $item->tanggal_mulai
                    ? e($item->tanggal_mulai->locale('id')->translatedFormat('d M Y'))
                    : '-';
            })
            ->editColumn('tanggal_selesai', function (HariLibur $item) {
                return $item->tanggal_selesai
                    ? e($item->tanggal_selesai->locale('id')->translatedFormat('d M Y'))
                    : '<span class="text-muted">-</span>';
            })
            ->editColumn('keterangan', function (HariLibur $item) {
                return $item->keterangan ? e($item->keterangan) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function (HariLibur $item) {
                $updateUrl = route('hari-libur.update', $item);
                $deleteUrl = route('hari-libur.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-libur"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-nama="' . e($item->nama) . '"
                            data-tanggal-mulai="' . ($item->tanggal_mulai?->format('Y-m-d') ?? '') . '"
                            data-tanggal-selesai="' . ($item->tanggal_selesai?->format('Y-m-d') ?? '') . '"
                            data-keterangan="' . e($item->keterangan ?? '') . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus hari libur ini?\')">
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
            ->rawColumns(['tanggal_selesai', 'keterangan', 'action'])
            ->setRowId('id');
    }

    public function query(HariLibur $model): QueryBuilder
    {
        $query = $model->newQuery()->orderBy('tanggal_mulai')->orderBy('id');

        if ($tahun = request('tahun')) {
            $query->whereYear('tanggal_mulai', (int) $tahun);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('hari-libur-table')
            ->columns($this->getColumns())
            ->minifiedAjax(
                route('hari-libur.index', [], false),
                'data.tahun = $("#filterTahun").val();'
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
                    'emptyTable' => 'Belum ada data hari libur pada tahun ini',
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
                ->title('Nama Hari Libur'),
            Column::make('tanggal_mulai')
                ->title('Tanggal Mulai')
                ->width(145)
                ->addClass('text-center text-nowrap'),
            Column::make('tanggal_selesai')
                ->title('Tanggal Selesai')
                ->width(145)
                ->addClass('text-center text-nowrap'),
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
        return 'HariLibur_' . date('YmdHis');
    }
}
