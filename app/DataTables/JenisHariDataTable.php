<?php

namespace App\DataTables;

use App\Models\JenisHari;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JenisHariDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (JenisHari $item) {
                return '<span class="fw-semibold">' . $item->kode . '</span>';
            })
            ->editColumn('jenis_hari', function (JenisHari $item) {
                if ($item->jenis_hari === 'Efektif') {
                    return '<span class="badge bg-success-subtle text-success">' . e($item->jenis_hari) . '</span>';
                }

                return '<span class="badge bg-secondary-subtle text-secondary">' . e($item->jenis_hari) . '</span>';
            })
            ->addColumn('action', function (JenisHari $item) {
                $updateUrl = route('jenis-hari.update', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-hari"
                            style="width: 32px; height: 32px;"
                            title="Edit Jenis Hari"
                            data-id="' . $item->id . '"
                            data-kode="' . $item->kode . '"
                            data-nama="' . e($item->nama) . '"
                            data-jenis-hari="' . e($item->jenis_hari) . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'jenis_hari', 'action'])
            ->setRowId('id');
    }

    public function query(JenisHari $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('jenis-hari-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('jenis-hari.index', [], false))
            ->pageLength(10)
            ->parameters([
                'ordering' => false,
                'paging' => false,
                'searching' => false,
                'info' => false,
                'language' => [
                    'emptyTable' => 'Data kosong',
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
                ->title('Nama Hari'),
            Column::make('jenis_hari')
                ->title('Jenis Hari')
                ->width(180),
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
        return 'JenisHari_' . date('YmdHis');
    }
}
