@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Validasi Riwayat Jabatan Fungsional</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kepegawaian</span>
            <a href="{{ route('validasi-jabatan-fungsional.index') }}" class="breadcrumb-item">Jabatan Fungsional</a>
            <span class="breadcrumb-item active">Detail</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('validasi-jabatan-fungsional.index') }}" class="btn btn-outline-secondary btn-sm">
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
              <h5 class="card-title mb-0">Informasi Riwayat Jabatan Fungsional</h5>
              <span class="badge {{ $riwayatJabatanFungsional->statusBadgeClass() }}">{{ $riwayatJabatanFungsional->statusLabel() }}</span>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                <tr>
                  <th style="width: 210px;" class="text-muted">NIP Pegawai</th>
                  <td>{{ $riwayatJabatanFungsional->pegawai?->nip ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Pegawai</th>
                  <td class="fw-semibold">{{ $riwayatJabatanFungsional->pegawai?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Unit Kerja</th>
                  <td>{{ $riwayatJabatanFungsional->pegawai?->unitKerja ? "{$riwayatJabatanFungsional->pegawai->unitKerja->kode} - {$riwayatJabatanFungsional->pegawai->unitKerja->nama}" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jabatan Akademik</th>
                  <td>{{ $riwayatJabatanFungsional->pegawai?->klasifikasiSdm?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="2"><hr class="my-2"></td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Jabatan Fungsional</th>
                  <td class="fw-semibold">{{ $riwayatJabatanFungsional->jabatanFungsional?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">TMT Jabatan</th>
                  <td>{{ $riwayatJabatanFungsional->tmt_jabatan ? $riwayatJabatanFungsional->tmt_jabatan->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Angka Kredit</th>
                  <td>{{ $riwayatJabatanFungsional->angka_kredit !== null ? number_format((float) $riwayatJabatanFungsional->angka_kredit, 2, ',', '.') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal SK</th>
                  <td>{{ $riwayatJabatanFungsional->tanggal_sk ? $riwayatJabatanFungsional->tanggal_sk->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nomor SK</th>
                  <td>{{ $riwayatJabatanFungsional->nomor_sk ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">File Jabatan</th>
                  <td>{{ $riwayatJabatanFungsional->file_jabatan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Keterangan</th>
                  <td>{{ $riwayatJabatanFungsional->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Diajukan</th>
                  <td>{{ $riwayatJabatanFungsional->tanggal_diajukan ? $riwayatJabatanFungsional->tanggal_diajukan->format('d M Y') : '-' }}</td>
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
              <form action="{{ route('validasi-jabatan-fungsional.bulk') }}" method="POST">
                @csrf
                <input type="hidden" name="ids[]" value="{{ $riwayatJabatanFungsional->id }}">

                <div class="mb-3">
                  <label for="catatan_validasi" class="form-label form-label-sm fw-semibold">Catatan Validasi (Opsional)</label>
                  <textarea id="catatan_validasi" name="catatan_validasi" rows="3" class="form-control form-control-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_validasi', $riwayatJabatanFungsional->catatan_validasi) }}</textarea>
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

              @if ($riwayatJabatanFungsional->validator)
                <hr>
                <div style="font-size: 11px;" class="text-muted">
                  <div>Terakhir divalidasi oleh: <strong>{{ $riwayatJabatanFungsional->validator->name }}</strong></div>
                  <div>Waktu: {{ $riwayatJabatanFungsional->validated_at?->format('d M Y H:i') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
