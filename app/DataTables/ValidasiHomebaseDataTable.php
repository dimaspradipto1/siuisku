<?php

namespace App\DataTables;

use App\Models\RiwayatHomebase;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiHomebaseDataTable extends DataTable
{
    protected ?string $status = RiwayatHomebase::STATUS_DIAJUKAN;
    protected ?int $unitKerjaId = null;
    protected ?int $klasifikasiSdmId = null;

    public function withFilters(
        ?string $status,
        ?int $unitKerjaId,
        ?int $klasifikasiSdmId,
    ): static {
        $this->status = $status;
        $this->unitKerjaId = $unitKerjaId;
        $this->klasifikasiSdmId = $klasifikasiSdmId;

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function (RiwayatHomebase $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-homebase">';
            })
            ->addColumn('nip', fn (RiwayatHomebase $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatHomebase $item) => e($item->pegawai?->nama ?? '-'))
            ->editColumn('tmt_tugas', fn (RiwayatHomebase $item) => $item->tmt_tugas ? $item->tmt_tugas->format('d M Y') : '-')
            ->addColumn('homebase', function (RiwayatHomebase $item) {
                if (! $item->homebaseUnit) {
                    return '-';
                }

                return '<span class="fw-semibold">' . e("{$item->homebaseUnit->kode} - {$item->homebaseUnit->nama}") . '</span>';
            })
            ->editColumn('file_sk', function (RiwayatHomebase $item) {
                if (! $item->file_sk) {
                    return '<span class="text-muted">-</span>';
                }

                return '<span class="badge bg-light text-dark border"><i class="bi bi-file-earmark-pdf text-danger me-1"></i>' . e(basename($item->file_sk)) . '</span>';
            })
            ->editColumn('tanggal_diajukan', fn (RiwayatHomebase $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatHomebase $item) {
                $showUrl = route('validasi-homebase.show', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 30px; height: 30px;"
                            title="Detail Validasi Homebase">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>
                ';
            })
            ->filterColumn('nip', function ($query, $keyword) {
                $query->whereHas('pegawai', fn ($q) => $q->where('nip', 'like', "%{$keyword}%"));
            })
            ->filterColumn('nama_pegawai', function ($query, $keyword) {
                $query->whereHas('pegawai', fn ($q) => $q->where('nama', 'like', "%{$keyword}%"));
            })
            ->filterColumn('homebase', function ($query, $keyword) {
                $query->whereHas('homebaseUnit', fn ($q) => $q->where('nama', 'like', "%{$keyword}%")->orWhere('kode', 'like', "%{$keyword}%"));
            })
            ->rawColumns(['checkbox', 'homebase', 'file_sk', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatHomebase $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'homebaseUnit'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($unitIds !== null, function ($q) use ($unitIds) {
                $q->whereHas('pegawai', fn ($pq) => $pq->whereIn('unit_kerja_id', $unitIds));
            })
            ->when($this->klasifikasiSdmId, function ($q) {
                $q->whereHas('pegawai', fn ($pq) => $pq->where('klasifikasi_sdm_id', $this->klasifikasiSdmId));
            })
            ->orderByDesc('tanggal_diajukan')
            ->orderByDesc('id');
    }

    public function html(): HtmlBuilder
    {
        $ajaxUrl = route('validasi-homebase.index', [], false);

        return $this->builder()
            ->setTableId('validasi-homebase-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaHomebase").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikHomebase").val();
                    d.status = $("#filterStatusHomebase").val();
                }',
            ])
            ->pageLength(10)
            ->lengthMenu([[10, 25, 50, 100], [10, 25, 50, 100]])
            ->parameters([
                'ordering' => false,
                'language' => [
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                    'infoFiltered' => '(disaring dari _MAX_ total data)',
                    'zeroRecords' => 'Data kosong',
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
            Column::computed('checkbox')
                ->title('<input type="checkbox" id="checkAllValidasiHomebase" class="form-check-input">')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(40)
                ->addClass('text-center'),
            Column::make('nip')
                ->title('NIP')
                ->width(120)
                ->addClass('text-nowrap'),
            Column::make('nama_pegawai')
                ->title('Nama Pegawai'),
            Column::make('tmt_tugas')
                ->title('TMT Tugas')
                ->width(105)
                ->addClass('text-center text-nowrap'),
            Column::make('homebase')
                ->title('Homebase'),
            Column::make('file_sk')
                ->title('File SK')
                ->width(160),
            Column::make('tanggal_diajukan')
                ->title('Tgl. Diajukan')
                ->width(110)
                ->addClass('text-center text-nowrap'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(70)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'ValidasiHomebase_' . date('YmdHis');
    }
}
