<?php

namespace App\DataTables;

use App\Models\RiwayatPangkat;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiPangkatDataTable extends DataTable
{
    protected ?string $status = RiwayatPangkat::STATUS_DIAJUKAN;
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
            ->addColumn('checkbox', function (RiwayatPangkat $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-pangkat">';
            })
            ->addColumn('nip', fn (RiwayatPangkat $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatPangkat $item) => e($item->pegawai?->nama ?? '-'))
            ->addColumn('nama_pangkat', function (RiwayatPangkat $item) {
                if (! $item->pangkat) {
                    return '-';
                }

                return '<span class="fw-semibold">' . e("{$item->pangkat->nama_golongan} ({$item->pangkat->pangkat})") . '</span>';
            })
            ->editColumn('tmt_pangkat', fn (RiwayatPangkat $item) => $item->tmt_pangkat ? $item->tmt_pangkat->format('d M Y') : '-')
            ->addColumn('jenis_sk', fn (RiwayatPangkat $item) => e($item->jenisSk?->nama ?? '-'))
            ->editColumn('file_pangkat', function (RiwayatPangkat $item) {
                if (! $item->file_pangkat) {
                    return '<span class="text-muted">-</span>';
                }

                return '<span class="badge bg-light text-dark border"><i class="bi bi-file-earmark-pdf text-danger me-1"></i>' . e(basename($item->file_pangkat)) . '</span>';
            })
            ->editColumn('tanggal_diajukan', fn (RiwayatPangkat $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatPangkat $item) {
                $showUrl = route('validasi-pangkat.show', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 30px; height: 30px;"
                            title="Detail Validasi Pangkat">
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
            ->filterColumn('nama_pangkat', function ($query, $keyword) {
                $query->whereHas('pangkat', fn ($q) => $q->where('nama_golongan', 'like', "%{$keyword}%")->orWhere('pangkat', 'like', "%{$keyword}%"));
            })
            ->filterColumn('jenis_sk', function ($query, $keyword) {
                $query->whereHas('jenisSk', fn ($q) => $q->where('nama', 'like', "%{$keyword}%"));
            })
            ->rawColumns(['checkbox', 'nama_pangkat', 'file_pangkat', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatPangkat $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'pangkat', 'jenisSk'])
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
        $ajaxUrl = route('validasi-pangkat.index', [], false);

        return $this->builder()
            ->setTableId('validasi-pangkat-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaPangkat").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikPangkat").val();
                    d.status = $("#filterStatusPangkat").val();
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
                ->title('<input type="checkbox" id="checkAllValidasiPangkat" class="form-check-input">')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(40)
                ->addClass('text-center'),
            Column::make('nip')
                ->title('NIP')
                ->width(115)
                ->addClass('text-nowrap'),
            Column::make('nama_pegawai')
                ->title('Nama Pegawai'),
            Column::make('nama_pangkat')
                ->title('Nama Pangkat'),
            Column::make('tmt_pangkat')
                ->title('TMT Pangkat')
                ->width(100)
                ->addClass('text-center text-nowrap'),
            Column::make('jenis_sk')
                ->title('Jenis SK')
                ->width(120),
            Column::make('file_pangkat')
                ->title('File Pangkat')
                ->width(150),
            Column::make('tanggal_diajukan')
                ->title('Tgl. Diajukan')
                ->width(105)
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
        return 'ValidasiPangkat_' . date('YmdHis');
    }
}
