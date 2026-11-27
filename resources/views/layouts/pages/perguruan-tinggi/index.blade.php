@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Perguruan Tinggi</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Pelengkap</span>
            <span class="breadcrumb-item active">Perguruan Tinggi</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlinePt" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Perguruan Tinggi
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
      <form id="inlineCreatePtForm" action="{{ route('perguruan-tinggi.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditPtForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Perguruan Tinggi</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive perguruan-tinggi-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .perguruan-tinggi-table-wrap,
    .perguruan-tinggi-table-wrap .dt-container,
    #perguruan-tinggi-table,
    #perguruan-tinggi-table th,
    #perguruan-tinggi-table td {
      font-size: 11px !important;
    }

    #perguruan-tinggi-table th,
    #perguruan-tinggi-table td {
      padding: 0.45rem 0.6rem;
    }

    #perguruan-tinggi-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #perguruan-tinggi-table tr.inline-form-row input.form-control {
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
      const btnTambah = document.getElementById('btnTambahInlinePt');
      const editForm = document.getElementById('inlineEditPtForm');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldAlamat = @json(old('alamat', ''));
      const oldNoTelp = @json(old('no_telp', ''));

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
        const existing = document.getElementById('inlineCreatePtRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', alamatVal = '', telpVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreatePtRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#perguruan-tinggi-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreatePtRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreatePtForm" class="form-control form-control-sm text-uppercase" placeholder="Kode PT" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreatePtForm" class="form-control form-control-sm" placeholder="Nama Perguruan Tinggi" maxlength="180" required>
          </td>
          <td>
            <input type="text" name="alamat" form="inlineCreatePtForm" class="form-control form-control-sm" placeholder="Alamat (opsional)" maxlength="255">
          </td>
          <td>
            <input type="text" name="no_telp" form="inlineCreatePtForm" class="form-control form-control-sm" placeholder="No Telp" maxlength="30">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreatePtForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreatePt" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const alamatInput = tr.querySelector('input[name="alamat"]');
        const telpInput = tr.querySelector('input[name="no_telp"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (alamatInput) alamatInput.value = alamatVal;
        if (telpInput) telpInput.value = telpVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreatePt')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '', '');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-pt');
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
        const alamat = editBtn.dataset.alamat || '';
        const noTelp = editBtn.dataset.noTelp || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditPtForm" class="form-control form-control-sm text-uppercase" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditPtForm" class="form-control form-control-sm" maxlength="180" required>
          </td>
          <td>
            <input type="text" name="alamat" form="inlineEditPtForm" class="form-control form-control-sm" maxlength="255">
          </td>
          <td>
            <input type="text" name="no_telp" form="inlineEditPtForm" class="form-control form-control-sm" maxlength="30">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditPtForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const alamatInput = tr.querySelector('input[name="alamat"]');
        const telpInput = tr.querySelector('input[name="no_telp"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (alamatInput) alamatInput.value = alamat;
        if (telpInput) telpInput.value = noTelp;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#perguruan-tinggi-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldAlamat : '',
            hasErrors ? oldNoTelp : ''
          );
        });
      }
    });
  </script>
@endpush
