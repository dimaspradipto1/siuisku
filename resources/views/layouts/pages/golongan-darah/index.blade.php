@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Golongan Darah</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Pelengkap</span>
            <span class="breadcrumb-item active">Golongan Darah</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineGolonganDarah" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Golongan Darah
          </button>
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

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateGolonganDarahForm" action="{{ route('golongan-darah.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditGolonganDarahForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Golongan Darah</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive golongan-darah-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .golongan-darah-table-wrap,
    .golongan-darah-table-wrap .dt-container,
    #golongan-darah-table,
    #golongan-darah-table th,
    #golongan-darah-table td {
      font-size: 11px !important;
    }

    #golongan-darah-table th,
    #golongan-darah-table td {
      padding: 0.45rem 0.6rem;
    }

    #golongan-darah-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #golongan-darah-table tr.inline-form-row input.form-control {
      font-size: 12px;
      height: 34px;
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
      const btnTambah = document.getElementById('btnTambahInlineGolonganDarah');
      const editForm = document.getElementById('inlineEditGolonganDarahForm');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldNama = @json(old('nama', ''));

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

      function removeCreateRow() {
        const existing = document.getElementById('inlineCreateGolonganDarahRow');
        if (existing) existing.remove();
      }

      function openCreateRow(namaVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateGolonganDarahRow');
        if (existing) {
          existing.querySelector('input[name="nama"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#golongan-darah-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateGolonganDarahRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="nama" form="inlineCreateGolonganDarahForm" class="form-control form-control-sm text-center text-uppercase" placeholder="Contoh: A+" maxlength="10" required>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateGolonganDarahForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateGolonganDarah" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const namaInput = tr.querySelector('input[name="nama"]');
        if (namaInput) namaInput.value = namaVal;
        namaInput?.focus();

        tr.querySelector('#btnCancelInlineCreateGolonganDarah')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-golongan-darah');
        if (!editBtn) return;

        removeCreateRow();
        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');

        const rowNo = tr.cells[0]?.textContent?.trim() || '';
        const nama = editBtn.dataset.nama || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="nama" form="inlineEditGolonganDarahForm" class="form-control form-control-sm text-center text-uppercase" maxlength="10" required>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditGolonganDarahForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const namaInput = tr.querySelector('input[name="nama"]');
        if (namaInput) namaInput.value = nama;
        namaInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#golongan-darah-table').one('draw.dt', function () {
          openCreateRow(hasErrors ? oldNama : '');
        });
      }
    });
  </script>
@endpush
