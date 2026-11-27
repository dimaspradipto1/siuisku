<?php

namespace App\DataTables;

use App\Models\RiwayatPendidikanFormal;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiPendidikanFormalDataTable extends DataTable
{
    protected ?string $status = RiwayatPendidikanFormal::STATUS_DIAJUKAN;
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
            ->addColumn('checkbox', function (RiwayatPendidikanFormal $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-pendidikan">';
            })
            ->addColumn('nip', fn (RiwayatPendidikanFormal $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (RiwayatPendidikanFormal $item) => e($item->pegawai?->nama ?? '-'))
            ->addColumn('jenjang', function (RiwayatPendidikanFormal $item) {
                if (! $item->jenjangPendidikan) {
                    return '-';
                }

                return '<span class="badge bg-primary-subtle text-primary fw-semibold">' . e($item->jenjangPendidikan->kode) . '</span>';
            })
            ->editColumn('nama_institusi', function (RiwayatPendidikanFormal $item) {
                $institusi = $item->perguruanTinggi?->nama ?? $item->nama_institusi;
                $prodi = $item->program_studi ? '<br><small class="text-muted">' . e($item->program_studi) . '</small>' : '';

                return '<span class="fw-semibold">' . e($institusi) . '</span>' . $prodi;
            })
            ->editColumn('no_ijazah', fn (RiwayatPendidikanFormal $item) => e($item->no_ijazah ?? '-'))
            ->editColumn('tanggal_diajukan', fn (RiwayatPendidikanFormal $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (RiwayatPendidikanFormal $item) {
                $showUrl = route('validasi-pendidikan-formal.show', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 30px; height: 30px;"
                            title="Detail Pendidikan Formal">
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
            ->filterColumn('jenjang', function ($query, $keyword) {
                $query->whereHas('jenjangPendidikan', fn ($q) => $q->where('kode', 'like', "%{$keyword}%")->orWhere('nama', 'like', "%{$keyword}%"));
            })
            ->rawColumns(['checkbox', 'jenjang', 'nama_institusi', 'action'])
            ->setRowId('id');
    }

    public function query(RiwayatPendidikanFormal $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'jenjangPendidikan', 'perguruanTinggi'])
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
        $ajaxUrl = route('validasi-pendidikan-formal.index', [], false);

        return $this->builder()
            ->setTableId('validasi-pendidikan-formal-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaPendidikan").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikPendidikan").val();
                    d.status = $("#filterStatusPendidikan").val();
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
                ->title('<input type="checkbox" id="checkAllValidasiPendidikan" class="form-check-input">')
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
            Column::make('jenjang')
                ->title('Jenjang')
                ->width(85)
                ->addClass('text-center text-nowrap'),
            Column::make('nama_institusi')
                ->title('Nama Institusi'),
            Column::make('no_ijazah')
                ->title('No. Ijazah')
                ->width(140),
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
        return 'ValidasiPendidikanFormal_' . date('YmdHis');
    }
}
