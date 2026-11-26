@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Jenis Izin</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kehadiran</span>
            <span class="breadcrumb-item active">Jenis Izin</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineIzin" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Izin
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
      <form id="inlineCreateIzinForm" action="{{ route('jenis-izin.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditIzinForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Jenis Izin</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive jenis-izin-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .jenis-izin-table-wrap,
    .jenis-izin-table-wrap .dt-container,
    #jenis-izin-table,
    #jenis-izin-table th,
    #jenis-izin-table td {
      font-size: 11px !important;
    }

    #jenis-izin-table th,
    #jenis-izin-table td {
      padding: 0.45rem 0.6rem;
    }

    #jenis-izin-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #jenis-izin-table tr.inline-form-row input.form-control,
    #jenis-izin-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineIzin');
      const editForm = document.getElementById('inlineEditIzinForm');
      const statusPresensis = @json($statusPresensis);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldStatusId = @json((string) old('jenis_kehadiran_id', ''));
      const oldMax = @json(old('maksimal_hari', ''));
      const oldPotong = @json((bool) old('potong_cuti', false));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function escapeHtml(str) {
        return String(str ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function buildStatusOptionsHtml(selectedId = '') {
        let html = '<option value="">-- Pilih Status Presensi --</option>';
        statusPresensis.forEach(function (st) {
          const selected = String(st.id) === String(selectedId) ? ' selected' : '';
          html += `<option value="${st.id}"${selected}>${escapeHtml(st.nama)}</option>`;
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
        const existing = document.getElementById('inlineCreateIzinRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', statusIdVal = '', maxVal = '', potongVal = false) {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateIzinRow');
        if (existing) {
          existing.querySelector('input[name="kode"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#jenis-izin-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateIzinRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateIzinForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: 010" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateIzinForm" class="form-control form-control-sm" placeholder="Contoh: Izin Umrah" maxlength="150" required>
          </td>
          <td>
            <select name="jenis_kehadiran_id" form="inlineCreateIzinForm" class="form-select form-select-sm">
              ${buildStatusOptionsHtml(statusIdVal)}
            </select>
          </td>
          <td>
            <input type="number" min="0" max="365" name="maksimal_hari" form="inlineCreateIzinForm" class="form-control form-control-sm text-center" placeholder="Opsional">
          </td>
          <td class="text-center">
            <input type="checkbox" name="potong_cuti" value="1" form="inlineCreateIzinForm" class="form-check-input">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateIzinForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateIzin" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const statusSelect = tr.querySelector('select[name="jenis_kehadiran_id"]');
        const maxInput = tr.querySelector('input[name="maksimal_hari"]');
        const potongInput = tr.querySelector('input[name="potong_cuti"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (statusSelect) statusSelect.value = statusIdVal;
        if (maxInput) maxInput.value = maxVal;
        if (potongInput) potongInput.checked = potongVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateIzin')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '', '', false);
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-izin');
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
        const statusId = editBtn.dataset.jenisKehadiranId || '';
        const maxHari = editBtn.dataset.maksimalHari || '';
        const potong = editBtn.dataset.potongCuti === '1';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditIzinForm" class="form-control form-control-sm text-uppercase" maxlength="10" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditIzinForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td>
            <select name="jenis_kehadiran_id" form="inlineEditIzinForm" class="form-select form-select-sm">
              ${buildStatusOptionsHtml(statusId)}
            </select>
          </td>
          <td>
            <input type="number" min="0" max="365" name="maksimal_hari" form="inlineEditIzinForm" class="form-control form-control-sm text-center" placeholder="Opsional">
          </td>
          <td class="text-center">
            <input type="checkbox" name="potong_cuti" value="1" form="inlineEditIzinForm" class="form-check-input">
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditIzinForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
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
        const statusSelect = tr.querySelector('select[name="jenis_kehadiran_id"]');
        const maxInput = tr.querySelector('input[name="maksimal_hari"]');
        const potongInput = tr.querySelector('input[name="potong_cuti"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (statusSelect) statusSelect.value = statusId;
        if (maxInput) maxInput.value = maxHari;
        if (potongInput) potongInput.checked = potong;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#jenis-izin-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldStatusId : '',
            hasErrors ? oldMax : '',
            hasErrors ? oldPotong : false
          );
        });
      }
    });
  </script>
@endpush
