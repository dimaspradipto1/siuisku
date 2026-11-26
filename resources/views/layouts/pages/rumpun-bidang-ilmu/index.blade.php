@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Rumpun Bidang Ilmu</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kepegawaian</span>
            <span class="breadcrumb-item active">Rumpun Bidang Ilmu</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineRbi" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Rumpun Bidang Ilmu
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
      <form id="inlineCreateRbiForm" action="{{ route('rumpun-bidang-ilmu.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditRbiForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Rumpun Bidang Ilmu</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive rumpun-bidang-ilmu-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .rumpun-bidang-ilmu-table-wrap,
    .rumpun-bidang-ilmu-table-wrap .dt-container,
    #rumpun-bidang-ilmu-table,
    #rumpun-bidang-ilmu-table th,
    #rumpun-bidang-ilmu-table td {
      font-size: 11px !important;
    }

    #rumpun-bidang-ilmu-table th,
    #rumpun-bidang-ilmu-table td {
      padding: 0.45rem 0.6rem;
    }

    #rumpun-bidang-ilmu-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #rumpun-bidang-ilmu-table tr.inline-form-row input.form-control,
    #rumpun-bidang-ilmu-table tr.inline-form-row select.form-select {
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
      const btnTambah = document.getElementById('btnTambahInlineRbi');
      const editForm = document.getElementById('inlineEditRbiForm');
      const parentOptions = @json($parentOptions);
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldKode = @json(old('kode', ''));
      const oldNama = @json(old('nama', ''));
      const oldParentId = @json((string) old('parent_id', ''));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function escapeHtml(str) {
        return String(str ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function buildParentOptionsHtml(selectedId = '', excludeId = '') {
        let html = '<option value="">-- Pilih Parent Bidang --</option>';
        parentOptions.forEach(function (opt) {
          if (excludeId && String(opt.id) === String(excludeId)) return;
          const indent = '\u00A0\u00A0\u00A0'.repeat(Math.min(opt.level || 0, 3));
          const selected = String(opt.id) === String(selectedId) ? ' selected' : '';
          html += `<option value="${opt.id}"${selected}>${indent}[${escapeHtml(opt.kode)}] ${escapeHtml(opt.nama)}</option>`;
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
        const existing = document.getElementById('inlineCreateRbiRow');
        if (existing) existing.remove();
      }

      function openCreateRow(kodeVal = '', namaVal = '', parentIdVal = '') {
        cancelActiveEdit();
        removeCreateRow();

        const tbody = document.querySelector('#rumpun-bidang-ilmu-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateRbiRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="kode" form="inlineCreateRbiForm" class="form-control form-control-sm text-uppercase" placeholder="Contoh: 466" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateRbiForm" class="form-control form-control-sm" placeholder="Nama Rumpun / Bidang Ilmu" maxlength="180" required>
          </td>
          <td>
            <select name="parent_id" form="inlineCreateRbiForm" class="form-select form-select-sm">
              ${buildParentOptionsHtml(parentIdVal)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateRbiForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 30px; height: 30px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateRbi" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 30px; height: 30px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const parentSelect = tr.querySelector('select[name="parent_id"]');
        if (kodeInput) kodeInput.value = kodeVal;
        if (namaInput) namaInput.value = namaVal;
        if (parentSelect) parentSelect.value = parentIdVal;
        kodeInput?.focus();

        tr.querySelector('#btnCancelInlineCreateRbi')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '', '');
        });
      }

      document.addEventListener('click', function (event) {
        const addChildBtn = event.target.closest('.btn-inline-add-child-rbi');
        if (addChildBtn) {
          openCreateRow('', '', addChildBtn.dataset.parentId || '');
          window.scrollTo({ top: 0, behavior: 'smooth' });
          return;
        }

        const editBtn = event.target.closest('.btn-inline-edit-rbi');
        if (!editBtn) return;

        removeCreateRow();
        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');

        const rowNo = tr.cells[0]?.textContent?.trim() || '';
        const id = editBtn.dataset.id || '';
        const kode = editBtn.dataset.kode || '';
        const nama = editBtn.dataset.nama || '';
        const parentId = editBtn.dataset.parentId || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="kode" form="inlineEditRbiForm" class="form-control form-control-sm text-uppercase" maxlength="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditRbiForm" class="form-control form-control-sm" maxlength="180" required>
          </td>
          <td>
            <select name="parent_id" form="inlineEditRbiForm" class="form-select form-select-sm">
              ${buildParentOptionsHtml(parentId, id)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditRbiForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 30px; height: 30px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 30px; height: 30px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const kodeInput = tr.querySelector('input[name="kode"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        const parentSelect = tr.querySelector('select[name="parent_id"]');
        if (kodeInput) kodeInput.value = kode;
        if (namaInput) namaInput.value = nama;
        if (parentSelect) parentSelect.value = parentId;
        kodeInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#rumpun-bidang-ilmu-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldKode : '',
            hasErrors ? oldNama : '',
            hasErrors ? oldParentId : ''
          );
        });
      }
    });
  </script>
@endpush
