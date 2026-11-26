@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jam Kerja</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kehadiran</span>
            <span class="breadcrumb-item active">Jam Kerja</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineJamKerja" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jam Kerja
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
      <form id="inlineCreateJamKerjaForm" action="{{ route('jam-kerja.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditJamKerjaForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jam Kerja</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jam-kerja-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jam-kerja-table-wrap,
    .jam-kerja-table-wrap .dt-container,
    #jam-kerja-table,
    #jam-kerja-table th,
    #jam-kerja-table td {
      font-size: 11px !important;
    }

    #jam-kerja-table th,
    #jam-kerja-table td {
      padding: 0.45rem 0.6rem;
    }

    #jam-kerja-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #jam-kerja-table tr.inline-form-row input.form-control {
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
      const btnTambah = document.getElementById('btnTambahInlineJamKerja');
      const editForm = document.getElementById('inlineEditJamKerjaForm');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldNama = @json(old('nama', ''));
      const oldNormal = @json((bool) old('is_normal', false));
      const oldDatang = @json(old('jam_datang', '08:00'));
      const oldPulang = @json(old('jam_pulang', '16:00'));

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
        const existing = document.getElementById('inlineCreateJamKerjaRow');
        if (existing) existing.remove();
      }

      function openCreateRow(namaVal = '', normalVal = false, datangVal = '08:00', pulangVal = '16:00') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateJamKerjaRow');
        if (existing) {
          existing.querySelector('input[name="nama"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#jam-kerja-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateJamKerjaRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="nama" form="inlineCreateJamKerjaForm" class="form-control form-control-sm" placeholder="Contoh: Shift Pagi" maxlength="150" required>
          </td>
          <td class="text-center">
            <input type="checkbox" name="is_normal" value="1" form="inlineCreateJamKerjaForm" class="form-check-input">
          </td>
          <td>
            <input type="time" name="jam_datang" form="inlineCreateJamKerjaForm" class="form-control form-control-sm text-center" required>
          </td>
          <td>
            <input type="time" name="jam_pulang" form="inlineCreateJamKerjaForm" class="form-control form-control-sm text-center" required>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateJamKerjaForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateJamKerja" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const namaInput = tr.querySelector('input[name="nama"]');
        const normalInput = tr.querySelector('input[name="is_normal"]');
        const datangInput = tr.querySelector('input[name="jam_datang"]');
        const pulangInput = tr.querySelector('input[name="jam_pulang"]');
        if (namaInput) namaInput.value = namaVal;
        if (normalInput) normalInput.checked = normalVal;
        if (datangInput) datangInput.value = datangVal;
        if (pulangInput) pulangInput.value = pulangVal;
        namaInput?.focus();

        tr.querySelector('#btnCancelInlineCreateJamKerja')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', false, '08:00', '16:00');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-jamkerja');
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
        const isNormal = editBtn.dataset.isNormal === '1';
        const jamDatang = editBtn.dataset.jamDatang || '';
        const jamPulang = editBtn.dataset.jamPulang || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="nama" form="inlineEditJamKerjaForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td class="text-center">
            <input type="checkbox" name="is_normal" value="1" form="inlineEditJamKerjaForm" class="form-check-input">
          </td>
          <td>
            <input type="time" name="jam_datang" form="inlineEditJamKerjaForm" class="form-control form-control-sm text-center" required>
          </td>
          <td>
            <input type="time" name="jam_pulang" form="inlineEditJamKerjaForm" class="form-control form-control-sm text-center" required>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditJamKerjaForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const namaInput = tr.querySelector('input[name="nama"]');
        const normalInput = tr.querySelector('input[name="is_normal"]');
        const datangInput = tr.querySelector('input[name="jam_datang"]');
        const pulangInput = tr.querySelector('input[name="jam_pulang"]');
        if (namaInput) namaInput.value = nama;
        if (normalInput) normalInput.checked = isNormal;
        if (datangInput) datangInput.value = jamDatang;
        if (pulangInput) pulangInput.value = jamPulang;
        namaInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#jam-kerja-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldNama : '',
            hasErrors ? oldNormal : false,
            hasErrors ? oldDatang : '08:00',
            hasErrors ? oldPulang : '16:00'
          );
        });
      }
    });
  </script>
@endpush
