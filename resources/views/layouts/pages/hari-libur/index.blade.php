@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Hari Libur</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kehadiran</span>
            <span class="breadcrumb-item active">Hari Libur</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineLibur" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Hari Libur
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

      <!-- Filter Tahun Card -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row align-items-center g-2">
            <label for="filterTahun" class="col-md-2 col-form-label fw-semibold text-primary mb-0">
              Tahun
            </label>
            <div class="col-md-3">
              <select id="filterTahun" class="form-select form-select-sm select2" data-placeholder="-- Semua Tahun --">
                <option value="">-- Semua Tahun --</option>
                @foreach ($tahunOptions as $th)
                  <option value="{{ $th }}" {{ (string) $defaultTahun === (string) $th ? 'selected' : '' }}>
                    {{ $th }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateLiburForm" action="{{ route('hari-libur.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditLiburForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Hari Libur</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive hari-libur-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .hari-libur-table-wrap,
    .hari-libur-table-wrap .dt-container,
    #hari-libur-table,
    #hari-libur-table th,
    #hari-libur-table td {
      font-size: 11px !important;
    }

    #hari-libur-table th,
    #hari-libur-table td {
      padding: 0.45rem 0.6rem;
    }

    #hari-libur-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #hari-libur-table tr.inline-form-row input.form-control {
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
      const btnTambah = document.getElementById('btnTambahInlineLibur');
      const editForm = document.getElementById('inlineEditLiburForm');
      const filterTahun = document.getElementById('filterTahun');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldNama = @json(old('nama', ''));
      const oldMulai = @json(old('tanggal_mulai', ''));
      const oldSelesai = @json(old('tanggal_selesai', ''));
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
        const existing = document.getElementById('inlineCreateLiburRow');
        if (existing) existing.remove();
      }

      function openCreateRow(namaVal = '', mulaiVal = '', selesaiVal = '', ketVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateLiburRow');
        if (existing) {
          existing.querySelector('input[name="nama"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#hari-libur-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateLiburRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="nama" form="inlineCreateLiburForm" class="form-control form-control-sm" placeholder="Contoh: Cuti Bersama" maxlength="150" required>
          </td>
          <td>
            <input type="date" name="tanggal_mulai" form="inlineCreateLiburForm" class="form-control form-control-sm" required>
          </td>
          <td>
            <input type="date" name="tanggal_selesai" form="inlineCreateLiburForm" class="form-control form-control-sm">
          </td>
          <td>
            <input type="text" name="keterangan" form="inlineCreateLiburForm" class="form-control form-control-sm" placeholder="Opsional" maxlength="500">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateLiburForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateLibur" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const namaInput = tr.querySelector('input[name="nama"]');
        const mulaiInput = tr.querySelector('input[name="tanggal_mulai"]');
        const selesaiInput = tr.querySelector('input[name="tanggal_selesai"]');
        const ketInput = tr.querySelector('input[name="keterangan"]');
        if (namaInput) namaInput.value = namaVal;
        if (mulaiInput) mulaiInput.value = mulaiVal;
        if (selesaiInput) selesaiInput.value = selesaiVal;
        if (ketInput) ketInput.value = ketVal;
        namaInput?.focus();

        tr.querySelector('#btnCancelInlineCreateLibur')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '', '');
        });
      }

      if (typeof $ !== 'undefined' && filterTahun) {
        $(filterTahun).on('change', function () {
          removeCreateRow();
          cancelActiveEdit();
          if (window.LaravelDataTables && window.LaravelDataTables['hari-libur-table']) {
            window.LaravelDataTables['hari-libur-table'].ajax.reload();
          }
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-libur');
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
        const mulai = editBtn.dataset.tanggalMulai || '';
        const selesai = editBtn.dataset.tanggalSelesai || '';
        const ket = editBtn.dataset.keterangan || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="nama" form="inlineEditLiburForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <input type="date" name="tanggal_mulai" form="inlineEditLiburForm" class="form-control form-control-sm" required>
          </td>
          <td>
            <input type="date" name="tanggal_selesai" form="inlineEditLiburForm" class="form-control form-control-sm">
          </td>
          <td>
            <input type="text" name="keterangan" form="inlineEditLiburForm" class="form-control form-control-sm" maxlength="500">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditLiburForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const namaInput = tr.querySelector('input[name="nama"]');
        const mulaiInput = tr.querySelector('input[name="tanggal_mulai"]');
        const selesaiInput = tr.querySelector('input[name="tanggal_selesai"]');
        const ketInput = tr.querySelector('input[name="keterangan"]');
        if (namaInput) namaInput.value = nama;
        if (mulaiInput) mulaiInput.value = mulai;
        if (selesaiInput) selesaiInput.value = selesai;
        if (ketInput) ketInput.value = ket;
        namaInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#hari-libur-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldNama : '',
            hasErrors ? oldMulai : '',
            hasErrors ? oldSelesai : '',
            hasErrors ? oldKet : ''
          );
        });
      }
    });
  </script>
@endpush
