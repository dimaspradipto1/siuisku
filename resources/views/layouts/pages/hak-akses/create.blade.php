@extends('layouts.dashboard.template')

@section('content')
  @php
    $actionCols = [
      'view' => 'Lihat',
      'create' => 'Tambah',
      'update' => 'Ubah',
      'delete' => 'Hapus',
      'approve' => 'Validasi',
    ];
    $oldPerms = old('permissions', []);
  @endphp

  <main class="main">
    <div class="main-content">
      <div class="page-header">
        <div>
          <h1 class="page-title">Tambah Role &amp; Hak Akses</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <a href="{{ route('hak-akses.index') }}" class="breadcrumb-item">Hak Akses</a>
            <span class="breadcrumb-item active">Tambah Role</span>
          </nav>
        </div>
        <div class="page-header-actions">
          <a href="{{ route('hak-akses.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <form action="{{ route('hak-akses.store') }}" method="POST">
        @csrf
        <div class="card mb-3">
          <div class="card-header">
            <h5 class="card-title mb-0">Informasi Role</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label for="kode" class="form-label">Kode Role <span class="text-danger">*</span></label>
                <input type="text" id="kode" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="contoh: operator_fakultas" required>
                @error('kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label for="nama" class="form-label">Nama Role <span class="text-danger">*</span></label>
                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="contoh: Operator Fakultas" required>
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label for="scope" class="form-label">Cakupan Data OTK (Scope) <span class="text-danger">*</span></label>
                <select id="scope" name="scope" class="form-select @error('scope') is-invalid @enderror" required>
                  <option value="universitas" {{ old('scope') === 'universitas' ? 'selected' : '' }}>Seluruh Universitas</option>
                  <option value="unit" {{ old('scope', 'unit') === 'unit' ? 'selected' : '' }}>Unit Kerja &amp; Sub-Unit (OTK)</option>
                  <option value="self" {{ old('scope') === 'self' ? 'selected' : '' }}>Data Pribadi (Self)</option>
                </select>
                @error('scope')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-12">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <input type="text" id="deskripsi" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}" placeholder="Keterangan singkat kewenangan role ini">
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Matrix Permission Menu (SIMPEG)</h5>
            <div class="form-check mb-0">
              <input class="form-check-input" type="checkbox" id="checkAllPerms">
              <label class="form-check-label small" for="checkAllPerms">Pilih Semua</label>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="font-size: 12px;">
                <thead class="table-light">
                  <tr>
                    <th class="ps-3">Menu / Fitur</th>
                    @foreach ($actionCols as $colLabel)
                      <th class="text-center" style="width: 110px;">{{ $colLabel }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach ($permissionsByMenu as $menuName => $perms)
                    @php
                      $byAction = $perms->keyBy('aksi');
                    @endphp
                    <tr>
                      <td class="ps-3 fw-semibold">{{ $menuName }}</td>
                      @foreach ($actionCols as $aksiKey => $colLabel)
                        <td class="text-center">
                          @if (isset($byAction[$aksiKey]))
                            <input
                              class="form-check-input perm-checkbox"
                              type="checkbox"
                              name="permissions[]"
                              value="{{ $byAction[$aksiKey]->id }}"
                              {{ in_array($byAction[$aksiKey]->id, $oldPerms) ? 'checked' : '' }}
                            >
                          @else
                            <span class="text-muted">-</span>
                          @endif
                        </td>
                      @endforeach
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('hak-akses.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i> Simpan Role
            </button>
          </div>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const checkAll = document.getElementById('checkAllPerms');
    if (checkAll) {
      checkAll.addEventListener('change', function () {
        document.querySelectorAll('.perm-checkbox').forEach(function (cb) {
          cb.checked = checkAll.checked;
        });
      });
    }
  </script>
@endpush
