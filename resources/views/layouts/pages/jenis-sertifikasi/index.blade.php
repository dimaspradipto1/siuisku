@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jenis Sertifikasi</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Aktifitas</span>
            <span class="breadcrumb-item active">Jenis Sertifikasi</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineSertifikasi" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Sertifikasi
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
      <form id="inlineCreateSertifikasiForm" action="{{ route('jenis-sertifikasi.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditSertifikasiForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jenis Sertifikasi</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jenis-sertifikasi-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jenis-sertifikasi-table-wrap,
    .jenis-sertifikasi-table-wrap .dt-container,
    #jenis-sertifikasi-table,
    #jenis-sertifikasi-table th,
    #jenis-sertifikasi-table td {
      font-size: 11px !important;
    }

    #jenis-sertifikasi-table th,
    #jenis-sertifikasi-table td {
      padding: 0.45rem 0.6rem;
    }

    #jenis-sertifikasi-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #jenis-sertifikasi-table tr.inline-form-row input.form-control,
    #jenis-sertifikasi-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineSertifikasi');
      const editForm = document.getElementById('inlineEditSertifikasiForm');
      const kategoriOptions = @json($kategoriOptions);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldKategori = @json(old('kategori', 'Sertifikasi Kompetensi'));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function buildKategoriOptionsHtml(selectedVal) {
        return kategoriOptions.map(function (opt) {
          const selected = opt === selectedVal ? ' selected' : '';
          return `<option value="${opt}"${selected}>${opt}</option>`;
        }).join('');
      }

      function cancelActiveEdit() {
        if (activeEditRow && activeEditOriginalHtml) {
          activeEditRow.innerHTML = activeEditOriginalHtml;
          activeEditRow.classList.remove('inline-form-row');
          activeEditRow = null;
          activeEditOriginalHtml = '';
        }
      }

      function removeCreateRow() {
        const existing = document.getElementById('inlineCreateSertifikasiRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', kategoriVal = 'Sertifikasi Kompetensi') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateSertifikasiRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#jenis-sertifikasi-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateSertifikasiRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateSertifikasiForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: S6" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateSertifikasiForm" class="form-control form-control-sm" placeholder="Contoh: Sertifikasi BNSP" maxlength="150" required>
          </td>
          <td>
            <select name="kategori" form="inlineCreateSertifikasiForm" class="form-select form-select-sm" required>
              ${buildKategoriOptionsHtml(kategoriVal)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateSertifikasiForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateSertifikasi" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const kategoriSelect = tr.querySelector('select[name="kategori"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (kategoriSelect) kategoriSelect.value = kategoriVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateSertifikasi')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', 'Sertifikasi Kompetensi');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-sertifikasi');
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
        const kategori = editBtn.dataset.kategori || 'Sertifikasi Kompetensi';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditSertifikasiForm" class="form-control form-control-sm text-uppercase" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditSertifikasiForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <select name="kategori" form="inlineEditSertifikasiForm" class="form-select form-select-sm" required>
              ${buildKategoriOptionsHtml(kategori)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditSertifikasiForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const kategoriSelect = tr.querySelector('select[name="kategori"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (kategoriSelect) kategoriSelect.value = kategori;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#jenis-sertifikasi-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldKategori : 'Sertifikasi Kompetensi'
          );
        });
      }
    });
  </script>
@endpush
