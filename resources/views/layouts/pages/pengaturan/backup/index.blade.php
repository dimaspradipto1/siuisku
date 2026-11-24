@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header mb-4">
        <div>
          <h1 class="page-title" style="color: #166534;">Backup Database &amp; Storage</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Pengaturan Sistem</span>
            <span class="breadcrumb-item active">Backup Database &amp; Storage</span>
          </nav>
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
          <strong>Gagal memproses berkas:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="row g-4">
        <!-- Card 1: Ekspor & Impor Database -->
        <div class="col-xl-6">
          <div class="card h-100 border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex align-items-start gap-3 mb-4">
                <div
                  class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                  style="width: 48px; height: 48px; background: #2d6a36; color: #ffffff; font-size: 20px;"
                >
                  <i class="bi bi-database"></i>
                </div>
                <div>
                  <h5 class="fw-bold mb-1">Ekspor &amp; Impor Database</h5>
                  <p class="text-muted mb-0 small">Unduh atau pulihkan struktur tabel dan data database aplikasi sebagai berkas SQL.</p>
                </div>
              </div>

              <div class="mb-4 small">
                <div class="row py-2 align-items-center">
                  <div class="col-4 text-muted">Koneksi</div>
                  <div class="col-8">
                    <span class="badge bg-light text-dark border fw-semibold">{{ strtoupper($driver) }}</span>
                  </div>
                </div>
                <div class="row py-2 align-items-center">
                  <div class="col-4 text-muted">Database</div>
                  <div class="col-8"><code>{{ basename($databaseName) }}</code></div>
                </div>
                <div class="row py-2 align-items-center">
                  <div class="col-4 text-muted">Isi backup</div>
                  <div class="col-8">Struktur dan data semua tabel {{ strtoupper($driver) }} ({{ $tableCount }} tabel)</div>
                </div>
              </div>

              <div class="rounded-3 p-3 mb-4 small" style="background: #e0f2fe; color: #0369a1;">
                <i class="bi bi-shield-check me-1"></i>
                Hanya administrator yang dapat mengunduh dan memulihkan database. Simpan berkas SQL di lokasi aman karena berisi seluruh data aplikasi.
              </div>

              <div class="mt-auto d-flex flex-wrap gap-2">
                <a
                  href="{{ route('pengaturan.backup.database.export') }}"
                  class="btn btn-sm px-3 py-2 text-white"
                  style="background: #2d6a36; border-color: #2d6a36;"
                >
                  <i class="bi bi-download me-1"></i> Unduh Backup SQL
                </a>
                <button
                  type="button"
                  class="btn btn-sm px-3 py-2"
                  style="color: #2d6a36; border: 1px solid #2d6a36; background: #ffffff;"
                  data-bs-toggle="modal"
                  data-bs-target="#modalImportDatabase"
                >
                  <i class="bi bi-upload me-1"></i> Import / Pulihkan Database
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2: Backup & Impor Storage Public -->
        <div class="col-xl-6">
          <div class="card h-100 border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex align-items-start gap-3 mb-4">
                <div
                  class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                  style="width: 48px; height: 48px; background: #eef6ef; color: #2d6a36; font-size: 20px;"
                >
                  <i class="bi bi-folder-symlink"></i>
                </div>
                <div>
                  <h5 class="fw-bold mb-1">Backup &amp; Impor Storage Public</h5>
                  <p class="text-muted mb-0 small">Arsipkan atau pulihkan semua file pada folder <code>storage/app/public</code> ke dalam berkas ZIP.</p>
                </div>
              </div>

              <div class="mb-4 small">
                <div class="row py-2 align-items-center">
                  <div class="col-4 text-muted">Direktori</div>
                  <div class="col-8"><code>storage/app/public</code></div>
                </div>
                <div class="row py-2 align-items-center">
                  <div class="col-4 text-muted">Total Berkas</div>
                  <div class="col-8">{{ $storageFileCount }} berkas ({{ $storageSizeFormatted }})</div>
                </div>
                <div class="row py-2 align-items-center">
                  <div class="col-4 text-muted">Format Arsip</div>
                  <div class="col-8">ZIP Archive (<code>.zip</code>)</div>
                </div>
              </div>

              <div class="rounded-3 p-3 mb-4 small" style="background: #fef3c7; color: #92400e;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Backup storage memuat seluruh berkas lampiran, gambar, foto profil, berita, dan dokumen publik aplikasi.
              </div>

              <div class="mt-auto d-flex flex-wrap gap-2">
                <a
                  href="{{ route('pengaturan.backup.storage.export') }}"
                  class="btn btn-sm px-3 py-2 text-white"
                  style="background: #2d6a36; border-color: #2d6a36;"
                >
                  <i class="bi bi-download me-1"></i> Unduh Backup Storage ZIP
                </a>
                <button
                  type="button"
                  class="btn btn-sm px-3 py-2"
                  style="color: #2d6a36; border: 1px solid #2d6a36; background: #ffffff;"
                  data-bs-toggle="modal"
                  data-bs-target="#modalImportStorage"
                >
                  <i class="bi bi-upload me-1"></i> Import / Pulihkan Storage
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal Import Database -->
  <div class="modal fade" id="modalImportDatabase" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('pengaturan.backup.database.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Import / Pulihkan Database</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-warning small">
              <i class="bi bi-exclamation-triangle me-1"></i>
              <strong>Perhatian:</strong> Proses impor akan menimpa struktur dan data tabel yang ada di database saat ini. Pastikan Anda sudah mengunduh backup terlebih dahulu.
            </div>
            <div class="mb-0">
              <label for="sql_file" class="form-label">Pilih Berkas SQL (<code>.sql</code>)</label>
              <input type="file" class="form-control" id="sql_file" name="sql_file" accept=".sql,.txt" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Pulihkan Database</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Import Storage -->
  <div class="modal fade" id="modalImportStorage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('pengaturan.backup.storage.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Import / Pulihkan Storage Public</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-warning small">
              <i class="bi bi-exclamation-triangle me-1"></i>
              Berkas di dalam arsip ZIP akan diekstrak ke <code>storage/app/public</code> dan menimpa berkas dengan nama yang sama.
            </div>
            <div class="mb-0">
              <label for="storage_file" class="form-label">Pilih Berkas Arsip (<code>.zip</code>)</label>
              <input type="file" class="form-control" id="storage_file" name="storage_file" accept=".zip" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Pulihkan Storage</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
