@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jenis Cuti</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kehadiran</span>
            <span class="breadcrumb-item active">Jenis Cuti</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineCuti" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Cuti
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
      <form id="inlineCreateCutiForm" action="{{ route('jenis-cuti.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditCutiForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Cuti</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jenis-cuti-table-wrap">
            {!! $dataTable->table() !!}
          </div>

          <div class="alert alert-warning border-start border-warning border-4 bg-warning-subtle text-dark mt-3 mb-0 py-2 px-3" style="font-size: 12px;">
            <div class="fw-semibold mb-1">Format Pengkodean</div>
            <div class="d-flex flex-wrap gap-4">
              <span>Kode : <code>@{{kode}}</code></span>
              <span>Urutan : <code>@{{urutan}}</code></span>
              <span>Tahun : <code>@{{tahun}}</code></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jenis-cuti-table-wrap,
    .jenis-cuti-table-wrap .dt-container,
    #jenis-cuti-table,
    #jenis-cuti-table th,
    #jenis-cuti-table td {
      font-size: 11px !important;
    }

    #jenis-cuti-table th,
    #jenis-cuti-table td {
      padding: 0.45rem 0.6rem;
    }

    #jenis-cuti-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #jenis-cuti-table tr.inline-form-row input.form-control {
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
      const btnTambah = document.getElementById('btnTambahInlineCuti');
      const editForm = document.getElementById('inlineEditCutiForm');
      const defaultFormat = @json('{{kode}}/{{urutan}}/{{tahun}}');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldStandar = @json(old('standar_cuti', ''));
      const oldFormat = @json(old('format_nomor_surat', ''));
      const oldKet = @json(old('keterangan', ''));

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
        const existing = document.getElementById('inlineCreateCutiRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', standarVal = '', formatVal = defaultFormat, ketVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateCutiRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#jenis-cuti-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateCutiRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateCutiForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: BSR" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateCutiForm" class="form-control form-control-sm" placeholder="Contoh: Cuti Besar" maxlength="150" required>
          </td>
          <td>
            <input type="number" min="0" max="365" name="standar_cuti" form="inlineCreateCutiForm" class="form-control form-control-sm text-center" placeholder="0" required>
          </td>
          <td>
            <input type="text" name="format_nomor_surat" form="inlineCreateCutiForm" class="form-control form-control-sm" maxlength="150">
          </td>
          <td>
            <input type="text" name="keterangan" form="inlineCreateCutiForm" class="form-control form-control-sm" placeholder="Opsional" maxlength="500">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateCutiForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateCuti" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const standarInput = tr.querySelector('input[name="standar_cuti"]');
        const formatInput = tr.querySelector('input[name="format_nomor_surat"]');
        const ketInput = tr.querySelector('input[name="keterangan"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (standarInput) standarInput.value = standarVal;
        if (formatInput) formatInput.value = formatVal;
        if (ketInput) ketInput.value = ketVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateCuti')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '', defaultFormat, '');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-cuti');
        if (!editBtn) return;

        removeCreateRow();
        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');

        const rowNo = tr.cells[0]?.textContent?.trim() || '';
        const kode = editBtn.dataset.kode || '';
        const nama = editBtn.dataset.nama || '';
        const standar = editBtn.dataset.standarCuti || '0';
        const format = editBtn.dataset.formatNomorSurat || '';
        const ket = editBtn.dataset.keterangan || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditCutiForm" class="form-control form-control-sm text-uppercase" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditCutiForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <input type="number" min="0" max="365" name="standar_cuti" form="inlineEditCutiForm" class="form-control form-control-sm text-center" required>
          </td>
          <td>
            <input type="text" name="format_nomor_surat" form="inlineEditCutiForm" class="form-control form-control-sm" maxlength="150">
          </td>
          <td>
            <input type="text" name="keterangan" form="inlineEditCutiForm" class="form-control form-control-sm" maxlength="500">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditCutiForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const standarInput = tr.querySelector('input[name="standar_cuti"]');
        const formatInput = tr.querySelector('input[name="format_nomor_surat"]');
        const ketInput = tr.querySelector('input[name="keterangan"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (standarInput) standarInput.value = standar;
        if (formatInput) formatInput.value = format;
        if (ketInput) ketInput.value = ket;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#jenis-cuti-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldStandar : '',
            hasErrors ? oldFormat : defaultFormat,
            hasErrors ? oldKet : ''
          );
        });
      }
    });
  </script>
@endpush
