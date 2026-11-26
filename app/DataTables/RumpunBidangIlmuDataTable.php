<?php

namespace App\DataTables;

use App\Models\RumpunBidangIlmu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RumpunBidangIlmuDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (RumpunBidangIlmu $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->editColumn('nama', function (RumpunBidangIlmu $item) {
                $padding = min((int) $item->level, 3) * 18;
                $isParent = $item->level < 2;
                $weight = $item->level === 0 ? 'fw-bold text-primary' : ($item->level === 1 ? 'fw-semibold' : '');
                $icon = $isParent ? '<i class="bi bi-caret-down-fill me-1 text-muted" style="font-size: 9px;"></i>' : '';

                return '<div style="padding-left: ' . $padding . 'px;" class="' . $weight . '">' . $icon . e($item->nama) . '</div>';
            })
            ->addColumn('parent_nama', function (RumpunBidangIlmu $item) {
                return $item->parent ? e($item->parent->nama) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function (RumpunBidangIlmu $item) {
                $updateUrl = route('rumpun-bidang-ilmu.update', $item);
                $deleteUrl = route('rumpun-bidang-ilmu.destroy', $item);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-add-child-rbi"
                            style="width: 30px; height: 30px;"
                            title="Tambah Sub-Bidang"
                            data-parent-id="' . $item->id . '">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-rbi"
                            style="width: 30px; height: 30px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-parent-id="' . ($item->parent_id ?? '') . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . e($deleteUrl) . '" method="POST" class="d-inline m-0" onsubmit="return confirm(\'Yakin ingin menghapus rumpun bidang ilmu ini?\')">
                            ' . $csrf . $method . '
                            <button type="submit"
                                class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0"
                                style="width: 30px; height: 30px;"
                                title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'nama', 'parent_nama', 'action'])
            ->setRowId('id');
    }

    public function query(RumpunBidangIlmu $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('parent')
            ->orderBy('urutan')
            ->orderBy('kode')
            ->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('rumpun-bidang-ilmu-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('rumpun-bidang-ilmu.index', [], false))
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
                    'emptyTable' => 'Belum ada data rumpun bidang ilmu',
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
                ->width(90)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Nama Bidang'),
            Column::computed('parent_nama')
                ->title('Parent Bidang')
                ->searchable(false)
                ->orderable(false)
                ->width(240),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'RumpunBidangIlmu_' . date('YmdHis');
    }
}
