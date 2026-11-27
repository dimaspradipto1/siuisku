@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Validasi <small class="text-muted fw-normal fs-6">Riwayat Jabatan Struktural</small></h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kepegawaian</span>
            <span class="breadcrumb-item active">Jabatan Struktural</span>
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
          <form id="filterValidasiStrukturalForm" action="{{ route('validasi-jabatan-struktural.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6">
              <div class="row g-2 align-items-center">
                <label for="filterUnitKerjaStruktural" class="col-sm-4 col-form-label col-form-label-sm fw-semibold text-primary">Unit Kerja</label>
                <div class="col-sm-8">
                  <select id="filterUnitKerjaStruktural" name="unit_kerja_id" class="form-select form-select-sm select2-struktural">
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
                <label for="filterJabatanStruktural" class="col-sm-4 col-form-label col-form-label-sm fw-semibold text-primary">Jabatan Struktural</label>
                <div class="col-sm-8">
                  <select id="filterJabatanStruktural" name="jabatan_struktural_id" class="form-select form-select-sm select2-struktural">
                    <option value="">-- Semua Jabatan --</option>
                    @foreach ($jabatanStrukturals as $jabatan)
                      <option value="{{ $jabatan->id }}" @selected($selectedJabatanId === $jabatan->id)>{{ $jabatan->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row g-2 align-items-center">
                <label for="filterStatusStruktural" class="col-sm-4 col-form-label col-form-label-sm fw-semibold text-primary">Status</label>
                <div class="col-sm-8">
                  <select id="filterStatusStruktural" name="status" class="form-select form-select-sm">
                    <option value="" @selected($status === null)>-- Semua Status --</option>
                    @foreach (\App\Models\RiwayatJabatanStruktural::STATUS_OPTIONS as $key => $label)
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
      <form id="bulkValidasiStrukturalForm" action="{{ route('validasi-jabatan-struktural.bulk') }}" method="POST">
        @csrf
        <input type="hidden" name="action" id="bulkStrukturalActionInput" value="">

        <div class="card">
          <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="card-title mb-0">Daftar Validasi Riwayat Jabatan Struktural</h5>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-sm btn-success btn-bulk-struktural" data-action="disetujui">
                <i class="bi bi-check-lg me-1"></i> Setujui
              </button>
              <button type="button" class="btn btn-sm btn-danger btn-bulk-struktural" data-action="ditolak">
                <i class="bi bi-x-lg me-1"></i> Tolak
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive validasi-struktural-table-wrap">
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
    .validasi-struktural-table-wrap,
    .validasi-struktural-table-wrap .dt-container,
    #validasi-struktural-table,
    #validasi-struktural-table th,
    #validasi-struktural-table td {
      font-size: 11px !important;
    }

    #validasi-struktural-table th,
    #validasi-struktural-table td {
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
      const filterForm = document.getElementById('filterValidasiStrukturalForm');

      if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2-struktural').select2({ width: '100%' });
      }

      function reloadTable() {
        if (window.LaravelDataTables && window.LaravelDataTables['validasi-struktural-table']) {
          window.LaravelDataTables['validasi-struktural-table'].ajax.reload();
        } else {
          filterForm?.submit();
        }
      }

      if (typeof $ !== 'undefined') {
        $('#filterUnitKerjaStruktural, #filterJabatanStruktural, #filterStatusStruktural').on('change', reloadTable);

        $(document).on('change', '#checkAllValidasiStruktural', function () {
          $('.check-validasi-struktural').prop('checked', this.checked);
        });
      }

      const bulkForm = document.getElementById('bulkValidasiStrukturalForm');
      const actionInput = document.getElementById('bulkStrukturalActionInput');

      document.querySelectorAll('.btn-bulk-struktural').forEach(function (btn) {
        btn.addEventListener('click', function () {
          const checkedCount = document.querySelectorAll('.check-validasi-struktural:checked').length;
          if (checkedCount === 0) {
            alert('Pilih minimal satu data riwayat jabatan struktural terlebih dahulu.');
            return;
          }

          const action = btn.dataset.action;
          const label = action === 'disetujui' ? 'Menyetujui' : 'Menolak';

          if (confirm(`Yakin ingin ${label} ${checkedCount} data riwayat jabatan struktural terpilih?`)) {
            actionInput.value = action;
            bulkForm.submit();
          }
        });
      });
    });
  </script>
@endpush
