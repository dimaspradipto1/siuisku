@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Monitoring &mdash; {{ $jenisRiwayat->nama }}</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <a href="{{ route('monitoring-validasi.index', array_filter(['unit_kerja_id' => $selectedUnitId, 'pegawai_id' => $selectedPegawaiId])) }}" class="breadcrumb-item">Monitoring</a>
            <span class="breadcrumb-item active">{{ $jenisRiwayat->nama }}</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('monitoring-validasi.index', array_filter(['unit_kerja_id' => $selectedUnitId, 'pegawai_id' => $selectedPegawaiId])) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row g-3 align-items-center" style="font-size: 12px;">
            <div class="col-md-3">
              <div class="text-muted">Kategori</div>
              <div class="fw-semibold">{{ $jenisRiwayat->kategori }}</div>
            </div>
            <div class="col-md-3">
              <div class="text-muted">Unit Kerja</div>
              <div class="fw-semibold">{{ $selectedUnit ? "{$selectedUnit->kode} - {$selectedUnit->nama}" : 'Semua Unit Kerja' }}</div>
            </div>
            <div class="col-md-3">
              <div class="text-muted">Pegawai</div>
              <div class="fw-semibold">{{ $selectedPegawai ? $selectedPegawai->label : 'Semua Pegawai' }}</div>
            </div>
            <div class="col-md-3">
              <form method="GET" action="{{ route('monitoring-validasi.show', $jenisRiwayat) }}" class="d-flex align-items-center gap-2">
                @if ($selectedUnitId)
                  <input type="hidden" name="unit_kerja_id" value="{{ $selectedUnitId }}">
                @endif
                @if ($selectedPegawaiId)
                  <input type="hidden" name="pegawai_id" value="{{ $selectedPegawaiId }}">
                @endif
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                  <option value="">-- Semua Status --</option>
                  @foreach (\App\Models\RiwayatPegawai::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected($selectedStatus === $key)>{{ $label }}</option>
                  @endforeach
                </select>
              </form>
            </div>
          </div>
        </div>
      </div>

      <form id="bulkDetailForm" action="{{ route('monitoring-validasi.bulk') }}" method="POST">
        @csrf
        <input type="hidden" name="action" id="bulkDetailActionInput" value="">

        <div class="card">
          <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="card-title mb-0">Daftar Pengajuan {{ $jenisRiwayat->nama }} <span class="badge bg-primary-subtle text-primary ms-1">{{ $riwayats->count() }} Data</span></h5>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-sm btn-success btn-bulk-detail" data-action="disetujui">
                <i class="bi bi-check-lg me-1"></i> Setujui
              </button>
              <button type="button" class="btn btn-sm btn-danger btn-bulk-detail" data-action="ditolak">
                <i class="bi bi-x-lg me-1"></i> Tolak
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white btn-bulk-detail" data-action="ditangguhkan">
                <i class="bi bi-pause-fill me-1"></i> Tangguhkan
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive monitoring-detail-wrap">
              <table class="table table-hover align-middle w-100 mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="text-center" style="width: 42px;">
                      <input type="checkbox" id="checkAllDetailRiwayat" class="form-check-input">
                    </th>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Pegawai</th>
                    <th>Unit Kerja</th>
                    <th>Judul / Uraian Riwayat</th>
                    <th class="text-center" style="width: 110px;">Tgl Pengajuan</th>
                    <th class="text-center" style="width: 110px;">Status</th>
                    <th>Validator</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($riwayats as $index => $riwayat)
                    <tr>
                      <td class="text-center">
                        <input type="checkbox" name="riwayat_ids[]" value="{{ $riwayat->id }}" class="form-check-input check-detail-riwayat">
                      </td>
                      <td class="text-center">{{ $index + 1 }}</td>
                      <td>
                        <div class="fw-semibold">{{ $riwayat->pegawai?->nama ?? '-' }}</div>
                        <small class="text-muted">{{ $riwayat->pegawai?->nip ?? '-' }}</small>
                      </td>
                      <td>{{ $riwayat->pegawai?->unitKerja?->nama ?? '-' }}</td>
                      <td>
                        <div>{{ $riwayat->judul }}</div>
                        @if ($riwayat->catatan_validasi)
                          <small class="text-muted">Catatan: {{ $riwayat->catatan_validasi }}</small>
                        @endif
                      </td>
                      <td class="text-center text-nowrap">
                        {{ $riwayat->tanggal_pengajuan ? $riwayat->tanggal_pengajuan->format('d M Y') : '-' }}
                      </td>
                      <td class="text-center">
                        <span class="badge {{ $riwayat->statusBadgeClass() }}">{{ $riwayat->statusLabel() }}</span>
                      </td>
                      <td>
                        @if ($riwayat->validator)
                          <div>{{ $riwayat->validator->name }}</div>
                          <small class="text-muted">{{ $riwayat->validated_at?->format('d M Y H:i') }}</small>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center text-muted py-4">Belum ada data riwayat untuk filter ini.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .monitoring-detail-wrap table,
    .monitoring-detail-wrap th,
    .monitoring-detail-wrap td {
      font-size: 11px !important;
      padding: 0.45rem 0.65rem;
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const checkAll = document.getElementById('checkAllDetailRiwayat');
      const checkboxes = document.querySelectorAll('.check-detail-riwayat');

      checkAll?.addEventListener('change', function () {
        checkboxes.forEach(function (cb) {
          cb.checked = checkAll.checked;
        });
      });

      const bulkForm = document.getElementById('bulkDetailForm');
      const actionInput = document.getElementById('bulkDetailActionInput');

      document.querySelectorAll('.btn-bulk-detail').forEach(function (btn) {
        btn.addEventListener('click', function () {
          const checkedCount = document.querySelectorAll('.check-detail-riwayat:checked').length;
          if (checkedCount === 0) {
            alert('Pilih minimal satu data pengajuan terlebih dahulu.');
            return;
          }

          const action = btn.dataset.action;
          if (confirm(`Yakin ingin memproses ${checkedCount} data pengajuan terpilih?`)) {
            actionInput.value = action;
            bulkForm.submit();
          }
        });
      });
    });
  </script>
@endpush
