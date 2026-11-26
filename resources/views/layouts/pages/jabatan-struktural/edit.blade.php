@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header fx-page-header">
        <div>
          <h1 class="page-title">Edit Jabatan Struktural</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <a href="{{ route('jabatan-struktural.index') }}" class="breadcrumb-item">Jabatan Struktural</a>
            <span class="breadcrumb-item active">Edit {{ $jabatan->kode }}</span>
          </nav>
        </div>
        <div class="page-header-actions d-flex gap-2">
          <a href="{{ route('jabatan-struktural.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
          </a>
          <button type="submit" form="formEditJabatanStruktural" class="btn btn-primary btn-sm">
            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
          </button>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Form Edit Jabatan Struktural</h5>
        </div>
        <div class="card-body">
          <form id="formEditJabatanStruktural" action="{{ route('jabatan-struktural.update', $jabatan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
              <!-- Left Column -->
              <div class="col-lg-6">
                <div class="mb-3">
                  <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                  <input type="text" id="kode" name="kode" class="form-control text-uppercase @error('kode') is-invalid @enderror" value="{{ old('kode', $jabatan->kode) }}" maxlength="30" required>
                  @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="nama" class="form-label">Nama Jabatan Struktural <span class="text-danger">*</span></label>
                  <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $jabatan->nama) }}" maxlength="150" required>
                  @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="jenis_jabatan_struktural_id" class="form-label">Jenis Jabatan Struktural</label>
                  <select id="jenis_jabatan_struktural_id" name="jenis_jabatan_struktural_id" class="form-select select2 @error('jenis_jabatan_struktural_id') is-invalid @enderror" data-placeholder="-- Pilih Jenis Jabatan Struktural --">
                    <option value="">-- Pilih Jenis Jabatan Struktural --</option>
                    @foreach ($jenisJabatans as $j)
                      <option value="{{ $j->id }}" {{ (string) old('jenis_jabatan_struktural_id', $jabatan->jenis_jabatan_struktural_id) === (string) $j->id ? 'selected' : '' }}>
                        [{{ $j->kode }}] {{ $j->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('jenis_jabatan_struktural_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="singkatan" class="form-label">Singkatan</label>
                  <input type="text" id="singkatan" name="singkatan" class="form-control @error('singkatan') is-invalid @enderror" value="{{ old('singkatan', $jabatan->singkatan) }}" maxlength="50">
                  @error('singkatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="parent_id" class="form-label">Parent Jabatan Struktural</label>
                  <select id="parent_id" name="parent_id" class="form-select select2 @error('parent_id') is-invalid @enderror" data-placeholder="-- Pilih Parent Jabatan Struktural --">
                    <option value="">-- Pilih Parent Jabatan Struktural --</option>
                    @foreach ($parents as $p)
                      <option value="{{ $p->id }}" {{ (string) old('parent_id', $jabatan->parent_id) === (string) $p->id ? 'selected' : '' }}>
                        [{{ $p->kode }}] {{ $p->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="unit_kerja_id" class="form-label">Unit Kerja</label>
                  <select id="unit_kerja_id" name="unit_kerja_id" class="form-select select2 @error('unit_kerja_id') is-invalid @enderror" data-placeholder="-- Pilih Unit Kerja --">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($unitKerjas as $unit)
                      <option value="{{ $unit->id }}" {{ (string) old('unit_kerja_id', $jabatan->unit_kerja_id) === (string) $unit->id ? 'selected' : '' }}>
                        [{{ $unit->kode }}] {{ $unit->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('unit_kerja_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-0">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $jabatan->email) }}" maxlength="150">
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-lg-6">
                <div class="mb-3">
                  <label for="eselon" class="form-label">Eselon</label>
                  <select id="eselon" name="eselon" class="form-select select2 @error('eselon') is-invalid @enderror" data-placeholder="-- Pilih Eselon --">
                    <option value="">-- Pilih Eselon --</option>
                    @foreach ($eselonOptions as $val => $label)
                      <option value="{{ $val }}" {{ old('eselon', $jabatan->eselon) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('eselon')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="pangkat_min" class="form-label">Pangkat Min</label>
                  <select id="pangkat_min" name="pangkat_min" class="form-select select2 @error('pangkat_min') is-invalid @enderror" data-placeholder="-- Pilih Pangkat Min --">
                    <option value="">-- Pilih Pangkat Min --</option>
                    @foreach ($pangkatOptions as $val => $label)
                      <option value="{{ $val }}" {{ old('pangkat_min', $jabatan->pangkat_min) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('pangkat_min')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="pangkat_max" class="form-label">Pangkat Max</label>
                  <select id="pangkat_max" name="pangkat_max" class="form-select select2 @error('pangkat_max') is-invalid @enderror" data-placeholder="-- Pilih Pangkat Max --">
                    <option value="">-- Pilih Pangkat Max --</option>
                    @foreach ($pangkatOptions as $val => $label)
                      <option value="{{ $val }}" {{ old('pangkat_max', $jabatan->pangkat_max) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('pangkat_max')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label for="beban_sks" class="form-label">Beban SKS</label>
                    <input type="number" step="0.01" min="0" id="beban_sks" name="beban_sks" class="form-control @error('beban_sks') is-invalid @enderror" value="{{ old('beban_sks', $jabatan->beban_sks) }}">
                    @error('beban_sks')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="urutan" class="form-label">Urutan Hierarki</label>
                    <input type="number" min="1" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan', $jabatan->urutan) }}">
                    @error('urutan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="d-flex flex-wrap gap-4 mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_pimpinan" name="is_pimpinan" value="1" {{ old('is_pimpinan', $jabatan->is_pimpinan) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_pimpinan">Is Pimpinan?</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $jabatan->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                  </div>
                </div>

                <div class="mb-0">
                  <label for="keterangan" class="form-label">Keterangan</label>
                  <textarea id="keterangan" name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $jabatan->keterangan) }}</textarea>
                  @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
              <a href="{{ route('jabatan-struktural.index') }}" class="btn btn-light">Batal</a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection
