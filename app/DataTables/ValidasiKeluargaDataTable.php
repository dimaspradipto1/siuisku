<?php

namespace App\DataTables;

use App\Models\KeluargaPegawai;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ValidasiKeluargaDataTable extends DataTable
{
    protected string $hubungan = KeluargaPegawai::HUBUNGAN_PASANGAN;
    protected ?string $status = KeluargaPegawai::STATUS_DIAJUKAN;
    protected ?int $unitKerjaId = null;
    protected ?int $klasifikasiSdmId = null;

    public function withFilters(
        string $hubungan,
        ?string $status,
        ?int $unitKerjaId,
        ?int $klasifikasiSdmId,
    ): static {
        $this->hubungan = $hubungan;
        $this->status = $status;
        $this->unitKerjaId = $unitKerjaId;
        $this->klasifikasiSdmId = $klasifikasiSdmId;

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function (KeluargaPegawai $item) {
                return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="form-check-input check-validasi-keluarga">';
            })
            ->addColumn('nip', fn (KeluargaPegawai $item) => e($item->pegawai?->nip ?? '-'))
            ->addColumn('nama_pegawai', fn (KeluargaPegawai $item) => e($item->pegawai?->nama ?? '-'))
            ->editColumn('nama', fn (KeluargaPegawai $item) => '<span class="fw-semibold">' . e($item->nama) . '</span>')
            ->editColumn('tanggal_lahir', fn (KeluargaPegawai $item) => $item->tanggal_lahir ? $item->tanggal_lahir->format('d M Y') : '-')
            ->editColumn('kartu_nikah', fn (KeluargaPegawai $item) => e($item->kartu_nikah ?: ($item->status_hubungan ?: '-')))
            ->editColumn('tanggal_diajukan', fn (KeluargaPegawai $item) => $item->tanggal_diajukan ? $item->tanggal_diajukan->format('d M Y') : '-')
            ->addColumn('action', function (KeluargaPegawai $item) {
                $showUrl = route('validasi-keluarga.show', $item);

                return '
                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <a href="' . e($showUrl) . '"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 30px; height: 30px;"
                            title="Detail Validasi">
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
            ->rawColumns(['checkbox', 'nama', 'action'])
            ->setRowId('id');
    }

    public function query(KeluargaPegawai $model): QueryBuilder
    {
        $unitIds = null;
        if ($this->unitKerjaId) {
            $unit = UnitKerja::with('children.children')->find($this->unitKerjaId);
            $unitIds = $unit ? $unit->descendantIds() : [$this->unitKerjaId];
        }

        return $model->newQuery()
            ->with(['pegawai.unitKerja', 'pegawai.klasifikasiSdm'])
            ->where('hubungan', $this->hubungan)
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
        $ajaxUrl = route('validasi-keluarga.index', [], false);

        return $this->builder()
            ->setTableId('validasi-keluarga-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => $ajaxUrl,
                'data' => 'function(d) {
                    d.unit_kerja_id = $("#filterUnitKerjaKeluarga").val();
                    d.klasifikasi_sdm_id = $("#filterJabatanAkademikKeluarga").val();
                    d.hubungan = $("#filterHubunganKeluarga").val();
                    d.status = $("#filterStatusKeluarga").val();
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
        $namaLabel = match ($this->hubungan) {
            KeluargaPegawai::HUBUNGAN_ANAK => 'Nama Anak',
            KeluargaPegawai::HUBUNGAN_ORANG_TUA => 'Nama Orang Tua',
            default => 'Nama Pasangan',
        };

        $dokumenLabel = match ($this->hubungan) {
            KeluargaPegawai::HUBUNGAN_ANAK => 'No. Akta / Status',
            KeluargaPegawai::HUBUNGAN_ORANG_TUA => 'Hubungan',
            default => 'Kartu Nikah',
        };

        return [
            Column::computed('checkbox')
                ->title('<input type="checkbox" id="checkAllValidasiKeluarga" class="form-check-input">')
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
            Column::make('nama')
                ->title($namaLabel),
            Column::make('tanggal_lahir')
                ->title('Tgl Lahir')
                ->width(105)
                ->addClass('text-center text-nowrap'),
            Column::make('kartu_nikah')
                ->title($dokumenLabel)
                ->width(150),
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
        return 'ValidasiKeluarga_' . date('YmdHis');
    }
}
