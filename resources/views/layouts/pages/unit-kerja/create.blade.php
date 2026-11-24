@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content">
      <div class="page-header">
        <div>
          <h1 class="page-title">Tambah Unit Kerja</h1>
          <nav class="breadcrumb mb-0">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
            <a href="{{ route('unit-kerja.index') }}" class="breadcrumb-item">Unit Kerja</a>
            <span class="breadcrumb-item active">Tambah</span>
          </nav>
        </div>
        <div class="page-header-actions">
          <a href="{{ route('unit-kerja.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Form Unit Kerja (OTK)</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('unit-kerja.store') }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-4">
                <label for="kode" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                <input type="text" id="kode" name="kode" class="form-control text-uppercase @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="Contoh: LPTI, FT, S1-TIF" required autofocus>
                @error('kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-8">
                <label for="nama" class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Fakultas Teknik" required>
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4">
                <label for="jenis" class="form-label">Jenis Unit <span class="text-danger">*</span></label>
                <select id="jenis" name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                  @foreach ($jenisOptions as $val => $label)
                    <option value="{{ $val }}" {{ old('jenis', 'unit') === $val ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
                @error('jenis')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-5">
                <label for="parent_id" class="form-label">Induk Unit Kerja (Hierarki OTK)</label>
                <select id="parent_id" name="parent_id" class="form-select select2 @error('parent_id') is-invalid @enderror" data-placeholder="-- Tidak ada (Level Puncak) --">
                  <option value="">-- Tidak ada (Level Puncak) --</option>
                  @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" {{ (string) old('parent_id') === (string) $parent->id ? 'selected' : '' }}>
                      [{{ $parent->kode }}] {{ $parent->nama }}
                    </option>
                  @endforeach
                </select>
                @error('parent_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label for="urutan" class="form-label">Urutan Hierarki</label>
                <input type="number" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan', 99) }}" min="1">
                @error('urutan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('unit-kerja.index') }}" class="btn btn-light">Batal</a>
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
