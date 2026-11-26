@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Kabupaten / Kota</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Wilayah</span>
            <span class="breadcrumb-item active">Kabupaten / Kota</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineKabKota" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kabupaten / Kota
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
            <label for="filterProvinsi" class="col-md-2 col-form-label fw-semibold text-primary mb-0">
              Provinsi
            </label>
            <div class="col-md-4">
              <select id="filterProvinsi" class="form-select form-select-sm select2" data-placeholder="-- Semua Provinsi --">
                <option value="">-- Semua Provinsi --</option>
                @foreach ($provinsis as $prov)
                  <option value="{{ $prov->id }}" {{ (string) $defaultProvinsiId === (string) $prov->id ? 'selected' : '' }}>
                    {{ $prov->nama }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateKabKotaForm" action="{{ route('kabupaten-kota.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditKabKotaForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Kabupaten / Kota</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive kabupaten-kota-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .kabupaten-kota-table-wrap,
    .kabupaten-kota-table-wrap .dt-container,
    #kabupaten-kota-table,
    #kabupaten-kota-table th,
    #kabupaten-kota-table td {
      font-size: 11px !important;
    }

    #kabupaten-kota-table th,
    #kabupaten-kota-table td {
      padding: 0.45rem 0.6rem;
    }

    #kabupaten-kota-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #kabupaten-kota-table tr.inline-form-row input.form-control,
    #kabupaten-kota-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineKabKota');
      const editForm = document.getElementById('inlineEditKabKotaForm');
      const filterProvinsi = document.getElementById('filterProvinsi');
      const provinsis = @json($provinsis);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldProvinsiId = @json((string) old('provinsi_id', ''));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function escapeHtml(str) {
        return String(str ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function buildProvinsiOptionsHtml(selectedId = '') {
        let html = '<option value="">-- Pilih Provinsi --</option>';
        provinsis.forEach(function (prov) {
          const selected = String(prov.id) === String(selectedId) ? ' selected' : '';
          html += `<option value="${prov.id}"${selected}>${escapeHtml(prov.nama)}</option>`;
        });
        return html;
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
        const existing = document.getElementById('inlineCreateKabKotaRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', provIdVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateKabKotaRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#kabupaten-kota-table tbody');
        if (!tbody) return;

        const defaultProv = provIdVal || (filterProvinsi?.value || '');

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateKabKotaRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateKabKotaForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: 2173" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateKabKotaForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: KOTA BATAM" maxlength="150" required>
          </td>
          <td>
            <select name="provinsi_id" form="inlineCreateKabKotaForm" class="form-select form-select-sm" required>
              ${buildProvinsiOptionsHtml(defaultProv)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateKabKotaForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateKabKota" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const provSelect = tr.querySelector('select[name="provinsi_id"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (provSelect) provSelect.value = defaultProv;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateKabKota')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', filterProvinsi?.value || '');
        });
      }

      if (typeof $ !== 'undefined' && filterProvinsi) {
        $(filterProvinsi).on('change', function () {
          removeCreateRow();
          cancelActiveEdit();
          if (window.LaravelDataTables && window.LaravelDataTables['kabupaten-kota-table']) {
            window.LaravelDataTables['kabupaten-kota-table'].ajax.reload();
          }
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-kabkota');
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
        const provId = editBtn.dataset.provinsiId || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditKabKotaForm" class="form-control form-control-sm text-uppercase" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditKabKotaForm" class="form-control form-control-sm text-uppercase" maxlength="150" required>
          </td>
          <td>
            <select name="provinsi_id" form="inlineEditKabKotaForm" class="form-select form-select-sm" required>
              ${buildProvinsiOptionsHtml(provId)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditKabKotaForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const provSelect = tr.querySelector('select[name="provinsi_id"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (provSelect) provSelect.value = provId;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#kabupaten-kota-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldProvinsiId : (filterProvinsi?.value || '')
          );
        });
      }
    });
  </script>
@endpush
