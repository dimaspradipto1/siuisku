@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jabatan Fungsional</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kepegawaian</span>
            <span class="breadcrumb-item active">Jabatan Fungsional</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('jabatan-fungsional.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jabatan Fungsional
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

      <!-- Filter Card -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row align-items-center g-2">
            <label for="filterKlasifikasiSdm" class="col-md-2 col-form-label fw-semibold text-primary mb-0">
              Jabatan Akademik
            </label>
            <div class="col-md-4">
              <select id="filterKlasifikasiSdm" class="form-select form-select-sm select2" data-placeholder="-- Semua Jabatan Akademik --">
                <option value="">-- Semua Jabatan Akademik --</option>
                @foreach ($klasifikasiSdms as $item)
                  <option value="{{ $item->id }}" {{ (string) $defaultKlasifikasiId === (string) $item->id ? 'selected' : '' }}>
                    {{ $item->nama }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jabatan Fungsional</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jabatan-fungsional-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jabatan-fungsional-table-wrap,
    .jabatan-fungsional-table-wrap .dt-container,
    #jabatan-fungsional-table,
    #jabatan-fungsional-table th,
    #jabatan-fungsional-table td {
      font-size: 11px !important;
    }

    #jabatan-fungsional-table th,
    #jabatan-fungsional-table td {
      padding: 0.45rem 0.6rem;
    }
  </style>
@endpush

@push('scripts')
  @if (app()->environment('production'))
    {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
  @else
    {!! $dataTable->scripts() !!}
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const filterSelect = document.getElementById('filterKlasifikasiSdm');
      if (typeof $ !== 'undefined' && filterSelect) {
        $(filterSelect).on('change', function () {
          if (window.LaravelDataTables && window.LaravelDataTables['jabatan-fungsional-table']) {
            window.LaravelDataTables['jabatan-fungsional-table'].ajax.reload();
          }
        });
      }
    });
  </script>
@endpush
