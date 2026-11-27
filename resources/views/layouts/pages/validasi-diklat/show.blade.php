@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Validasi Riwayat Diklat</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kualifikasi</span>
            <a href="{{ route('validasi-diklat.index') }}" class="breadcrumb-item">Diklat</a>
            <span class="breadcrumb-item active">Detail</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('validasi-diklat.index') }}" class="btn btn-outline-secondary btn-sm">
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
              <h5 class="card-title mb-0">Informasi Riwayat Diklat Pegawai</h5>
              <span class="badge {{ $riwayatDiklat->statusBadgeClass() }}">{{ $riwayatDiklat->statusLabel() }}</span>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                <tr>
                  <th style="width: 210px;" class="text-muted">NIP Pegawai</th>
                  <td>{{ $riwayatDiklat->pegawai?->nip ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Pegawai</th>
                  <td class="fw-semibold">{{ $riwayatDiklat->pegawai?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Unit Kerja</th>
                  <td>{{ $riwayatDiklat->pegawai?->unitKerja ? "{$riwayatDiklat->pegawai->unitKerja->kode} - {$riwayatDiklat->pegawai->unitKerja->nama}" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jabatan Akademik</th>
                  <td>{{ $riwayatDiklat->pegawai?->klasifikasiSdm?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="2"><hr class="my-2"></td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Diklat</th>
                  <td class="fw-semibold">{{ $riwayatDiklat->nama_diklat }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jenis Diklat</th>
                  <td>{{ $riwayatDiklat->jenis_diklat }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Penyelenggara</th>
                  <td>{{ $riwayatDiklat->penyelenggara }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tahun</th>
                  <td>{{ $riwayatDiklat->tahun }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jumlah Jam (JP)</th>
                  <td>{{ $riwayatDiklat->jumlah_jam ? "{$riwayatDiklat->jumlah_jam} JP" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nomor Sertifikat</th>
                  <td>{{ $riwayatDiklat->nomor_sertifikat ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Sertifikat</th>
                  <td>{{ $riwayatDiklat->tanggal_sertifikat ? $riwayatDiklat->tanggal_sertifikat->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">File Sertifikat</th>
                  <td>{{ $riwayatDiklat->file_sertifikat ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Keterangan</th>
                  <td>{{ $riwayatDiklat->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Diajukan</th>
                  <td>{{ $riwayatDiklat->tanggal_diajukan ? $riwayatDiklat->tanggal_diajukan->format('d M Y') : '-' }}</td>
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
              <form action="{{ route('validasi-diklat.bulk') }}" method="POST">
                @csrf
                <input type="hidden" name="ids[]" value="{{ $riwayatDiklat->id }}">

                <div class="mb-3">
                  <label for="catatan_validasi" class="form-label form-label-sm fw-semibold">Catatan Validasi (Opsional)</label>
                  <textarea id="catatan_validasi" name="catatan_validasi" rows="3" class="form-control form-control-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_validasi', $riwayatDiklat->catatan_validasi) }}</textarea>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" name="action" value="disetujui" class="btn btn-sm btn-success">
                    <i class="bi bi-check-lg me-1"></i> Setujui
                  </button>
                  <button type="submit" name="action" value="ditolak" class="btn btn-sm btn-danger">
                    <i class="bi bi-x-lg me-1"></i> Tolak
                  </button>
                  <button type="submit" name="action" value="ditangguhkan" class="btn btn-sm btn-warning text-white">
                    <i class="bi bi-pause-fill me-1"></i> Tangguhkan
                  </button>
                </div>
              </form>

              @if ($riwayatDiklat->validator)
                <hr>
                <div style="font-size: 11px;" class="text-muted">
                  <div>Terakhir divalidasi oleh: <strong>{{ $riwayatDiklat->validator->name }}</strong></div>
                  <div>Waktu: {{ $riwayatDiklat->validated_at?->format('d M Y H:i') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
