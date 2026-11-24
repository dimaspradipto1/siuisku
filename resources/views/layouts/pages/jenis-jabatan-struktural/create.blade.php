@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header">
        <div>
          <h1 class="page-title">Tambah Jenis Jabatan Struktural</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <a href="{{ route('jenis-jabatan-struktural.index') }}" class="breadcrumb-item">Jenis Jabatan Struktural</a>
            <span class="breadcrumb-item active">Tambah</span>
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
          <h5 class="card-title mb-0">Form Jenis Jabatan Struktural</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('jenis-jabatan-struktural.store') }}" method="POST">
            @csrf

            <div class="row g-3">
              <div class="col-md-3">
                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="kode"
                  name="kode"
                  class="form-control text-uppercase @error('kode') is-invalid @enderror"
                  value="{{ old('kode') }}"
                  placeholder="Contoh: R, WR1, DFB"
                  maxlength="20"
                  required
                  autofocus
                >
                @error('kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="nama" class="form-label">Jenis Jabatan Struktural <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="nama"
                  name="nama"
                  class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama') }}"
                  placeholder="Contoh: Dekan Fakultas Ekonomi dan Bisnis"
                  maxlength="150"
                  required
                >
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label for="urutan" class="form-label">Urutan Hierarki</label>
                <input
                  type="number"
                  id="urutan"
                  name="urutan"
                  class="form-control @error('urutan') is-invalid @enderror"
                  value="{{ old('urutan', 99) }}"
                  min="1"
                >
                @error('urutan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('jenis-jabatan-struktural.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection
