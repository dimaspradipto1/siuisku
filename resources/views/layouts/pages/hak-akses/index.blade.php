@extends('layouts.dashboard.template')

@section('content')
  @php
    $activeTab = request('tab', 'roles');
  @endphp

  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Hak Akses &amp; Role (OTK)</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Pengaturan</span>
            <span class="breadcrumb-item active">Hak Akses</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPlottingRole">
            <i class="bi bi-person-plus me-1"></i> Plotting Role User
          </button>
          <a href="{{ route('hak-akses.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Role
          </a>
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

      <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="nav-link {{ $activeTab !== 'plotting' ? 'active' : '' }}"
            id="tab-roles"
            data-bs-toggle="tab"
            data-bs-target="#pane-roles"
            type="button"
            role="tab"
          >
            <i class="bi bi-shield-lock me-1"></i> Master Role &amp; Matrix Permission
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link {{ $activeTab === 'plotting' ? 'active' : '' }}"
            id="tab-plotting"
            data-bs-toggle="tab"
            data-bs-target="#pane-plotting"
            type="button"
            role="tab"
          >
            <i class="bi bi-diagram-3 me-1"></i> Plotting Role User (OTK)
            <span class="badge bg-primary-subtle text-primary ms-1">{{ $userRoles->count() }}</span>
          </button>
        </li>
      </ul>

      <div class="tab-content">
        <!-- Tab 1: Master Role -->
        <div class="tab-pane fade {{ $activeTab !== 'plotting' ? 'show active' : '' }}" id="pane-roles" role="tabpanel">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="card-title mb-0">Daftar Role Hak Akses</h5>
              <span class="badge bg-primary-subtle text-primary">{{ $roles->count() }} Role</span>
            </div>
            <div class="card-body">
              <div class="table-responsive hak-akses-table-wrap">
                {!! $dataTable->table() !!}
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 2: Plotting Role User (OTK) -->
        <div class="tab-pane fade {{ $activeTab === 'plotting' ? 'show active' : '' }}" id="pane-plotting" role="tabpanel">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="card-title mb-0">Daftar Plotting Hak Akses Pengguna</h5>
              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPlottingRole">
                <i class="bi bi-plus-lg me-1"></i> Tambah Plotting
              </button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 11px;">
                  <thead class="table-light">
                    <tr>
                      <th class="ps-3" style="width: 50px;">No</th>
                      <th>Pengguna</th>
                      <th>Modul</th>
                      <th>Role Hak Akses</th>
                      <th>Unit Kerja (OTK)</th>
                      <th>Jabatan Struktural (OTK)</th>
                      <th class="text-center pe-3" style="width: 90px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($userRoles as $idx => $plot)
                      <tr>
                        <td class="ps-3">{{ $idx + 1 }}</td>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <img src="{{ $plot->user?->avatar_url }}" alt="" class="rounded-circle border" style="width: 26px; height: 26px; object-fit: cover;">
                            <div>
                              <div class="fw-semibold">{{ $plot->user?->name ?? '-' }}</div>
                              <small class="text-muted">{{ $plot->user?->username }}</small>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-light text-dark border">{{ $plot->modul?->singkatan ?: $plot->modul?->nama }}</span>
                        </td>
                        <td>
                          <span class="badge bg-primary-subtle text-primary">{{ $plot->role?->nama ?? '-' }}</span>
                        </td>
                        <td>{{ $plot->unitKerja?->nama ?? 'Universitas Ibnu Sina' }}</td>
                        <td>
                          @if ($plot->jenisJabatanStruktural)
                            <span class="fw-semibold">{{ $plot->jenisJabatanStruktural->kode }}</span> - {{ $plot->jenisJabatanStruktural->nama }}
                          @else
                            <span class="text-muted">-</span>
                          @endif
                        </td>
                        <td class="text-center pe-3">
                          <form action="{{ route('hak-akses.plotting.destroy', $plot) }}" method="POST" onsubmit="return confirm('Hapus plotting role ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="width: 30px; height: 30px;" title="Hapus Plotting">
                              <i class="fa-solid fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada plotting hak akses pengguna.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal Plotting Role User -->
  <div class="modal fade" id="modalPlottingRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('hak-akses.plotting.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Plotting Role User (OTK)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Pengguna <span class="text-danger">*</span></label>
                <select name="user_id" class="form-select select2" data-placeholder="Pilih pengguna..." required>
                  <option value="">Pilih pengguna...</option>
                  @foreach ($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->username }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Modul <span class="text-danger">*</span></label>
                <select name="modul_id" class="form-select" required>
                  @foreach ($modules as $mod)
                    <option value="{{ $mod->id }}" {{ $mod->kode === 'simpeg' ? 'selected' : '' }}>
                      {{ $mod->nama }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Role Hak Akses <span class="text-danger">*</span></label>
                <select name="role_id" class="form-select select2" data-placeholder="Pilih role..." required>
                  <option value="">Pilih role...</option>
                  @foreach ($roles as $r)
                    <option value="{{ $r->id }}">{{ $r->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Unit Kerja (OTK)</label>
                <select name="unit_kerja_id" class="form-select select2" data-placeholder="Pilih unit kerja...">
                  <option value="">Universitas Ibnu Sina (Default)</option>
                  @foreach ($unitKerjas as $unit)
                    <option value="{{ $unit->id }}">[{{ $unit->kode }}] {{ $unit->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Jenis Jabatan Struktural (OTK)</label>
                <select name="jenis_jabatan_struktural_id" class="form-select select2" data-placeholder="-- Opsional --">
                  <option value="">-- Opsional --</option>
                  @foreach ($jabatans as $jab)
                    <option value="{{ $jab->id }}">[{{ $jab->kode }}] {{ $jab->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Plotting</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('style')
  <style>
    .hak-akses-table-wrap,
    .hak-akses-table-wrap .dt-container,
    #roles-table,
    #roles-table th,
    #roles-table td,
    #roles-table code,
    #roles-table .badge {
      font-size: 11px !important;
    }

    #roles-table th,
    #roles-table td {
      padding: 0.45rem 0.6rem;
      white-space: nowrap;
    }
  </style>
@endpush

@push('scripts')
  @if (app()->environment('production'))
    {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
  @else
    {!! $dataTable->scripts() !!}
  @endif
@endpush
