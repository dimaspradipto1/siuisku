@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Pengaturan Modul</h1>
          <p class="fx-subtitle">Kelola daftar modul yang tampil pada halaman portal modul setelah login.</p>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('modul') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Portal Modul
          </a>
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahModul">
            <i class="bi bi-plus-lg me-1"></i>Tambah Modul
          </button>
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
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

      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Daftar Modul Portal</h5>
          <span class="badge bg-primary-subtle text-primary">{{ $modules->count() }} Modul</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3" style="width: 70px;">Urutan</th>
                  <th>Modul</th>
                  <th>Kode</th>
                  <th>URL Tujuan</th>
                  <th style="width: 120px;">Akses User</th>
                  <th style="width: 140px;">Status</th>
                  <th class="text-end pe-3" style="width: 210px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($modules as $modul)
                  <tr>
                    <td class="ps-3">
                      <span class="badge bg-secondary-subtle text-secondary">{{ $modul->urutan }}</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <span
                          class="d-inline-flex align-items-center justify-content-center rounded-3"
                          style="width: 40px; height: 40px; background: {{ $modul->warna_bg }}; color: {{ $modul->warna }}; font-size: 19px; flex-shrink: 0;"
                        >
                          <i class="bi {{ $modul->icon }}"></i>
                        </span>
                        <div>
                          <div class="fw-semibold">{{ $modul->nama }}</div>
                          @if ($modul->singkatan)
                            <small class="text-muted">{{ $modul->singkatan }}</small>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td><code>{{ $modul->kode }}</code></td>
                    <td>
                      @if ($modul->url)
                        <span class="text-muted small">{{ $modul->url }}</span>
                      @else
                        <span class="text-muted small fst-italic">Default (/dashboard)</span>
                      @endif
                    </td>
                    <td>
                      <span class="badge bg-info-subtle text-info">
                        <i class="bi bi-people me-1"></i>{{ $modul->users_count }} User
                      </span>
                    </td>
                    <td>
                      <form action="{{ route('pengaturan.modul.toggle', $modul) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button
                          type="submit"
                          class="btn btn-sm {{ $modul->is_active ? 'btn-success-subtle text-success border-success-subtle' : 'btn-secondary-subtle text-secondary border-secondary-subtle' }}"
                          title="Klik untuk ubah status"
                        >
                          <i class="bi {{ $modul->is_active ? 'bi-check-circle-fill' : 'bi-dash-circle' }} me-1"></i>
                          {{ $modul->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                      </form>
                    </td>
                    <td class="text-end pe-3">
                      <button
                        type="button"
                        class="btn btn-outline-info btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalAturUserModul"
                        data-nama="{{ $modul->nama }}"
                        data-action="{{ route('pengaturan.modul.users.sync', $modul) }}"
                        data-user-ids='@json($modul->users->pluck("id")->values())'
                        title="Atur Pengguna Modul"
                      >
                        <i class="bi bi-people"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditModul"
                        data-id="{{ $modul->id }}"
                        data-action="{{ route('pengaturan.modul.update', $modul) }}"
                        data-kode="{{ $modul->kode }}"
                        data-nama="{{ $modul->nama }}"
                        data-singkatan="{{ $modul->singkatan }}"
                        data-icon="{{ $modul->icon }}"
                        data-warna="{{ $modul->warna }}"
                        data-warna-bg="{{ $modul->warna_bg }}"
                        data-url="{{ $modul->url }}"
                        data-urutan="{{ $modul->urutan }}"
                        data-is-active="{{ $modul->is_active ? '1' : '0' }}"
                      >
                        <i class="bi bi-pencil-square"></i>
                      </button>

                      <form
                        action="{{ route('pengaturan.modul.destroy', $modul) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus modul {{ $modul->nama }}?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      Belum ada data modul. Klik <strong>Tambah Modul</strong> untuk menambahkan.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal Tambah Modul -->
  <div class="modal fade" id="modalTambahModul" tabindex="-1" aria-labelledby="modalTambahModulLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('pengaturan.modul.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahModulLabel">Tambah Modul</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="add_kode" class="form-label">Kode Modul</label>
                <input type="text" class="form-control" id="add_kode" name="kode" value="{{ old('kode') }}" placeholder="contoh: simpeg" required>
              </div>
              <div class="col-md-6">
                <label for="add_singkatan" class="form-label">Singkatan</label>
                <input type="text" class="form-control" id="add_singkatan" name="singkatan" value="{{ old('singkatan') }}" placeholder="contoh: SIMPEG">
              </div>
              <div class="col-12">
                <label for="add_nama" class="form-label">Nama Modul</label>
                <input type="text" class="form-control" id="add_nama" name="nama" value="{{ old('nama') }}" placeholder="contoh: SIM Kepegawaian" required>
              </div>
              <div class="col-md-8">
                <label for="add_icon" class="form-label">Icon (Bootstrap Icons)</label>
                <select class="form-select" id="add_icon" name="icon" required>
                  @foreach ($iconPresets as $iconClass => $iconLabel)
                    <option value="{{ $iconClass }}" {{ old('icon') === $iconClass ? 'selected' : '' }}>
                      {{ $iconLabel }} ({{ $iconClass }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="add_urutan" class="form-label">Urutan</label>
                <input type="number" class="form-control" id="add_urutan" name="urutan" value="{{ old('urutan', $modules->count() + 1) }}" min="0" required>
              </div>
              <div class="col-md-6">
                <label for="add_warna" class="form-label">Warna Icon</label>
                <input type="color" class="form-control form-control-color w-100" id="add_warna" name="warna" value="{{ old('warna', '#1e4b94') }}" required>
              </div>
              <div class="col-md-6">
                <label for="add_warna_bg" class="form-label">Warna Latar Icon</label>
                <input type="color" class="form-control form-control-color w-100" id="add_warna_bg" name="warna_bg" value="{{ old('warna_bg', '#eff6ff') }}" required>
              </div>
              <div class="col-12">
                <label for="add_url" class="form-label">URL Tujuan</label>
                <input type="text" class="form-control" id="add_url" name="url" value="{{ old('url', '/dashboard') }}" placeholder="/dashboard atau https://...">
              </div>
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="add_is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                  <label class="form-check-label" for="add_is_active">Aktifkan modul di portal</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Modul</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Modul -->
  <div class="modal fade" id="modalEditModul" tabindex="-1" aria-labelledby="modalEditModulLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="formEditModul" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditModulLabel">Edit Modul</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="edit_kode" class="form-label">Kode Modul</label>
                <input type="text" class="form-control" id="edit_kode" name="kode" required>
              </div>
              <div class="col-md-6">
                <label for="edit_singkatan" class="form-label">Singkatan</label>
                <input type="text" class="form-control" id="edit_singkatan" name="singkatan">
              </div>
              <div class="col-12">
                <label for="edit_nama" class="form-label">Nama Modul</label>
                <input type="text" class="form-control" id="edit_nama" name="nama" required>
              </div>
              <div class="col-md-8">
                <label for="edit_icon" class="form-label">Icon (Bootstrap Icons)</label>
                <select class="form-select" id="edit_icon" name="icon" required>
                  @foreach ($iconPresets as $iconClass => $iconLabel)
                    <option value="{{ $iconClass }}">{{ $iconLabel }} ({{ $iconClass }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="edit_urutan" class="form-label">Urutan</label>
                <input type="number" class="form-control" id="edit_urutan" name="urutan" min="0" required>
              </div>
              <div class="col-md-6">
                <label for="edit_warna" class="form-label">Warna Icon</label>
                <input type="color" class="form-control form-control-color w-100" id="edit_warna" name="warna" required>
              </div>
              <div class="col-md-6">
                <label for="edit_warna_bg" class="form-label">Warna Latar Icon</label>
                <input type="color" class="form-control form-control-color w-100" id="edit_warna_bg" name="warna_bg" required>
              </div>
              <div class="col-12">
                <label for="edit_url" class="form-label">URL Tujuan</label>
                <input type="text" class="form-control" id="edit_url" name="url" placeholder="/dashboard atau https://...">
              </div>
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active" name="is_active" value="1">
                  <label class="form-check-label" for="edit_is_active">Aktifkan modul di portal</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal Atur Pengguna Modul -->
  <div class="modal fade" id="modalAturUserModul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <form id="formAturUserModul" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title">Atur Akses Pengguna: <span id="aturUserModulNama"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <small class="text-muted">Super Admin otomatis memiliki akses ke semua modul aktif.</small>
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="checkAllModulUsers">
                <label class="form-check-label small" for="checkAllModulUsers">Pilih Semua</label>
              </div>
            </div>
            <div class="list-group">
              @foreach ($allUsers as $u)
                <label class="list-group-item d-flex align-items-center justify-content-between gap-2" style="cursor: pointer;">
                  <span class="d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0 modul-user-checkbox" type="checkbox" name="user_ids[]" value="{{ $u->id }}">
                    <span>
                      <span class="fw-semibold d-block small">{{ $u->name }}</span>
                      <small class="text-muted">{{ $u->username }}</small>
                    </span>
                  </span>
                  <span class="badge bg-light text-dark border">{{ ucwords((string) $u->roles) }}</span>
                </label>
              @endforeach
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Akses</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
    const modalEditModul = document.getElementById('modalEditModul');
    if (modalEditModul) {
      modalEditModul.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        document.getElementById('formEditModul').setAttribute('action', button.dataset.action);
        document.getElementById('edit_kode').value = button.dataset.kode || '';
        document.getElementById('edit_nama').value = button.dataset.nama || '';
        document.getElementById('edit_singkatan').value = button.dataset.singkatan || '';
        document.getElementById('edit_icon').value = button.dataset.icon || 'bi-grid-fill';
        document.getElementById('edit_warna').value = button.dataset.warna || '#1e4b94';
        document.getElementById('edit_warna_bg').value = button.dataset.warnaBg || '#eff6ff';
        document.getElementById('edit_url').value = button.dataset.url || '';
        document.getElementById('edit_urutan').value = button.dataset.urutan || '0';
        document.getElementById('edit_is_active').checked = button.dataset.isActive === '1';
      });
    }

    const modalAturUserModul = document.getElementById('modalAturUserModul');
    if (modalAturUserModul) {
      const checkAllUsers = document.getElementById('checkAllModulUsers');

      modalAturUserModul.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        document.getElementById('formAturUserModul').setAttribute('action', button.dataset.action);
        document.getElementById('aturUserModulNama').textContent = button.dataset.nama || '';

        let selectedIds = [];
        try {
          selectedIds = JSON.parse(button.dataset.userIds || '[]').map(String);
        } catch (e) {
          selectedIds = [];
        }

        const checkboxes = modalAturUserModul.querySelectorAll('.modul-user-checkbox');
        let allChecked = checkboxes.length > 0;
        checkboxes.forEach(function (cb) {
          cb.checked = selectedIds.includes(String(cb.value));
          if (!cb.checked) allChecked = false;
        });

        if (checkAllUsers) checkAllUsers.checked = allChecked;
      });

      if (checkAllUsers) {
        checkAllUsers.addEventListener('change', function () {
          modalAturUserModul.querySelectorAll('.modul-user-checkbox').forEach(function (cb) {
            cb.checked = checkAllUsers.checked;
          });
        });
      }
    }
  </script>
@endpush
