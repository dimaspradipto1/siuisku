@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Setting Validasi &mdash; {{ $settingValidasi->nama }}</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <a href="{{ route('setting-validasi.index') }}" class="breadcrumb-item">Setting Validasi</a>
            <span class="breadcrumb-item active">{{ $settingValidasi->kode }}</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('setting-validasi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <button type="button" id="btnTambahStepValidasi" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Tahap
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

      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="row g-3 align-items-center" style="font-size: 12px;">
            <div class="col-md-3">
              <div class="text-muted">Kode Jenis Validasi</div>
              <div class="fw-semibold">{{ $settingValidasi->kode }}</div>
            </div>
            <div class="col-md-6">
              <div class="text-muted">Nama Jenis Validasi</div>
              <div class="fw-semibold">{{ $settingValidasi->nama }}</div>
            </div>
            <div class="col-md-3">
              <div class="text-muted">Jumlah Tahap Validasi</div>
              <div><span class="badge bg-primary-subtle text-primary">{{ $settingValidasi->steps->count() }} Tahap</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden forms for inline Create & Edit -->
      <form id="inlineCreateStepForm" action="{{ route('setting-validasi.steps.store', $settingValidasi) }}" method="POST" class="d-none">
        @csrf
      </form>
      <form id="inlineEditStepForm" method="POST" class="d-none">
        @csrf
        @method('PUT')
      </form>

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Alur / Tahap Validasi</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $settingValidasi->steps->count() }} Data</span>
        </div>
        <div class="card-body">
          <div class="table-responsive step-validasi-table-wrap">
            <table id="step-validasi-table" class="table table-hover align-middle w-100 mb-0">
              <thead>
                <tr>
                  <th class="text-center" style="width: 90px;">Urutan</th>
                  <th>Nama Tahap Validasi</th>
                  <th style="width: 280px;">Role Validator</th>
                  <th class="text-center text-nowrap" style="width: 105px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($settingValidasi->steps as $step)
                  <tr>
                    <td class="text-center fw-semibold">{{ $step->urutan }}</td>
                    <td>{{ $step->nama }}</td>
                    <td>
                      @if ($step->role)
                        <span class="badge bg-info-subtle text-info">{{ $step->role->nama }}</span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td class="text-center text-nowrap">
                      <div class="d-inline-flex align-items-center justify-content-center gap-1">
                        <button type="button"
                          class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-inline-edit-step"
                          style="width: 32px; height: 32px;"
                          title="Edit"
                          data-urutan="{{ $step->urutan }}"
                          data-nama="{{ $step->nama }}"
                          data-role-id="{{ $step->role_id }}"
                          data-action="{{ route('setting-validasi.steps.update', [$settingValidasi, $step]) }}">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="{{ route('setting-validasi.steps.destroy', [$settingValidasi, $step]) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Yakin ingin menghapus tahap validasi ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0"
                            style="width: 32px; height: 32px;"
                            title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr id="emptyStepRow">
                    <td colspan="4" class="text-center text-muted py-4">Belum ada tahap validasi yang diatur.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('style')
  <style>
    .step-validasi-table-wrap,
    #step-validasi-table,
    #step-validasi-table th,
    #step-validasi-table td {
      font-size: 11px !important;
    }

    #step-validasi-table th,
    #step-validasi-table td {
      padding: 0.45rem 0.6rem;
    }

    #step-validasi-table tr.inline-form-row {
      background-color: #f8fafc;
    }

    #step-validasi-table tr.inline-form-row input.form-control,
    #step-validasi-table tr.inline-form-row select.form-select {
      font-size: 12px;
      height: 34px;
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btnTambah = document.getElementById('btnTambahStepValidasi');
      const editForm = document.getElementById('inlineEditStepForm');
      const roles = @json($roles->map(fn ($r) => ['id' => $r->id, 'nama' => $r->nama])->values());
      const nextUrutan = @json($nextUrutan);
      const hasErrors = @json($errors->any());
      const oldUrutan = @json(old('urutan', $nextUrutan));
      const oldNama = @json(old('nama', ''));
      const oldRoleId = @json(old('role_id', ''));

      let activeEditRow = null;
      let activeEditOriginalHtml = '';

      function buildRoleOptions(selectedId = '') {
        let html = '<option value="">-- Pilih Role Validator --</option>';
        roles.forEach(function (role) {
          const selected = String(role.id) === String(selectedId) ? 'selected' : '';
          html += `<option value="${role.id}" ${selected}>${role.nama}</option>`;
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
        const existing = document.getElementById('inlineCreateStepRow');
        if (existing) existing.remove();
      }

      function openCreateRow(urutanVal = nextUrutan, namaVal = '', roleVal = '') {
        cancelActiveEdit();
        const existing = document.getElementById('inlineCreateStepRow');
        if (existing) {
          existing.querySelector('input[name="nama"]')?.focus();
          return;
        }

        const tbody = document.querySelector('#step-validasi-table tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.id = 'inlineCreateStepRow';
        tr.className = 'inline-form-row';
        tr.innerHTML = `
          <td>
            <input type="number" name="urutan" form="inlineCreateStepForm" class="form-control form-control-sm text-center" min="1" max="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineCreateStepForm" class="form-control form-control-sm" placeholder="Contoh: Validasi Wakil Rektor II" maxlength="120" required>
          </td>
          <td>
            <select name="role_id" form="inlineCreateStepForm" class="form-select form-select-sm" required>
              ${buildRoleOptions(roleVal)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineCreateStepForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" id="btnCancelInlineCreateStep" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        tbody.prepend(tr);

        const urutanInput = tr.querySelector('input[name="urutan"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        if (urutanInput) urutanInput.value = urutanVal;
        if (namaInput) namaInput.value = namaVal;
        namaInput?.focus();

        tr.querySelector('#btnCancelInlineCreateStep')?.addEventListener('click', removeCreateRow);
      }

      if (btnTambah) {
        btnTambah.addEventListener('click', function () {
          openCreateRow(nextUrutan, '', '');
        });
      }

      document.addEventListener('click', function (event) {
        const editBtn = event.target.closest('.btn-inline-edit-step');
        if (!editBtn) return;

        removeCreateRow();
        cancelActiveEdit();

        const tr = editBtn.closest('tr');
        if (!tr || !editForm) return;

        activeEditRow = tr;
        activeEditOriginalHtml = tr.innerHTML;
        editForm.setAttribute('action', editBtn.dataset.action || '');

        const urutan = editBtn.dataset.urutan || '1';
        const nama = editBtn.dataset.nama || '';
        const roleId = editBtn.dataset.roleId || '';

        tr.classList.add('inline-form-row');
        tr.innerHTML = `
          <td>
            <input type="number" name="urutan" form="inlineEditStepForm" class="form-control form-control-sm text-center" min="1" max="20" required>
          </td>
          <td>
            <input type="text" name="nama" form="inlineEditStepForm" class="form-control form-control-sm" maxlength="120" required>
          </td>
          <td>
            <select name="role_id" form="inlineEditStepForm" class="form-select form-select-sm" required>
              ${buildRoleOptions(roleId)}
            </select>
          </td>
          <td class="text-center text-nowrap">
            <div class="d-inline-flex align-items-center justify-content-center gap-1">
              <button type="submit" form="inlineEditStepForm" class="btn btn-sm btn-success text-white d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Simpan Perubahan">
                <i class="fa-solid fa-floppy-disk"></i>
              </button>
              <button type="button" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0 btn-cancel-inline-edit" style="width: 32px; height: 32px;" title="Batal">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>
          </td>
        `;

        const urutanInput = tr.querySelector('input[name="urutan"]');
        const namaInput = tr.querySelector('input[name="nama"]');
        if (urutanInput) urutanInput.value = urutan;
        if (namaInput) namaInput.value = nama;
        namaInput?.focus();

        tr.querySelector('.btn-cancel-inline-edit')?.addEventListener('click', cancelActiveEdit);
      });

      if (hasErrors) {
        openCreateRow(oldUrutan, oldNama, oldRoleId);
      }
    });
  </script>
@endpush
