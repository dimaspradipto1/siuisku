@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Kecamatan</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Wilayah</span>
            <span class="breadcrumb-item active">Kecamatan</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineKecamatan" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kecamatan
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

      <!-- Filter Card (Cascading Provinsi -> Kabupaten/Kota) -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row align-items-center g-3">
            <div class="col-md-6">
              <div class="row align-items-center g-2">
                <label for="filterProvinsi" class="col-sm-3 col-form-label fw-semibold text-primary mb-0">
                  Provinsi
                </label>
                <div class="col-sm-9">
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
            <div class="col-md-6">
              <div class="row align-items-center g-2">
                <label for="filterKabKota" class="col-sm-3 col-form-label fw-semibold text-primary mb-0">
                  Kota / Kab
                </label>
                <div class="col-sm-9">
                  <select id="filterKabKota" class="form-select form-select-sm select2" data-placeholder="-- Semua Kabupaten/Kota --">
                    <option value="">-- Semua Kabupaten/Kota --</option>
                    @foreach ($kabupatenKotas as $kk)
                      @if (! $defaultProvinsiId || (string) $kk->provinsi_id === (string) $defaultProvinsiId)
                        <option value="{{ $kk->id }}" {{ (string) $defaultKabKotaId === (string) $kk->id ? 'selected' : '' }}>
                          {{ $kk->nama }}
                        </option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateKecamatanForm" action="{{ route('kecamatan.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditKecamatanForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Kecamatan</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive kecamatan-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .kecamatan-table-wrap,
    .kecamatan-table-wrap .dt-container,
    #kecamatan-table,
    #kecamatan-table th,
    #kecamatan-table td {
      font-size: 11px !important;
    }

    #kecamatan-table th,
    #kecamatan-table td {
      padding: 0.45rem 0.6rem;
    }

    #kecamatan-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #kecamatan-table tr.inline-form-row input.form-control,
    #kecamatan-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineKecamatan');
      const editForm = document.getElementById('inlineEditKecamatanForm');
      const filterProvinsi = document.getElementById('filterProvinsi');
      const filterKabKota = document.getElementById('filterKabKota');
      const allKabKotas = @json($kabupatenKotas);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldKabKotaId = @json((string) old('kabupaten_kota_id', ''));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function escapeHtml(str) {
        return String(str ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function buildKabKotaOptionsHtml(selectedId = '', provinsiFilterId = '') {
        let html = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        allKabKotas.forEach(function (kk) {
          if (provinsiFilterId && String(kk.provinsi_id) !== String(provinsiFilterId)) return;
          const selected = String(kk.id) === String(selectedId) ? ' selected' : '';
          html += `<option value="${kk.id}"${selected}>${escapeHtml(kk.nama)}</option>`;
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
        const existing = document.getElementById('inlineCreateKecamatanRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', kabIdVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateKecamatanRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#kecamatan-table tbody');
        if (!tbody) return;

        const defaultKab = kabIdVal || (filterKabKota?.value || '');
        const currentProv = filterProvinsi?.value || '';

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateKecamatanRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateKecamatanForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: 217113" maxlength="15" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateKecamatanForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: LUBUK BAJA" maxlength="150" required>
          </td>
          <td>
            <select name="kabupaten_kota_id" form="inlineCreateKecamatanForm" class="form-select form-select-sm" required>
              ${buildKabKotaOptionsHtml(defaultKab, defaultKab ? '' : currentProv)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateKecamatanForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateKecamatan" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const kabSelect = tr.querySelector('select[name="kabupaten_kota_id"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (kabSelect) kabSelect.value = defaultKab;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateKecamatan')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', filterKabKota?.value || '');
        });
      }

      if (typeof $ !== 'undefined' && filterProvinsi && filterKabKota) {
        $(filterProvinsi).on('change', function () {
          const provId = this.value;
          const matching = allKabKotas.filter(kk => !provId || String(kk.provinsi_id) === String(provId));

          let opts = '<option value="">-- Semua Kabupaten/Kota --</option>';
          matching.forEach(function (kk) {
            opts += `<option value="${kk.id}">${escapeHtml(kk.nama)}</option>`;
          });

          $(filterKabKota).html(opts).val('').trigger('change.select2');
          removeCreateRow();
          cancelActiveEdit();

          if (window.LaravelDataTables && window.LaravelDataTables['kecamatan-table']) {
            window.LaravelDataTables['kecamatan-table'].ajax.reload();
          }
        });

        $(filterKabKota).on('change', function () {
          removeCreateRow();
          cancelActiveEdit();
          if (window.LaravelDataTables && window.LaravelDataTables['kecamatan-table']) {
            window.LaravelDataTables['kecamatan-table'].ajax.reload();
          }
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-kecamatan');
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
        const kabId = editBtn.dataset.kabupatenKotaId || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditKecamatanForm" class="form-control form-control-sm text-uppercase" maxlength="15" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditKecamatanForm" class="form-control form-control-sm text-uppercase" maxlength="150" required>
          </td>
          <td>
            <select name="kabupaten_kota_id" form="inlineEditKecamatanForm" class="form-select form-select-sm" required>
              ${buildKabKotaOptionsHtml(kabId)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditKecamatanForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const kabSelect = tr.querySelector('select[name="kabupaten_kota_id"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (kabSelect) kabSelect.value = kabId;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#kecamatan-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldKabKotaId : (filterKabKota?.value || '')
          );
        });
      }
    });
  </script>
@endpush
