@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Hubungan Kerja</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kepegawaian</span>
            <span class="breadcrumb-item active">Hubungan Kerja</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineHk" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Hubungan Kerja
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
      <form id="inlineCreateHkForm" action="{{ route('hubungan-kerja.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditHkForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Hubungan Kerja</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive hubungan-kerja-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .hubungan-kerja-table-wrap,
    .hubungan-kerja-table-wrap .dt-container,
    #hubungan-kerja-table,
    #hubungan-kerja-table th,
    #hubungan-kerja-table td {
      font-size: 11px !important;
    }

    #hubungan-kerja-table th,
    #hubungan-kerja-table td {
      padding: 0.45rem 0.6rem;
    }

    #hubungan-kerja-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #hubungan-kerja-table tr.inline-form-row input.form-control,
    #hubungan-kerja-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineHk');
      const editForm = document.getElementById('inlineEditHkForm');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldStatusAktif = @json((bool) old('status_aktif', true));
      const oldPegawaiTetap = @json((bool) old('pegawai_tetap', false));
      const oldJenisAsn = @json(old('jenis_asn', 'Non ASN'));

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
        const existing = document.getElementById('inlineCreateHkRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', aktifVal = true, tetapVal = false, asnVal = 'Non ASN') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateHkRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#hubungan-kerja-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateHkRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateHkForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: H7" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateHkForm" class="form-control form-control-sm" placeholder="Contoh: Magang" maxlength="150" required>
          </td>
          <td class="text-center">
            <input type="checkbox" name="status_aktif" value="1" form="inlineCreateHkForm" class="form-check-input">
          </td>
          <td class="text-center">
            <input type="checkbox" name="pegawai_tetap" value="1" form="inlineCreateHkForm" class="form-check-input">
          </td>
          <td>
            <select name="jenis_asn" form="inlineCreateHkForm" class="form-select form-select-sm" required>
              <option value="Non ASN">Non ASN</option>
              <option value="PNS">PNS</option>
              <option value="PPPK">PPPK</option>
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateHkForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateHk" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const aktifInput = tr.querySelector('input[name="status_aktif"]');
        const tetapInput = tr.querySelector('input[name="pegawai_tetap"]');
        const asnSelect = tr.querySelector('select[name="jenis_asn"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (aktifInput) aktifInput.checked = aktifVal;
        if (tetapInput) tetapInput.checked = tetapVal;
        if (asnSelect) asnSelect.value = asnVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateHk')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', true, false, 'Non ASN');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-hk');
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
        const aktif = editBtn.dataset.statusAktif === '1';
        const tetap = editBtn.dataset.pegawaiTetap === '1';
        const jenisAsn = editBtn.dataset.jenisAsn || 'Non ASN';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditHkForm" class="form-control form-control-sm text-uppercase" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditHkForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td class="text-center">
            <input type="checkbox" name="status_aktif" value="1" form="inlineEditHkForm" class="form-check-input">
          </td>
          <td class="text-center">
            <input type="checkbox" name="pegawai_tetap" value="1" form="inlineEditHkForm" class="form-check-input">
          </td>
          <td>
            <select name="jenis_asn" form="inlineEditHkForm" class="form-select form-select-sm" required>
              <option value="Non ASN">Non ASN</option>
              <option value="PNS">PNS</option>
              <option value="PPPK">PPPK</option>
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditHkForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const aktifInput = tr.querySelector('input[name="status_aktif"]');
        const tetapInput = tr.querySelector('input[name="pegawai_tetap"]');
        const asnSelect = tr.querySelector('select[name="jenis_asn"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (aktifInput) aktifInput.checked = aktif;
        if (tetapInput) tetapInput.checked = tetap;
        if (asnSelect) asnSelect.value = jenisAsn;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#hubungan-kerja-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldStatusAktif : true,
            hasErrors ? oldPegawaiTetap : false,
            hasErrors ? oldJenisAsn : 'Non ASN'
          );
        });
      }
    });
  </script>
@endpush
