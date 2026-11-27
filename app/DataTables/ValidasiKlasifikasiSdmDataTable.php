<?php

namespace App\DataTables;

use App\Models\RiwayatKlasifikasiSdm;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiKlasifikasiSdmDataTable extends DataTable
{
    protected ?string $status = RiwayatKlasifikasiSdm::STATUS_DIAJUKAN;
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
            ->addColumn('checkbox', function (RiwayatKlasifikasiSdm $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-klasifikasi-sdm">';
            })
            ->addColumn('nip', fn (RiwayatKlasifikasiSdm $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatKlasifikasiSdm $item) => e($item->pegawai?->nama ?? '-'))
            ->editColumn('tmt_jabatan', fn (RiwayatKlasifikasiSdm $item) => $item->tmt_jabatan ? $item->tmt_jabatan->format('d M Y') : '-')
            ->addColumn('nama_jabatan', fn (RiwayatKlasifikasiSdm $item) => '<span class="fw-semibold">' . e($item->klasifikasiSdm?->nama ?? '-') . '</span>')
            ->editColumn('tanggal_sk', fn (RiwayatKlasifikasiSdm $item) => $item->tanggal_sk ? $item->tanggal_sk->format('d M Y') : '-')
            ->editColumn('nomor_sk', fn (RiwayatKlasifikasiSdm $item) => e($item->nomor_sk ?? '-'))
            ->editColumn('file_jabatan', function (RiwayatKlasifikasiSdm $item) {
                if (! $item->file_jabatan) {
                    return '<span class="text-muted">-</span>';
                }

                return '<span class="badge bg-light text-dark border"><i class="bi bi-file-earmark-pdf text-danger me-1"></i>' . e(basename($item->file_jabatan)) . '</span>';
            })
            ->editColumn('tanggal_diajukan', fn (RiwayatKlasifikasiSdm $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatKlasifikasiSdm $item) {
                $showUrl = route('validasi-klasifikasi-sdm.show', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 30px; height: 30px;"
                            title="Detail Validasi Klasifikasi SDM">
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
            ->filterColumn('nama_jabatan', function ($query, $keyword) {
                $query->whereHas('klasifikasiSdm', fn ($q) => $q->where('nama', 'like', "%{$keyword}%"));
            })
            ->rawColumns(['checkbox', 'nama_jabatan', 'file_jabatan', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatKlasifikasiSdm $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'klasifikasiSdm'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($unitIds !== null, function ($q) use ($unitIds) {
                $q->whereHas('pegawai', fn ($pq) => $pq->whereIn('unit_kerja_id', $unitIds));
            })
            ->when($this->klasifikasiSdmId, fn ($q) => $q->where('klasifikasi_sdm_id', $this->klasifikasiSdmId))
            ->orderByDesc('tanggal_diajukan')
            ->orderByDesc('id');
    }

    public function html(): HtmlBuilder
    {
        $ajaxUrl = route('validasi-klasifikasi-sdm.index', [], false);

        return $this->builder()
            ->setTableId('validasi-klasifikasi-sdm-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaKlasifikasiSdm").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikKlasifikasiSdm").val();
                    d.status = $("#filterStatusKlasifikasiSdm").val();
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
                ->title('<input type="checkbox" id="checkAllValidasiKlasifikasiSdm" class="form-check-input">')
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
            Column::make('tmt_jabatan')
                ->title('TMT Jabatan')
                ->width(95)
                ->addClass('text-center text-nowrap'),
            Column::make('nama_jabatan')
                ->title('Nama Jabatan'),
            Column::make('tanggal_sk')
                ->title('Tgl. SK')
                ->width(95)
                ->addClass('text-center text-nowrap'),
            Column::make('nomor_sk')
                ->title('No. SK')
                ->width(120),
            Column::make('file_jabatan')
                ->title('File Jabatan')
                ->width(140),
            Column::make('tanggal_diajukan')
                ->title('Tgl. Diajukan')
                ->width(100)
                ->addClass('text-center text-nowrap'),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(65)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'ValidasiKlasifikasiSdm_' . date('YmdHis');
    }
}
