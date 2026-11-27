@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Validasi <small class="text-muted fw-normal fs-6">Riwayat Pangkat</small></h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kepegawaian</span>
            <span class="breadcrumb-item active">Pangkat</span>
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

      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- 3-Filter Card -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <form id="filterValidasiPangkatForm" action="{{ route('validasi-pangkat.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6">
              <div class="row g-2 align-items-center">
                <label for="filterUnitKerjaPangkat" class="col-sm-4 col-form-label col-form-label-sm fw-semibold text-primary">Unit Kerja</label>
                <div class="col-sm-8">
                  <select id="filterUnitKerjaPangkat" name="unit_kerja_id" class="form-select form-select-sm select2-pangkat">
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
                <label for="filterJabatanAkademikPangkat" class="col-sm-4 col-form-label col-form-label-sm fw-semibold text-primary">Jabatan Akademik</label>
                <div class="col-sm-8">
                  <select id="filterJabatanAkademikPangkat" name="klasifikasi_sdm_id" class="form-select form-select-sm select2-pangkat">
                    <option value="">-- Semua Jabatan Akademik --</option>
                    @foreach ($klasifikasiSdms as $sdm)
                      <option value="{{ $sdm->id }}" @selected($selectedKlasifikasiId === $sdm->id)>{{ $sdm->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row g-2 align-items-center">
                <label for="filterStatusPangkat" class="col-sm-4 col-form-label col-form-label-sm fw-semibold text-primary">Status</label>
                <div class="col-sm-8">
                  <select id="filterStatusPangkat" name="status" class="form-select form-select-sm">
                    <option value="" @selected($status === null)>-- Semua Status --</option>
                    @foreach (\App\Models\RiwayatPangkat::STATUS_OPTIONS as $key => $label)
                      <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- DataTable & Bulk Actions -->
      <form id="bulkValidasiPangkatForm" action="{{ route('validasi-pangkat.bulk') }}" method="POST">
        @csrf
        <input type="hidden" name="action" id="bulkPangkatActionInput" value="">

        <div class="card">
          <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="card-title mb-0">Daftar Validasi Riwayat Pangkat</h5>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-sm btn-success btn-bulk-pangkat" data-action="disetujui">
                <i class="bi bi-check-lg me-1"></i> Setujui
              </button>
              <button type="button" class="btn btn-sm btn-danger btn-bulk-pangkat" data-action="ditolak">
                <i class="bi bi-x-lg me-1"></i> Tolak
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white btn-bulk-pangkat" data-action="ditangguhkan">
                <i class="bi bi-pause-fill me-1"></i> Tangguhkan
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive validasi-pangkat-table-wrap">
              {!! $dataTable->table() !!}
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
    .validasi-pangkat-table-wrap,
    .validasi-pangkat-table-wrap .dt-container,
    #validasi-pangkat-table,
    #validasi-pangkat-table th,
    #validasi-pangkat-table td {
      font-size: 11px !important;
    }

    #validasi-pangkat-table th,
    #validasi-pangkat-table td {
      padding: 0.45rem 0.6rem;
    }

    .select2-container--default .select2-selection--single {
      height: 32px;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      display: flex;
      align-items: center;
      font-size: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 30px;
    }

    .select2-dropdown {
      font-size: 12px;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  @if (app()->environment('production'))
    {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
  @else
    {!! $dataTable->scripts() !!}
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const filterForm = document.getElementById('filterValidasiPangkatForm');

      if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2-pangkat').select2({ width: '100%' });
      }

      function reloadTable() {
        if (window.LaravelDataTables && window.LaravelDataTables['validasi-pangkat-table']) {
          window.LaravelDataTables['validasi-pangkat-table'].ajax.reload();
        } else {
          filterForm?.submit();
        }
      }

      if (typeof $ !== 'undefined') {
        $('#filterUnitKerjaPangkat, #filterJabatanAkademikPangkat, #filterStatusPangkat').on('change', reloadTable);

        $(document).on('change', '#checkAllValidasiPangkat', function () {
          $('.check-validasi-pangkat').prop('checked', this.checked);
        });
      }

      const bulkForm = document.getElementById('bulkValidasiPangkatForm');
      const actionInput = document.getElementById('bulkPangkatActionInput');

      document.querySelectorAll('.btn-bulk-pangkat').forEach(function (btn) {
        btn.addEventListener('click', function () {
          const checkedCount = document.querySelectorAll('.check-validasi-pangkat:checked').length;
          if (checkedCount === 0) {
            alert('Pilih minimal satu data riwayat pangkat terlebih dahulu.');
            return;
          }

          const action = btn.dataset.action;
          const labelMap = {
            disetujui: 'Menyetujui',
            ditolak: 'Menolak',
            ditangguhkan: 'Menangguhkan',
          };

          if (confirm(`Yakin ingin ${labelMap[action] || 'memproses'} ${checkedCount} data riwayat pangkat terpilih?`)) {
            actionInput.value = action;
            bulkForm.submit();
          }
        });
      });
    });
  </script>
@endpush
