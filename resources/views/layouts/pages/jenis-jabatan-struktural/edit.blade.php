@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header">
        <div>
          <h1 class="page-title">Edit Jenis Jabatan Struktural</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <a href="{{ route('jenis-jabatan-struktural.index') }}" class="breadcrumb-item">Jenis Jabatan Struktural</a>
            <span class="breadcrumb-item active">Edit {{ $jabatan->kode }}</span>
          </nav>
        </div>
        <div class="page-header-actions">
          <a href="{{ route('jenis-jabatan-struktural.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Form Edit Jenis Jabatan Struktural</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('jenis-jabatan-struktural.update', $jabatan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <div class="col-md-4">
                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="kode"
                  name="kode"
                  class="form-control text-uppercase @error('kode') is-invalid @enderror"
                  value="{{ old('kode', $jabatan->kode) }}"
                  maxlength="20"
                  required
                >
                @error('kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-8">
                <label for="nama" class="form-label">Jenis Jabatan Struktural <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="nama"
                  name="nama"
                  class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $jabatan->nama) }}"
                  maxlength="150"
                  required
                >
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('jenis-jabatan-struktural.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection
