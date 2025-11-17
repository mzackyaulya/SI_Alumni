@extends('layout.main')

@section('title','Buat Event')

@section('content')
<div class="container py-4" style="max-width: 900px;">
  <a href="{{ route('admin.event.index') }}" class="btn btn-light mb-3">
    <i class="fa-solid fa-arrow-left me-1"></i>Kembali
  </a>

  <div class="card shadow-sm border-0">
    <div class="card-body p-4">
      <h4 class="fw-bold mb-3">Event Baru</h4>

      <form method="POST" action="{{ route('admin.event.store') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Judul <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal & Jam Mulai <span class="text-danger">*</span></label>
            <input type="datetime-local" name="start_at"
                   class="form-control @error('start_at') is-invalid @enderror"
                   value="{{ old('start_at') }}" required>
            @error('start_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal & Jam Berakhir</label>
            <input type="datetime-local" name="end_at"
                   class="form-control @error('end_at') is-invalid @enderror"
                   value="{{ old('end_at') }}">
            @error('end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Lokasi</label>
          <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                 value="{{ old('location') }}" placeholder="Aula sekolah, Zoom, dll">
          @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" rows="6"
                    class="form-control @error('deskripsi') is-invalid @enderror"
                    placeholder="Detail acara, rundown, kontak, dsb.">{{ old('deskripsi') }}</textarea>
          @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch mb-4">
          <input class="form-check-input" type="checkbox" name="is_published" value="1"
                 id="is_published" {{ old('is_published', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_published">Publish</label>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <button class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
