@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Tambah Jabatan Fungsional</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <a href="{{ route('jabatan-fungsional.index') }}" class="breadcrumb-item">Jabatan Fungsional</a>
            <span class="breadcrumb-item active">Tambah</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('jabatan-fungsional.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
          </a>
          <button type="submit" form="formJabatanFungsional" class="btn btn-primary btn-sm">
            <i class="bi bi-check-lg me-1"></i> Simpan
          </button>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Detail Jabatan Fungsional</h5>
        </div>
        <div class="card-body">
          <form id="formJabatanFungsional" action="{{ route('jabatan-fungsional.store') }}" method="POST">
            @csrf

            <div class="row g-4">
              <!-- Left Column -->
              <div class="col-lg-6">
                <div class="mb-3">
                  <label for="klasifikasi_sdm_id" class="form-label">Jabatan Akademik <span class="text-danger">*</span></label>
                  <select id="klasifikasi_sdm_id" name="klasifikasi_sdm_id" class="form-select select2 @error('klasifikasi_sdm_id') is-invalid @enderror" data-placeholder="-- Pilih Jabatan Akademik --" required>
                    <option value="">-- Pilih Jabatan Akademik --</option>
                    @foreach ($klasifikasiSdms as $item)
                      <option value="{{ $item->id }}" {{ (string) old('klasifikasi_sdm_id') === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('klasifikasi_sdm_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="kode" class="form-label">Kode Jabatan <span class="text-danger">*</span></label>
                  <input type="text" id="kode" name="kode" class="form-control text-uppercase @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="Contoh: 31, 32, 41" maxlength="20" required>
                  @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="nama" class="form-label">Jabatan Fungsional <span class="text-danger">*</span></label>
                  <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Asisten Ahli (100)" maxlength="150" required>
                  @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="pangkat_id" class="form-label">Golongan/Pangkat</label>
                  <select id="pangkat_id" name="pangkat_id" class="form-select select2 @error('pangkat_id') is-invalid @enderror" data-placeholder="-- Pilih Golongan/Pangkat --">
                    <option value="">-- Pilih Golongan/Pangkat --</option>
                    @foreach ($pangkats as $p)
                      <option value="{{ $p->id }}" {{ (string) old('pangkat_id') === (string) $p->id ? 'selected' : '' }}>
                        {{ $p->pangkat }} - {{ $p->nama_golongan }}
                      </option>
                    @endforeach
                  </select>
                  @error('pangkat_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-0">
                  <label for="angka_kredit" class="form-label">Angka Kredit <span class="text-danger">*</span></label>
                  <input type="number" id="angka_kredit" name="angka_kredit" class="form-control @error('angka_kredit') is-invalid @enderror" value="{{ old('angka_kredit', 0) }}" min="0" required>
                  @error('angka_kredit')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-lg-6">
                <div class="mb-3">
                  <label for="usia_pensiun" class="form-label">Usia Pensiun <span class="text-danger">*</span></label>
                  <input type="number" id="usia_pensiun" name="usia_pensiun" class="form-control @error('usia_pensiun') is-invalid @enderror" value="{{ old('usia_pensiun', 65) }}" min="1" max="100" required>
                  @error('usia_pensiun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="referensi_sister" class="form-label">Referensi SISTER</label>
                  <select id="referensi_sister" name="referensi_sister" class="form-select select2 @error('referensi_sister') is-invalid @enderror" data-placeholder="-- Pilih Referensi SISTER --">
                    <option value="">-- Pilih Referensi SISTER --</option>
                    @foreach ($referensiSisterOptions as $opt)
                      <option value="{{ $opt }}" {{ old('referensi_sister') === $opt ? 'selected' : '' }}>
                        {{ $opt }}
                      </option>
                    @endforeach
                  </select>
                  @error('referensi_sister')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-0">
                  <label for="keterangan" class="form-label">Keterangan</label>
                  <textarea id="keterangan" name="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                  @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection
