@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Gelar Akademik</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Referensi Kepegawaian</span>
            <span class="breadcrumb-item active">Gelar Akademik</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" id="btnTambahInlineGelar" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Gelar Akademik
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
      <form id="inlineCreateGelarForm" action="{{ route('gelar-akademik.store') }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditGelarForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Gelar Akademik</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $total }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive gelar-akademik-table-wrap">
            {!! $dataTable->table() !!}
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .gelar-akademik-table-wrap,
    .gelar-akademik-table-wrap .dt-container,
    #gelar-akademik-table,
    #gelar-akademik-table th,
    #gelar-akademik-table td {
      font-size: 11px !important;
    }

    #gelar-akademik-table th,
    #gelar-akademik-table td {
      padding: 0.45rem 0.6rem;
    }

    #gelar-akademik-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #gelar-akademik-table tr.inline-form-row input.form-control {
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
      const btnTambah = document.getElementById('btnTambahInlineGelar');
      const editForm = document.getElementById('inlineEditGelarForm');
      const hasErrors = @json($errors->any());
      const openCreateOnLoad = @json(request()->boolean('create') || $errors->any());
      const oldGelar = @json(old('gelar', ''));
      const oldNama = @json(old('nama', ''));

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
        const existing = document.getElementById('inlineCreateGelarRow');
        if (existing) existing.remove();
      }

      function openCreateRow(gelarVal = '', namaVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateGelarRow');
        if (existing) {
          existing.querySelector('input[name="gelar"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#gelar-akademik-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateGelarRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td class="text-center text-muted"><i class="bi bi-plus-circle"></i></td>
          <td>
            <input type="text" name="gelar" form="inlineCreateGelarForm" class="form-control form-control-sm" placeholder="Contoh: M.Kom." maxlength="30" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateGelarForm" class="form-control form-control-sm" placeholder="Contoh: Magister Komputer" maxlength="150" required>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateGelarForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateGelar" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const gelarInput = tr.querySelector('input[name="gelar"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        if (gelarInput) gelarInput.value = gelarVal;
        if (namaInput) namaInput.value = namaVal;
        gelarInput?.focus();

        tr.querySelector('#btnCancelInlineCreateGelar')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow('', '');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-gelar');
        if (!editBtn) return;

        removeCreateRow();
        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');

        const rowNo = tr.cells[0]?.textContent?.trim() || '';
        const gelar = editBtn.dataset.gelar || '';
        const nama = editBtn.dataset.nama || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td class="text-center">${rowNo}</td>
          <td>
            <input type="text" name="gelar" form="inlineEditGelarForm" class="form-control form-control-sm" maxlength="30" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditGelarForm" class="form-control form-control-sm" maxlength="150" required>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditGelarForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const gelarInput = tr.querySelector('input[name="gelar"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        if (gelarInput) gelarInput.value = gelar;
        if (namaInput) namaInput.value = nama;
        gelarInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (openCreateOnLoad && typeof $ !== 'undefined') {
        $('#gelar-akademik-table').one('draw.dt', function () {
          openCreateRow(
            hasErrors ? oldGelar : '',
            hasErrors ? oldNama : ''
          );
        });
      }
    });
  </script>
@endpush
