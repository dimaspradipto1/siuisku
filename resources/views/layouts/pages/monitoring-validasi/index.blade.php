@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Monitoring <small class="text-muted fw-normal fs-6">Data Riwayat Pegawai</small></h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item active">Monitoring</span>
          </nav>
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

      <!-- Filter Unit Kerja & Pegawai -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <form id="filterMonitoringForm" action="{{ route('monitoring-validasi.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6">
              <div class="row g-2 align-items-center">
                <label for="filterUnitKerja" class="col-sm-3 col-form-label col-form-label-sm fw-semibold text-primary">Unit Kerja</label>
                <div class="col-sm-9">
                  <select id="filterUnitKerja" name="unit_kerja_id" class="form-select form-select-sm select2-monitoring">
                    <option value="">-- Semua Unit Kerja --</option>
                    @foreach ($unitOptions as $unit)
                      <option value="{{ $unit['id'] }}" @selected($selectedUnitId === $unit['id'])>{{ $unit['label'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row g-2 align-items-center">
                <label for="filterPegawai" class="col-sm-3 col-form-label col-form-label-sm fw-semibold text-primary">Pegawai</label>
                <div class="col-sm-9">
                  <select id="filterPegawai" name="pegawai_id" class="form-select form-select-sm select2-monitoring">
                    <option value="">-- Semua Pegawai --</option>
                    @foreach ($pegawais as $pegawai)
                      <option value="{{ $pegawai->id }}" @selected($selectedPegawaiId === $pegawai->id)>{{ $pegawai->label }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel Monitoring Riwayat Pegawai -->
      <form id="bulkMonitoringForm" action="{{ route('monitoring-validasi.bulk') }}" method="POST">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput" value="">
        <input type="hidden" name="unit_kerja_id" value="{{ $selectedUnitId }}">
        <input type="hidden" name="pegawai_id" value="{{ $selectedPegawaiId }}">

        <div class="card">
          <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2" style="font-size: 12px;">
              <i class="bi bi-info-circle-fill text-secondary"></i>
              <span>Riwayat yang bertanda <strong class="text-danger">merah</strong> terdapat data yang menunggu validasi</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-sm btn-success btn-bulk-monitoring" data-action="disetujui">
                <i class="bi bi-check-lg me-1"></i> Setujui
              </button>
              <button type="button" class="btn btn-sm btn-danger btn-bulk-monitoring" data-action="ditolak">
                <i class="bi bi-x-lg me-1"></i> Tolak
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white btn-bulk-monitoring" data-action="ditangguhkan">
                <i class="bi bi-pause-fill me-1"></i> Tangguhkan
              </button>
            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive monitoring-table-wrap">
              <table id="monitoring-validasi-table" class="table table-hover table-bordered align-middle w-100 mb-0">
                <thead class="table-light">
                  <tr>
                    <th rowspan="2" class="text-center align-middle" style="width: 42px;">
                      <input type="checkbox" id="checkAllJenisRiwayat" class="form-check-input">
                    </th>
                    <th rowspan="2" class="align-middle">Nama Riwayat</th>
                    <th colspan="5" class="text-center">Status Riwayat</th>
                    <th rowspan="2" class="text-center align-middle" style="width: 65px;">Aksi</th>
                  </tr>
                  <tr>
                    <th class="text-center" style="width: 95px;">Draft</th>
                    <th class="text-center" style="width: 95px;">Diajukan</th>
                    <th class="text-center" style="width: 95px;">Disetujui</th>
                    <th class="text-center" style="width: 95px;">Ditolak</th>
                    <th class="text-center" style="width: 105px;">Ditangguhkan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($grouped as $kategori => $rows)
                    <tr class="table-secondary">
                      <td colspan="8" class="fw-bold py-2">{{ $kategori }}</td>
                    </tr>
                    @foreach ($rows as $row)
                      @php
                        $jenis = $row['jenis'];
                        $stats = $row['stats'];
                        $hasPending = $stats['diajukan'] > 0;
                        $detailUrl = route('monitoring-validasi.show', array_filter([
                            'jenisRiwayat' => $jenis->id,
                            'unit_kerja_id' => $selectedUnitId,
                            'pegawai_id' => $selectedPegawaiId,
                        ]));
                      @endphp
                      <tr class="{{ $hasPending ? 'monitoring-row-pending' : '' }}">
                        <td class="text-center">
                          <input type="checkbox" name="jenis_riwayat_ids[]" value="{{ $jenis->id }}" class="form-check-input check-jenis-riwayat">
                        </td>
                        <td class="{{ $hasPending ? 'text-danger fw-semibold' : '' }}">
                          {{ $jenis->nama }}
                        </td>
                        <td class="text-end">{{ $stats['draft'] }}</td>
                        <td class="text-end {{ $hasPending ? 'text-danger fw-bold' : '' }}">{{ $stats['diajukan'] }}</td>
                        <td class="text-end">{{ $stats['disetujui'] }}</td>
                        <td class="text-end">{{ $stats['ditolak'] }}</td>
                        <td class="text-end">{{ $stats['ditangguhkan'] }}</td>
                        <td class="text-center">
                          <a href="{{ $detailUrl }}"
                            class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 30px; height: 30px;"
                            title="Lihat Detail {{ $jenis->nama }}">
                            <i class="fa-solid fa-eye"></i>
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                  <tr>
                    <td colspan="2">Total</td>
                    <td class="text-end">{{ $totals['draft'] }}</td>
                    <td class="text-end {{ $totals['diajukan'] > 0 ? 'text-danger' : '' }}">{{ $totals['diajukan'] }}</td>
                    <td class="text-end">{{ $totals['disetujui'] }}</td>
                    <td class="text-end">{{ $totals['ditolak'] }}</td>
                    <td class="text-end">{{ $totals['ditangguhkan'] }}</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('style')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .monitoring-table-wrap,
    #monitoring-validasi-table,
    #monitoring-validasi-table th,
    #monitoring-validasi-table td {
      font-size: 11px !important;
    }

    #monitoring-validasi-table th,
    #monitoring-validasi-table td {
      padding: 0.45rem 0.65rem;
    }

    #monitoring-validasi-table tr.monitoring-row-pending {
      background-color: #fff5f5;
    }

    .select2-container--default .select2-selection--single {
      height: 34px;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      display: flex;
      align-items: center;
      font-size: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 32px;
    }

    .select2-dropdown {
      font-size: 12px;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const filterForm = document.getElementById('filterMonitoringForm');
      const unitSelect = document.getElementById('filterUnitKerja');
      const pegawaiSelect = document.getElementById('filterPegawai');

      if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2-monitoring').select2({
          width: '100%',
        });

        $('#filterUnitKerja').on('change', function () {
          if (pegawaiSelect) pegawaiSelect.value = '';
          filterForm?.submit();
        });

        $('#filterPegawai').on('change', function () {
          filterForm?.submit();
        });
      } else {
        unitSelect?.addEventListener('change', function () {
          if (pegawaiSelect) pegawaiSelect.value = '';
          filterForm?.submit();
        });
        pegawaiSelect?.addEventListener('change', function () {
          filterForm?.submit();
        });
      }

      const checkAll = document.getElementById('checkAllJenisRiwayat');
      const checkboxes = document.querySelectorAll('.check-jenis-riwayat');

      checkAll?.addEventListener('change', function () {
        checkboxes.forEach(function (cb) {
          cb.checked = checkAll.checked;
        });
      });

      const bulkForm = document.getElementById('bulkMonitoringForm');
      const bulkActionInput = document.getElementById('bulkActionInput');

      document.querySelectorAll('.btn-bulk-monitoring').forEach(function (btn) {
        btn.addEventListener('click', function () {
          const checkedCount = document.querySelectorAll('.check-jenis-riwayat:checked').length;
          if (checkedCount === 0) {
            alert('Pilih minimal satu jenis riwayat terlebih dahulu.');
            return;
          }

          const action = btn.dataset.action;
          const labelMap = {
            disetujui: 'Menyetujui',
            ditolak: 'Menolak',
            ditangguhkan: 'Menangguhkan',
          };

          if (confirm(`Yakin ingin ${labelMap[action] || 'memproses'} seluruh data berstatus Diajukan pada ${checkedCount} riwayat terpilih?`)) {
            bulkActionInput.value = action;
            bulkForm.submit();
          }
        });
      });
    });
  </script>
@endpush
