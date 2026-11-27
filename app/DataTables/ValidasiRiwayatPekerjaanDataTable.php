<?php

namespace App\DataTables;

use App\Models\RiwayatPekerjaan;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiRiwayatPekerjaanDataTable extends DataTable
{
    protected ?string $status = RiwayatPekerjaan::STATUS_DIAJUKAN;
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
            ->addColumn('checkbox', function (RiwayatPekerjaan $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-pekerjaan">';
            })
            ->addColumn('nip', fn (RiwayatPekerjaan $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatPekerjaan $item) => e($item->pegawai?->nama ?? '-'))
            ->editColumn('nama_instansi', fn (RiwayatPekerjaan $item) => '<span class="fw-semibold">' . e($item->nama_instansi) . '</span>')
            ->editColumn('jenis_pekerjaan', fn (RiwayatPekerjaan $item) => e($item->pekerjaan?->nama ?? $item->jenis_pekerjaan))
            ->editColumn('area_pekerjaan', function (RiwayatPekerjaan $item) {
                $cls = $item->area_pekerjaan === 'Luar Negeri'
                    ? 'bg-primary-subtle text-primary'
                    : 'bg-secondary-subtle text-secondary';

                return '<span class="badge ' . $cls . '">' . e($item->area_pekerjaan) . '</span>';
            })
            ->editColumn('tanggal_diajukan', fn (RiwayatPekerjaan $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatPekerjaan $item) {
                $showUrl = route('validasi-riwayat-pekerjaan.show', $item);

                $quickButtons = '';
                if ($item->status === RiwayatPekerjaan::STATUS_DIAJUKAN) {
                    $quickButtons = '
                        <button type="button"
                            class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-pekerjaan"
                            style="width: 26px; height: 26px;"
                            title="Setujui"
                            data-id="' . $item->id . '"
                            data-action="disetujui">
                            <i class="fa-solid fa-check"></i>
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-danger text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-pekerjaan"
                            style="width: 26px; height: 26px;"
                            title="Tolak"
                            data-id="' . $item->id . '"
                            data-action="ditolak">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-pekerjaan"
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
                            title="Detail Riwayat Pekerjaan">
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
            ->rawColumns(['checkbox', 'nama_instansi', 'area_pekerjaan', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatPekerjaan $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'pekerjaan'])
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
        $ajaxUrl = route('validasi-riwayat-pekerjaan.index', [], false);

        return $this->builder()
            ->setTableId('validasi-riwayat-pekerjaan-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaPekerjaan").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikPekerjaan").val();
                    d.status = $("#filterStatusPekerjaan").val();
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
                ->title('<input type="checkbox" id="checkAllValidasiPekerjaan" class="form-check-input">')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(38)
                ->addClass('text-center'),
            Column::make('nip')
                ->title('NIP')
                ->width(110)
                ->addClass('text-nowrap'),
            Column::make('nama_pegawai')
                ->title('Nama Pegawai'),
            Column::make('nama_instansi')
                ->title('Nama Instansi'),
            Column::make('jenis_pekerjaan')
                ->title('Jenis Pekerjaan'),
            Column::make('area_pekerjaan')
                ->title('Area Pekerjaan')
                ->width(115)
                ->addClass('text-center text-nowrap'),
            Column::make('tanggal_diajukan')
                ->title('Tgl. Diajukan')
                ->width(100)
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
        return 'ValidasiRiwayatPekerjaan_' . date('YmdHis');
    }
}
