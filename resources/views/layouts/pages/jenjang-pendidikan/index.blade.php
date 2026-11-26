@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jenjang Pendidikan</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Pelengkap</span>
            <span class="breadcrumb-item active">Jenjang Pendidikan</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineJenjang" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenjang Pendidikan
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
      <form id="inlineCreateJenjangForm" action="{{ route('jenjang-pendidikan.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditJenjangForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jenjang Pendidikan</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jenjang-pendidikan-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jenjang-pendidikan-table-wrap,
    .jenjang-pendidikan-table-wrap .dt-container,
    #jenjang-pendidikan-table,
    #jenjang-pendidikan-table th,
    #jenjang-pendidikan-table td {
      font-size: 11px !important;
    }

    #jenjang-pendidikan-table th,
    #jenjang-pendidikan-table td {
      padding: 0.45rem 0.6rem;
    }

    #jenjang-pendidikan-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #jenjang-pendidikan-table tr.inline-form-row input.form-control {
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
      const btnTambah = document.getElementById('btnTambahInlineJenjang');
      const editForm = document.getElementById('inlineEditJenjangForm');
      const nextUrutan = @json($nextUrutan);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldNamaEn = @json(old('nama_en', ''));
      const oldUrutan = @json(old('urutan', $nextUrutan));
      const oldPt = @json((bool) old('is_perguruan_tinggi', false));
      const oldPasca = @json((bool) old('is_pasca_sarjana', false));

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
        const existing = document.getElementById('inlineCreateJenjangRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', namaEnVal = '', urutanVal = nextUrutan, ptVal = false, pascaVal = false) {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateJenjangRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#jenjang-pendidikan-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateJenjangRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateJenjangForm" class="form-control form-control-sm" placeholder="Contoh: S4" maxlength="15" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateJenjangForm" class="form-control form-control-sm" placeholder="Nama Jenjang" maxlength="150" required>
          </td>
          <td>
            <input type="text" name="nama_en" form="inlineCreateJenjangForm" class="form-control form-control-sm" placeholder="Opsional" maxlength="150">
          </td>
          <td>
            <input type="number" min="0" max="999" name="urutan" form="inlineCreateJenjangForm" class="form-control form-control-sm text-center" required>
          </td>
          <td class="text-center">
            <input type="checkbox" name="is_perguruan_tinggi" value="1" form="inlineCreateJenjangForm" class="form-check-input">
          </td>
          <td class="text-center">
            <input type="checkbox" name="is_pasca_sarjana" value="1" form="inlineCreateJenjangForm" class="form-check-input">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateJenjangForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateJenjang" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const namaEnInput = tr.querySelector('input[name="nama_en"]');
        const urutanInput = tr.querySelector('input[name="urutan"]');
        const ptInput = tr.querySelector('input[name="is_perguruan_tinggi"]');
        const pascaInput = tr.querySelector('input[name="is_pasca_sarjana"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (namaEnInput) namaEnInput.value = namaEnVal;
        if (urutanInput) urutanInput.value = urutanVal;
        if (ptInput) ptInput.checked = ptVal;
        if (pascaInput) pascaInput.checked = pascaVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateJenjang')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '', nextUrutan, false, false);
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-jenjang');
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
        const namaEn = editBtn.dataset.namaEn || '';
        const urutan = editBtn.dataset.urutan || '0';
        const pt = editBtn.dataset.pt === '1';
        const pasca = editBtn.dataset.pasca === '1';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditJenjangForm" class="form-control form-control-sm" maxlength="15" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditJenjangForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <input type="text" name="nama_en" form="inlineEditJenjangForm" class="form-control form-control-sm" maxlength="150">
          </td>
          <td>
            <input type="number" min="0" max="999" name="urutan" form="inlineEditJenjangForm" class="form-control form-control-sm text-center" required>
          </td>
          <td class="text-center">
            <input type="checkbox" name="is_perguruan_tinggi" value="1" form="inlineEditJenjangForm" class="form-check-input">
          </td>
          <td class="text-center">
            <input type="checkbox" name="is_pasca_sarjana" value="1" form="inlineEditJenjangForm" class="form-check-input">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditJenjangForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const namaEnInput = tr.querySelector('input[name="nama_en"]');
        const urutanInput = tr.querySelector('input[name="urutan"]');
        const ptInput = tr.querySelector('input[name="is_perguruan_tinggi"]');
        const pascaInput = tr.querySelector('input[name="is_pasca_sarjana"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (namaEnInput) namaEnInput.value = namaEn;
        if (urutanInput) urutanInput.value = urutan;
        if (ptInput) ptInput.checked = pt;
        if (pascaInput) pascaInput.checked = pasca;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#jenjang-pendidikan-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldNamaEn : '',
            hasErrors ? oldUrutan : nextUrutan,
            hasErrors ? oldPt : false,
            hasErrors ? oldPasca : false
          );
        });
      }
    });
  </script>
@endpush
