@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jabatan Akademik</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kepegawaian</span>
            <span class="breadcrumb-item active">Klasifikasi SDM</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineKsdm" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jabatan Akademik
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

      <!-- Filter Card -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row align-items-center g-2">
            <label for="filterJenisJabatan" class="col-md-2 col-form-label fw-semibold text-primary mb-0">
              Jenis Jabatan
            </label>
            <div class="col-md-4">
              <select id="filterJenisJabatan" class="form-select form-select-sm select2" data-placeholder="-- Semua Jenis Jabatan --">
                <option value="">-- Semua Jenis Jabatan --</option>
                @foreach ($jenisJabatanOptions as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateKsdmForm" action="{{ route('klasifikasi-sdm.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditKsdmForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jabatan Akademik</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive klasifikasi-sdm-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .klasifikasi-sdm-table-wrap,
    .klasifikasi-sdm-table-wrap .dt-container,
    #klasifikasi-sdm-table,
    #klasifikasi-sdm-table th,
    #klasifikasi-sdm-table td {
      font-size: 11px !important;
    }

    #klasifikasi-sdm-table th,
    #klasifikasi-sdm-table td {
      padding: 0.45rem 0.6rem;
    }

    #klasifikasi-sdm-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #klasifikasi-sdm-table tr.inline-form-row input.form-control,
    #klasifikasi-sdm-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineKsdm');
      const editForm = document.getElementById('inlineEditKsdmForm');
      const filterSelect = document.getElementById('filterJenisJabatan');
      const jenisJabatanOptions = @json($jenisJabatanOptions);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldJenisJabatan = @json(old('jenis_jabatan', 'Dosen'));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function buildJenisJabatanOptionsHtml(selectedVal) {
        return jenisJabatanOptions.map(function (opt) {
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
        const existing = document.getElementById('inlineCreateKsdmRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', jenisVal = 'Dosen') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateKsdmRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#klasifikasi-sdm-table tbody');
        if (!tbody) return;

        const defaultJenis = jenisVal || (filterSelect?.value || 'Dosen');

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateKsdmRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateKsdmForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: K18" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateKsdmForm" class="form-control form-control-sm" placeholder="Contoh: Staf Akademik" maxlength="150" required>
          </td>
          <td>
            <select name="jenis_jabatan" form="inlineCreateKsdmForm" class="form-select form-select-sm" required>
              ${buildJenisJabatanOptionsHtml(defaultJenis)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateKsdmForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateKsdm" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const jenisSelect = tr.querySelector('select[name="jenis_jabatan"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (jenisSelect) jenisSelect.value = defaultJenis;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateKsdm')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', filterSelect?.value || 'Dosen');
        });
      }

      if (typeof $ !== 'undefined' && filterSelect) {
        $(filterSelect).on('change', function () {
          removeCreateRow();
          cancelActiveEdit();
          if (window.LaravelDataTables && window.LaravelDataTables['klasifikasi-sdm-table']) {
            window.LaravelDataTables['klasifikasi-sdm-table'].ajax.reload();
          }
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-ksdm');
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
        const jenisJabatan = editBtn.dataset.jenisJabatan || 'Dosen';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditKsdmForm" class="form-control form-control-sm text-uppercase" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditKsdmForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <select name="jenis_jabatan" form="inlineEditKsdmForm" class="form-select form-select-sm" required>
              ${buildJenisJabatanOptionsHtml(jenisJabatan)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditKsdmForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const jenisSelect = tr.querySelector('select[name="jenis_jabatan"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (jenisSelect) jenisSelect.value = jenisJabatan;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#klasifikasi-sdm-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldJenisJabatan : 'Dosen'
          );
        });
      }
    });
  </script>
@endpush
