@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Validasi Riwayat Hubungan Kerja</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kepegawaian</span>
            <a href="{{ route('validasi-hubungan-kerja.index') }}" class="breadcrumb-item">Hubungan Kerja</a>
            <span class="breadcrumb-item active">Detail</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('validasi-hubungan-kerja.index') }}" class="btn btn-outline-secondary btn-sm">
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
              <h5 class="card-title mb-0">Informasi Riwayat Hubungan Kerja</h5>
              <span class="badge {{ $riwayatHubunganKerja->statusBadgeClass() }}">{{ $riwayatHubunganKerja->statusLabel() }}</span>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                <tr>
                  <th style="width: 210px;" class="text-muted">NIP Pegawai</th>
                  <td>{{ $riwayatHubunganKerja->pegawai?->nip ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Pegawai</th>
                  <td class="fw-semibold">{{ $riwayatHubunganKerja->pegawai?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Unit Kerja</th>
                  <td>{{ $riwayatHubunganKerja->pegawai?->unitKerja ? "{$riwayatHubunganKerja->pegawai->unitKerja->kode} - {$riwayatHubunganKerja->pegawai->unitKerja->nama}" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jabatan Akademik</th>
                  <td>{{ $riwayatHubunganKerja->pegawai?->klasifikasiSdm?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="2"><hr class="my-2"></td>
                </tr>
                <tr>
                  <th class="text-muted">Hubungan Kerja</th>
                  <td class="fw-semibold">{{ $riwayatHubunganKerja->hubunganKerja?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Mulai</th>
                  <td>{{ $riwayatHubunganKerja->tanggal_mulai ? $riwayatHubunganKerja->tanggal_mulai->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Selesai</th>
                  <td>{{ $riwayatHubunganKerja->tanggal_selesai ? $riwayatHubunganKerja->tanggal_selesai->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nomor SK</th>
                  <td>{{ $riwayatHubunganKerja->nomor_sk ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal SK</th>
                  <td>{{ $riwayatHubunganKerja->tanggal_sk ? $riwayatHubunganKerja->tanggal_sk->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">File Hubungan Kerja</th>
                  <td>{{ $riwayatHubunganKerja->file_hubungan_kerja ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Keterangan</th>
                  <td>{{ $riwayatHubunganKerja->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Diajukan</th>
                  <td>{{ $riwayatHubunganKerja->tanggal_diajukan ? $riwayatHubunganKerja->tanggal_diajukan->format('d M Y') : '-' }}</td>
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
              <form action="{{ route('validasi-hubungan-kerja.bulk') }}" method="POST">
                @csrf
                <input type="hidden" name="ids[]" value="{{ $riwayatHubunganKerja->id }}">

                <div class="mb-3">
                  <label for="catatan_validasi" class="form-label form-label-sm fw-semibold">Catatan Validasi (Opsional)</label>
                  <textarea id="catatan_validasi" name="catatan_validasi" rows="3" class="form-control form-control-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_validasi', $riwayatHubunganKerja->catatan_validasi) }}</textarea>
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

              @if ($riwayatHubunganKerja->validator)
                <hr>
                <div style="font-size: 11px;" class="text-muted">
                  <div>Terakhir divalidasi oleh: <strong>{{ $riwayatHubunganKerja->validator->name }}</strong></div>
                  <div>Waktu: {{ $riwayatHubunganKerja->validated_at?->format('d M Y H:i') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
