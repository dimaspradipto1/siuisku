@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Detail Validasi Data {{ $keluargaPegawai->hubunganLabel() }}</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-item">Validasi Data</span>
            <a href="{{ route('validasi-keluarga.index', ['hubungan' => $keluargaPegawai->hubungan]) }}" class="breadcrumb-item">Keluarga</a>
            <span class="breadcrumb-item active">Detail</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('validasi-keluarga.index', ['hubungan' => $keluargaPegawai->hubungan]) }}" class="btn btn-outline-secondary btn-sm">
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
              <h5 class="card-title mb-0">Informasi {{ $keluargaPegawai->hubunganLabel() }}</h5>
              <span class="badge {{ $keluargaPegawai->statusBadgeClass() }}">{{ $keluargaPegawai->statusLabel() }}</span>
            </div>
            <div class="card-body">
              <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                <tr>
                  <th style="width: 210px;" class="text-muted">NIP Pegawai</th>
                  <td>{{ $keluargaPegawai->pegawai?->nip ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Nama Pegawai</th>
                  <td class="fw-semibold">{{ $keluargaPegawai->pegawai?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Unit Kerja</th>
                  <td>{{ $keluargaPegawai->pegawai?->unitKerja ? "{$keluargaPegawai->pegawai->unitKerja->kode} - {$keluargaPegawai->pegawai->unitKerja->nama}" : '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jabatan Akademik</th>
                  <td>{{ $keluargaPegawai->pegawai?->klasifikasiSdm?->nama ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="2"><hr class="my-2"></td>
                </tr>
                <tr>
                  <th class="text-muted">Nama {{ $keluargaPegawai->hubunganLabel() }}</th>
                  <td class="fw-semibold">{{ $keluargaPegawai->nama }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Hubungan</th>
                  <td>{{ $keluargaPegawai->status_hubungan ?? $keluargaPegawai->hubunganLabel() }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Jenis Kelamin</th>
                  <td>{{ $keluargaPegawai->jenis_kelamin === 'L' ? 'Laki-laki' : ($keluargaPegawai->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tempat, Tanggal Lahir</th>
                  <td>
                    {{ $keluargaPegawai->tempat_lahir ?? '-' }}{{ $keluargaPegawai->tanggal_lahir ? ', ' . $keluargaPegawai->tanggal_lahir->format('d M Y') : '' }}
                  </td>
                </tr>
                <tr>
                  <th class="text-muted">Kartu Nikah / No. Akta</th>
                  <td>{{ $keluargaPegawai->kartu_nikah ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Pekerjaan</th>
                  <td>{{ $keluargaPegawai->pekerjaan ?? '-' }}</td>
                </tr>
                <tr>
                  <th class="text-muted">Tanggal Diajukan</th>
                  <td>{{ $keluargaPegawai->tanggal_diajukan ? $keluargaPegawai->tanggal_diajukan->format('d M Y') : '-' }}</td>
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
              <form action="{{ route('validasi-keluarga.bulk') }}" method="POST">
                @csrf
                <input type="hidden" name="ids[]" value="{{ $keluargaPegawai->id }}">

                <div class="mb-3">
                  <label for="catatan_validasi" class="form-label form-label-sm fw-semibold">Catatan Validasi (Opsional)</label>
                  <textarea id="catatan_validasi" name="catatan_validasi" rows="3" class="form-control form-control-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_validasi', $keluargaPegawai->catatan_validasi) }}</textarea>
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

              @if ($keluargaPegawai->validator)
                <hr>
                <div style="font-size: 11px;" class="text-muted">
                  <div>Terakhir divalidasi oleh: <strong>{{ $keluargaPegawai->validator->name }}</strong></div>
                  <div>Waktu: {{ $keluargaPegawai->validated_at?->format('d M Y H:i') }}</div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
