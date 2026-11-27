<?php

namespace App\DataTables;

use App\Models\RiwayatDiklat;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiDiklatDataTable extends DataTable
{
    protected ?string $status = RiwayatDiklat::STATUS_DIAJUKAN;
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
            ->addColumn('checkbox', function (RiwayatDiklat $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-diklat">';
            })
            ->addColumn('nip', fn (RiwayatDiklat $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatDiklat $item) => e($item->pegawai?->nama ?? '-'))
            ->editColumn('nama_diklat', fn (RiwayatDiklat $item) => '<span class="fw-semibold">' . e($item->nama_diklat) . '</span>')
            ->editColumn('jenis_diklat', fn (RiwayatDiklat $item) => e($item->jenis_diklat))
            ->editColumn('penyelenggara', fn (RiwayatDiklat $item) => e($item->penyelenggara))
            ->editColumn('tahun', fn (RiwayatDiklat $item) => (string) $item->tahun)
            ->editColumn('tanggal_diajukan', fn (RiwayatDiklat $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatDiklat $item) {
                $showUrl = route('validasi-diklat.show', $item);

                $quickButtons = '';
                if ($item->status === RiwayatDiklat::STATUS_DIAJUKAN) {
                    $quickButtons = '
                        <button type="button"
                            class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-diklat"
                            style="width: 26px; height: 26px;"
                            title="Setujui"
                            data-id="' . $item->id . '"
                            data-action="disetujui">
                            <i class="fa-solid fa-check"></i>
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-danger text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-diklat"
                            style="width: 26px; height: 26px;"
                            title="Tolak"
                            data-id="' . $item->id . '"
                            data-action="ditolak">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-diklat"
                            style="width: 26px; height: 26px;"
                            title="Tangguhkan"
                            data-id="' . $item->id . '"
                            data-action="ditangguhkan">
                            <i class="fa-solid fa-pause"></i>
                        </button>
                    ';
                }

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        ' . $quickButtons . '
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 26px; height: 26px;"
                            title="Detail Validasi Diklat">
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
            ->rawColumns(['checkbox', 'nama_diklat', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatDiklat $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm'])
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
        $ajaxUrl = route('validasi-diklat.index', [], false);

        return $this->builder()
            ->setTableId('validasi-diklat-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaDiklat").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikDiklat").val();
                    d.status = $("#filterStatusDiklat").val();
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
                ->title('<input type="checkbox" id="checkAllValidasiDiklat" class="form-check-input">')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(38)
                ->addClass('text-center'),
            Column::make('nip')
                ->title('NIP')
                ->width(105)
                ->addClass('text-nowrap'),
            Column::make('nama_pegawai')
                ->title('Nama Pegawai'),
            Column::make('nama_diklat')
                ->title('Nama Diklat'),
            Column::make('jenis_diklat')
                ->title('Jenis Diklat')
                ->width(100),
            Column::make('penyelenggara')
                ->title('Penyelenggara'),
            Column::make('tahun')
                ->title('Tahun')
                ->width(65)
                ->addClass('text-center text-nowrap'),
            Column::make('tanggal_diajukan')
                ->title('Tgl. Diajukan')
                ->width(95)
                ->addClass('text-center text-nowrap'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(130)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'ValidasiDiklat_' . date('YmdHis');
    }
}
