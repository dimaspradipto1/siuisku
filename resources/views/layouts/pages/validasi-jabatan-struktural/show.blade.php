@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Validasi Riwayat Jabatan Struktural</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kepegawaian</span>
            <a href="{{ route('validasi-jabatan-struktural.index') }}" class="breadcrumb-item">Jabatan Struktural</a>
            <span class="breadcrumb-item active">Detail</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('validasi-jabatan-struktural.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="row g-3">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="card-title mb-0">Informasi Riwayat Jabatan Struktural</h5>
              <span class="badge {{ $riwayatJabatanStruktural->statusBadgeClass() }}">{{ $riwayatJabatanStruktural->statusLabel() }}</span>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                <tr>
                  <th style="width: 210px;" class="text-muted">NIP Pegawai</th>
                  <td>{{ $riwayatJabatanStruktural->pegawai?->nip ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Pegawai</th>
                  <td class="fw-semibold">{{ $riwayatJabatanStruktural->pegawai?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Unit Kerja Pegawai</th>
                  <td>{{ $riwayatJabatanStruktural->pegawai?->unitKerja ? "{$riwayatJabatanStruktural->pegawai->unitKerja->kode} - {$riwayatJabatanStruktural->pegawai->unitKerja->nama}" : '-' }}</td>
                </tr>
                <tr>
                  <td colspan="2"><hr class="my-2"></td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Jabatan Struktural</th>
                  <td class="fw-semibold">{{ $riwayatJabatanStruktural->jabatanStruktural?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Mulai</th>
                  <td>{{ $riwayatJabatanStruktural->tanggal_mulai ? $riwayatJabatanStruktural->tanggal_mulai->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Selesai</th>
                  <td>{{ $riwayatJabatanStruktural->tanggal_selesai ? $riwayatJabatanStruktural->tanggal_selesai->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nomor SK</th>
                  <td>{{ $riwayatJabatanStruktural->nomor_sk ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal SK</th>
                  <td>{{ $riwayatJabatanStruktural->tanggal_sk ? $riwayatJabatanStruktural->tanggal_sk->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">File Jabatan</th>
                  <td>{{ $riwayatJabatanStruktural->file_jabatan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Keterangan</th>
                  <td>{{ $riwayatJabatanStruktural->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Diajukan</th>
                  <td>{{ $riwayatJabatanStruktural->tanggal_diajukan ? $riwayatJabatanStruktural->tanggal_diajukan->format('d M Y') : '-' }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Aksi Validasi</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('validasi-jabatan-struktural.bulk') }}" method="POST">
                @csrf
                <input type="hidden" name="ids[]" value="{{ $riwayatJabatanStruktural->id }}">

                <div class="mb-3">
                  <label for="catatan_validasi" class="form-label form-label-sm fw-semibold">Catatan Validasi (Opsional)</label>
                  <textarea id="catatan_validasi" name="catatan_validasi" rows="3" class="form-control form-control-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_validasi', $riwayatJabatanStruktural->catatan_validasi) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                  <button type="submit" name="action" value="disetujui" class="btn btn-sm btn-success flex-fill">
                    <i class="bi bi-check-lg me-1"></i> Setujui
                  </button>
                  <button type="submit" name="action" value="ditolak" class="btn btn-sm btn-danger flex-fill">
                    <i class="bi bi-x-lg me-1"></i> Tolak
                  </button>
                </div>
              </form>

              @if ($riwayatJabatanStruktural->validator)
                <hr>
                <div style="font-size: 11px;" class="text-muted">
                  <div>Terakhir divalidasi oleh: <strong>{{ $riwayatJabatanStruktural->validator->name }}</strong></div>
                  <div>Waktu: {{ $riwayatJabatanStruktural->validated_at?->format('d M Y H:i') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
