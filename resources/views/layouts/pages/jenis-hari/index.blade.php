@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Setting Hari</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kehadiran</span>
            <span class="breadcrumb-item active">Jenis Hari</span>
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
          <strong>Terjadi kesalahan input:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Hidden form for inline Edit -->
      <form id="inlineEditHariForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Setting Hari</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Hari</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jenis-hari-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jenis-hari-table-wrap,
    .jenis-hari-table-wrap .dt-container,
    #jenis-hari-table,
    #jenis-hari-table th,
    #jenis-hari-table td {
      font-size: 11px !important;
    }

    #jenis-hari-table th,
    #jenis-hari-table td {
      padding: 0.45rem 0.6rem;
    }

    #jenis-hari-table tr.inline-form-row {
      background-color: #f8fafc;
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
      const editForm = document.getElementById('inlineEditHariForm');
      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function cancelActiveEdit() {
        if (activeEditRow && activeEditOriginalHtml) {
          activeEditRow.innerHTML = activeEditOriginalHtml;
          activeEditRow.classList.remove('inline-form-row');
          activeEditRow = null;
          activeEditOriginalHtml = '';
        }
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-hari');
        if (!editBtn) return;

        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');

        const rowNo = tr.cells[0]?.textContent?.trim() || '';
        const kode = editBtn.dataset.kode || '';
        const nama = editBtn.dataset.nama || '';
        const jenisHari = editBtn.dataset.jenisHari || 'Efektif';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td class="text-center fw-semibold">${kode}</td>
          <td>${nama}</td>
          <td>
            <div class="d-flex flex-column gap-1">
              <label class="form-check mb-0 d-flex align-items-center gap-2" style="cursor: pointer;">
                <input type="radio" name="jenis_hari" value="Efektif" form="inlineEditHariForm" class="form-check-input mt-0" ${jenisHari === 'Efektif' ? 'checked' : ''}>
                <span>Efektif</span>
              </label>
              <label class="form-check mb-0 d-flex align-items-center gap-2" style="cursor: pointer;">
                <input type="radio" name="jenis_hari" value="Non Efektif" form="inlineEditHariForm" class="form-check-input mt-0" ${jenisHari === 'Non Efektif' ? 'checked' : ''}>
                <span>Non Efektif</span>
              </label>
            </div>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditHariForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });
    });
  </script>
@endpush
