@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Prodi Perguruan Tinggi</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Pelengkap</span>
            <span class="breadcrumb-item active">Prodi Perguruan Tinggi</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineProdiPt" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Prodi
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

      <!-- Filter Perguruan Tinggi Card -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row align-items-center g-2">
            <label for="filterPerguruanTinggi" class="col-md-2 col-form-label fw-semibold text-primary mb-0">
              Perguruan Tinggi
            </label>
            <div class="col-md-5">
              <select id="filterPerguruanTinggi" class="form-select form-select-sm select2" data-placeholder="-- Semua Perguruan Tinggi --">
                <option value="">-- Semua Perguruan Tinggi --</option>
                @foreach ($perguruanTinggis as $pt)
                  <option value="{{ $pt->id }}" {{ (string) $defaultPtId === (string) $pt->id ? 'selected' : '' }}>
                    [{{ $pt->kode }}] {{ $pt->nama }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateProdiPtForm" action="{{ route('prodi-perguruan-tinggi.store') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="perguruan_tinggi_id" id="createPtIdInput" value="{{ $defaultPtId }}">
      </form>
      <form id="inlineEditProdiPtForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
        <input type="hidden" name="perguruan_tinggi_id" id="editPtIdInput" value="{{ $defaultPtId }}">
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Prodi Perguruan Tinggi</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive prodi-pt-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .prodi-pt-table-wrap,
    .prodi-pt-table-wrap .dt-container,
    #prodi-perguruan-tinggi-table,
    #prodi-perguruan-tinggi-table th,
    #prodi-perguruan-tinggi-table td {
      font-size: 11px !important;
    }

    #prodi-perguruan-tinggi-table th,
    #prodi-perguruan-tinggi-table td {
      padding: 0.45rem 0.6rem;
    }

    #prodi-perguruan-tinggi-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #prodi-perguruan-tinggi-table tr.inline-form-row input.form-control,
    #prodi-perguruan-tinggi-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineProdiPt');
      const editForm = document.getElementById('inlineEditProdiPtForm');
      const filterPt = document.getElementById('filterPerguruanTinggi');
      const createPtIdInput = document.getElementById('createPtIdInput');
      const editPtIdInput = document.getElementById('editPtIdInput');
      const jenjangs = @json($jenjangs);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldJenjangId = @json((string) old('jenjang_pendidikan_id', ''));
      const oldAlamat = @json(old('alamat', ''));
      const oldNoTelp = @json(old('no_telp', ''));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function escapeHtml(str) {
        return String(str ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function buildJenjangOptionsHtml(selectedId = '') {
        let html = '<option value="">-- Pilih Jenjang --</option>';
        jenjangs.forEach(function (j) {
          const selected = String(j.id) === String(selectedId) ? ' selected' : '';
          html += `<option value="${j.id}"${selected}>${escapeHtml(j.nama)}</option>`;
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
        const existing = document.getElementById('inlineCreateProdiPtRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', jenjangIdVal = '', alamatVal = '', telpVal = '') {
        const selectedPt = filterPt?.value || '';
        if (!selectedPt) {
          alert('Silakan pilih Perguruan Tinggi terlebih dahulu pada filter di atas sebelum menambahkan Prodi.');
          filterPt?.focus();
          return;
        }

        if (createPtIdInput) createPtIdInput.value = selectedPt;

        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateProdiPtRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#prodi-perguruan-tinggi-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateProdiPtRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateProdiPtForm" class="form-control form-control-sm text-uppercase" placeholder="Kode Prodi" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateProdiPtForm" class="form-control form-control-sm" placeholder="Nama Program Studi" maxlength="180" required>
          </td>
          <td>
            <select name="jenjang_pendidikan_id" form="inlineCreateProdiPtForm" class="form-select form-select-sm" required>
              ${buildJenjangOptionsHtml(jenjangIdVal)}
            </select>
          </td>
          <td>
            <input type="text" name="alamat" form="inlineCreateProdiPtForm" class="form-control form-control-sm" placeholder="Alamat (opsional)" maxlength="255">
          </td>
          <td>
            <input type="text" name="no_telp" form="inlineCreateProdiPtForm" class="form-control form-control-sm" placeholder="No Telp" maxlength="30">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateProdiPtForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateProdiPt" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const jenjangSelect = tr.querySelector('select[name="jenjang_pendidikan_id"]');
        const alamatInput = tr.querySelector('input[name="alamat"]');
        const telpInput = tr.querySelector('input[name="no_telp"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (jenjangSelect && jenjangIdVal) jenjangSelect.value = jenjangIdVal;
        if (alamatInput) alamatInput.value = alamatVal;
        if (telpInput) telpInput.value = telpVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateProdiPt')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '', '', '');
        });
      }

      if (typeof $ !== 'undefined' && filterPt) {
        $(filterPt).on('change', function () {
          if (createPtIdInput) createPtIdInput.value = this.value;
          removeCreateRow();
          cancelActiveEdit();
          if (window.LaravelDataTables && window.LaravelDataTables['prodi-perguruan-tinggi-table']) {
            window.LaravelDataTables['prodi-perguruan-tinggi-table'].ajax.reload();
          }
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-prodi-pt');
        if (!editBtn) return;

        removeCreateRow();
        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');
        if (editPtIdInput) editPtIdInput.value = editBtn.dataset.perguruanTinggiId || '';

        const rowNo = tr.cells[0]?.textContent?.trim() || '';
        const kode = editBtn.dataset.kode || '';
        const nama = editBtn.dataset.nama || '';
        const jenjangId = editBtn.dataset.jenjangId || '';
        const alamat = editBtn.dataset.alamat || '';
        const noTelp = editBtn.dataset.noTelp || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditProdiPtForm" class="form-control form-control-sm text-uppercase" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditProdiPtForm" class="form-control form-control-sm" maxlength="180" required>
          </td>
          <td>
            <select name="jenjang_pendidikan_id" form="inlineEditProdiPtForm" class="form-select form-select-sm" required>
              ${buildJenjangOptionsHtml(jenjangId)}
            </select>
          </td>
          <td>
            <input type="text" name="alamat" form="inlineEditProdiPtForm" class="form-control form-control-sm" maxlength="255">
          </td>
          <td>
            <input type="text" name="no_telp" form="inlineEditProdiPtForm" class="form-control form-control-sm" maxlength="30">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditProdiPtForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const jenjangSelect = tr.querySelector('select[name="jenjang_pendidikan_id"]');
        const alamatInput = tr.querySelector('input[name="alamat"]');
        const telpInput = tr.querySelector('input[name="no_telp"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (jenjangSelect) jenjangSelect.value = jenjangId;
        if (alamatInput) alamatInput.value = alamat;
        if (telpInput) telpInput.value = noTelp;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#prodi-perguruan-tinggi-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldJenjangId : '',
            hasErrors ? oldAlamat : '',
            hasErrors ? oldNoTelp : ''
          );
        });
      }
    });
  </script>
@endpush
