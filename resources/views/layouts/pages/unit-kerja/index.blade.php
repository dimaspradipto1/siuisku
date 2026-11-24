@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Unit Kerja</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kepegawaian</span>
            <span class="breadcrumb-item active">Unit Kerja</span>
          </nav>
        </div>
        <div class="page-header-actions">
          <a href="{{ route('unit-kerja.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Unit Kerja
          </a>
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Unit Kerja Universitas Ibnu Sina</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Unit</span>
        </div>
        <div class="card-body">
          <div class="table-responsive unit-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .unit-table-wrap,
    .unit-table-wrap .dt-container,
    #unit-kerja-table,
    #unit-kerja-table th,
    #unit-kerja-table td,
    #unit-kerja-table .badge {
      font-size: 11px !important;
    }

    #unit-kerja-table th,
    #unit-kerja-table td {
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
@endpush
