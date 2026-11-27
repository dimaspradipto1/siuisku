<?php

namespace App\DataTables;

use App\Models\RiwayatHubunganKerja;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiHubunganKerjaDataTable extends DataTable
{
    protected ?string $status = RiwayatHubunganKerja::STATUS_DIAJUKAN;
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
            ->addColumn('checkbox', function (RiwayatHubunganKerja $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-hubungan-kerja">';
            })
            ->addColumn('nip', fn (RiwayatHubunganKerja $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatHubunganKerja $item) => e($item->pegawai?->nama ?? '-'))
            ->editColumn('tanggal_mulai', fn (RiwayatHubunganKerja $item) => $item->tanggal_mulai ? $item->tanggal_mulai->format('d M Y') : '-')
            ->editColumn('tanggal_selesai', fn (RiwayatHubunganKerja $item) => $item->tanggal_selesai ? $item->tanggal_selesai->format('d M Y') : '')
            ->addColumn('hubungan_kerja', fn (RiwayatHubunganKerja $item) => '<span class="fw-semibold">' . e($item->hubunganKerja?->nama ?? '-') . '</span>')
            ->editColumn('keterangan', fn (RiwayatHubunganKerja $item) => e($item->keterangan ?? ''))
            ->editColumn('file_hubungan_kerja', function (RiwayatHubunganKerja $item) {
                if (! $item->file_hubungan_kerja) {
                    return '<span class="text-muted">-</span>';
                }

                return '<span class="badge bg-light text-dark border"><i class="bi bi-file-earmark-pdf text-danger me-1"></i>' . e(basename($item->file_hubungan_kerja)) . '</span>';
            })
            ->editColumn('tanggal_diajukan', fn (RiwayatHubunganKerja $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatHubunganKerja $item) {
                $showUrl = route('validasi-hubungan-kerja.show', $item);

                $quickButtons = '';
                if ($item->status === RiwayatHubunganKerja::STATUS_DIAJUKAN) {
                    $quickButtons = '
                        <button type="button"
                            class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-hk"
                            style="width: 28px; height: 28px;"
                            title="Setujui"
                            data-id="' . $item->id . '"
                            data-action="disetujui">
                            <i class="fa-solid fa-check"></i>
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-danger text-white d-inline-flex align-items-center justify-content-center p-0 btn-quick-validasi-hk"
                            style="width: 28px; height: 28px;"
                            title="Tolak"
                            data-id="' . $item->id . '"
                            data-action="ditolak">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    ';
                }

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        ' . $quickButtons . '
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 28px; height: 28px;"
                            title="Detail Validasi Hubungan Kerja">
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
            ->filterColumn('hubungan_kerja', function ($query, $keyword) {
                $query->whereHas('hubunganKerja', fn ($q) => $q->where('nama', 'like', "%{$keyword}%"));
            })
            ->rawColumns(['checkbox', 'hubungan_kerja', 'file_hubungan_kerja', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatHubunganKerja $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'hubunganKerja'])
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
        $ajaxUrl = route('validasi-hubungan-kerja.index', [], false);

        return $this->builder()
            ->setTableId('validasi-hubungan-kerja-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaHubunganKerja").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikHubunganKerja").val();
                    d.status = $("#filterStatusHubunganKerja").val();
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
                ->title('<input type="checkbox" id="checkAllValidasiHubunganKerja" class="form-check-input">')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(40)
                ->addClass('text-center'),
            Column::make('nip')
                ->title('NIP')
                ->width(110)
                ->addClass('text-nowrap'),
            Column::make('nama_pegawai')
                ->title('Nama Pegawai'),
            Column::make('tanggal_mulai')
                ->title('Tgl. Mulai')
                ->width(95)
                ->addClass('text-center text-nowrap'),
            Column::make('tanggal_selesai')
                ->title('Tgl. Selesai')
                ->width(95)
                ->addClass('text-center text-nowrap'),
            Column::make('hubungan_kerja')
                ->title('Hubungan Kerja'),
            Column::make('keterangan')
                ->title('Keterangan'),
            Column::make('file_hubungan_kerja')
                ->title('File Hubungan Kerja')
                ->width(150),
            Column::make('tanggal_diajukan')
                ->title('Tgl. Diajukan')
                ->width(100)
                ->addClass('text-center text-nowrap'),
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
        return 'ValidasiHubunganKerja_' . date('YmdHis');
    }
}
