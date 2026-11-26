@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jenis Kehadiran</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kehadiran</span>
            <span class="breadcrumb-item active">Jenis Kehadiran</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineKehadiran" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Kehadiran
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
      <form id="inlineCreateKehadiranForm" action="{{ route('jenis-kehadiran.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditKehadiranForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jenis Kehadiran</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jenis-kehadiran-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jenis-kehadiran-table-wrap,
    .jenis-kehadiran-table-wrap .dt-container,
    #jenis-kehadiran-table,
    #jenis-kehadiran-table th,
    #jenis-kehadiran-table td {
      font-size: 11px !important;
    }

    #jenis-kehadiran-table th,
    #jenis-kehadiran-table td {
      padding: 0.45rem 0.6rem;
    }

    #jenis-kehadiran-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #jenis-kehadiran-table tr.inline-form-row input.form-control {
      font-size: 12px;
      height: 34px;
    }

    #jenis-kehadiran-table tr.inline-form-row input[type="color"] {
      width: 34px;
      height: 34px;
      padding: 2px;
      cursor: pointer;
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
      const btnTambah = document.getElementById('btnTambahInlineKehadiran');
      const editForm = document.getElementById('inlineEditKehadiranForm');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldWarna = @json(old('warna', '#198754'));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function bindColorSync(tr) {
        const picker = tr.querySelector('.color-picker-sync');
        const text = tr.querySelector('input[name="warna"]');
        if (!picker || !text) return;

        picker.addEventListener('input', function () {
          text.value = this.value.toUpperCase();
        });

        text.addEventListener('input', function () {
          const val = this.value.trim();
          if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            picker.value = val;
          }
        });
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
        const existing = document.getElementById('inlineCreateKehadiranRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', warnaVal = '#198754') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateKehadiranRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#jenis-kehadiran-table tbody');
        if (!tbody) return;

        const pickerColor = /^#[0-9A-Fa-f]{6}$/.test(warnaVal) ? warnaVal : '#198754';

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateKehadiranRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateKehadiranForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: WFH" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateKehadiranForm" class="form-control form-control-sm" placeholder="Contoh: Work From Home" maxlength="150" required>
          </td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <input type="color" class="form-control form-control-color color-picker-sync" value="${pickerColor}" title="Pilih Warna">
              <input type="text" name="warna" form="inlineCreateKehadiranForm" class="form-control form-control-sm text-uppercase" placeholder="#F5BD24" maxlength="20">
            </div>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateKehadiranForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateKehadiran" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const warnaInput = tr.querySelector('input[name="warna"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (warnaInput) warnaInput.value = warnaVal;
        bindColorSync(tr);
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateKehadiran')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '#198754');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-kehadiran');
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
        const warna = editBtn.dataset.warna || '';
        const pickerColor = /^#[0-9A-Fa-f]{6}$/.test(warna) ? warna : '#198754';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditKehadiranForm" class="form-control form-control-sm text-uppercase" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditKehadiranForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <input type="color" class="form-control form-control-color color-picker-sync" value="${pickerColor}" title="Pilih Warna">
              <input type="text" name="warna" form="inlineEditKehadiranForm" class="form-control form-control-sm text-uppercase" placeholder="#F5BD24" maxlength="20">
            </div>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditKehadiranForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const warnaInput = tr.querySelector('input[name="warna"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (warnaInput) warnaInput.value = warna;
        bindColorSync(tr);
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#jenis-kehadiran-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldWarna : '#198754'
          );
        });
      }
    });
  </script>
@endpush
