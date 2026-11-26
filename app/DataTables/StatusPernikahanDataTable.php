<?php

namespace App\DataTables;

use App\Models\StatusPernikahan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StatusPernikahanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('kode', function (StatusPernikahan $item) {
                return '<span class="fw-semibold">' . e($item->kode) . '</span>';
            })
            ->addColumn('action', function (StatusPernikahan $item) {
                $updateUrl = route('status-pernikahan.update', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-nikah"
                            style="width: 32px; height: 32px;"
                            title="Edit"
                            data-id="' . $item->id . '"
                            data-kode="' . e($item->kode) . '"
                            data-nama="' . e($item->nama) . '"
                            data-action="' . e($updateUrl) . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'action'])
            ->setRowId('id');
    }

    public function query(StatusPernikahan $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('kode');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('status-pernikahan-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('status-pernikahan.index', [], false))
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
                ->width(100)
                ->addClass('text-center text-nowrap'),
            Column::make('nama')
                ->title('Status'),
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
        return 'StatusPernikahan_' . date('YmdHis');
    }
}
