@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Validasi Pendidikan Formal</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <span class="breadcrumb-item">Kualifikasi</span>
            <a href="{{ route('validasi-pendidikan-formal.index') }}" class="breadcrumb-item">Pendidikan Formal</a>
            <span class="breadcrumb-item active">Detail</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('validasi-pendidikan-formal.index') }}" class="btn btn-outline-secondary btn-sm">
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
              <h5 class="card-title mb-0">Informasi Pendidikan Formal Pegawai</h5>
              <span class="badge {{ $riwayatPendidikanFormal->statusBadgeClass() }}">{{ $riwayatPendidikanFormal->statusLabel() }}</span>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                <tr>
                  <th style="width: 210px;" class="text-muted">NIP Pegawai</th>
                  <td>{{ $riwayatPendidikanFormal->pegawai?->nip ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Pegawai</th>
                  <td class="fw-semibold">{{ $riwayatPendidikanFormal->pegawai?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Unit Kerja</th>
                  <td>{{ $riwayatPendidikanFormal->pegawai?->unitKerja ? "{$riwayatPendidikanFormal->pegawai->unitKerja->kode} - {$riwayatPendidikanFormal->pegawai->unitKerja->nama}" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jabatan Akademik</th>
                  <td>{{ $riwayatPendidikanFormal->pegawai?->klasifikasiSdm?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="2"><hr class="my-2"></td>
                </tr>
                <tr>
                  <th class="text-muted">Jenjang Pendidikan</th>
                  <td class="fw-semibold">
                    {{ $riwayatPendidikanFormal->jenjangPendidikan ? "{$riwayatPendidikanFormal->jenjangPendidikan->kode} - {$riwayatPendidikanFormal->jenjangPendidikan->nama}" : '-' }}
                  </td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Institusi</th>
                  <td class="fw-semibold">{{ $riwayatPendidikanFormal->perguruanTinggi?->nama ?? $riwayatPendidikanFormal->nama_institusi }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Program Studi</th>
                  <td>{{ $riwayatPendidikanFormal->program_studi ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Gelar Akademik</th>
                  <td>{{ $riwayatPendidikanFormal->gelarAkademik ? "{$riwayatPendidikanFormal->gelarAkademik->gelar} ({$riwayatPendidikanFormal->gelarAkademik->nama})" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tahun Masuk / Lulus</th>
                  <td>{{ $riwayatPendidikanFormal->tahun_masuk ?? '-' }} / {{ $riwayatPendidikanFormal->tahun_lulus ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">IPK</th>
                  <td>{{ $riwayatPendidikanFormal->ipk !== null ? number_format((float) $riwayatPendidikanFormal->ipk, 2, ',', '.') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">No. Ijazah</th>
                  <td>{{ $riwayatPendidikanFormal->no_ijazah ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Ijazah</th>
                  <td>{{ $riwayatPendidikanFormal->tanggal_ijazah ? $riwayatPendidikanFormal->tanggal_ijazah->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">File Ijazah</th>
                  <td>{{ $riwayatPendidikanFormal->file_ijazah ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Keterangan</th>
                  <td>{{ $riwayatPendidikanFormal->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Diajukan</th>
                  <td>{{ $riwayatPendidikanFormal->tanggal_diajukan ? $riwayatPendidikanFormal->tanggal_diajukan->format('d M Y') : '-' }}</td>
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
              <form action="{{ route('validasi-pendidikan-formal.bulk') }}" method="POST">
                @csrf
                <input type="hidden" name="ids[]" value="{{ $riwayatPendidikanFormal->id }}">

                <div class="mb-3">
                  <label for="catatan_validasi" class="form-label form-label-sm fw-semibold">Catatan Validasi (Opsional)</label>
                  <textarea id="catatan_validasi" name="catatan_validasi" rows="3" class="form-control form-control-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_validasi', $riwayatPendidikanFormal->catatan_validasi) }}</textarea>
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

              @if ($riwayatPendidikanFormal->validator)
                <hr>
                <div style="font-size: 11px;" class="text-muted">
                  <div>Terakhir divalidasi oleh: <strong>{{ $riwayatPendidikanFormal->validator->name }}</strong></div>
                  <div>Waktu: {{ $riwayatPendidikanFormal->validated_at?->format('d M Y H:i') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
