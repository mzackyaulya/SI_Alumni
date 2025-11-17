@extends('layout.main')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h3 class="fw-bold mb-2">Edit Profil</h3>
    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
  </div>

  {{-- Alert sukses / error --}}
  @if (session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Profil berhasil diperbarui.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form Edit --}}
  <div class="card shadow border-0">
    <div class="card-body p-4">

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="row g-3">

          {{-- Nama --}}
          <div class="col-md-6">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', Auth::user()->name) }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Email --}}
          <div class="col-md-6">
            <label for="email" class="form-label fw-semibold">Alamat Email</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', Auth::user()->email) }}" required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Role (non-editable) --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Peran</label>
            <input type="text" class="form-control" value="{{ Auth::user()->role }}" disabled>
          </div>

          {{-- Tanggal dibuat (readonly) --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Tanggal Bergabung</label>
            <input type="text" class="form-control"
                   value="{{ Auth::user()->created_at->format('d M Y') }}" disabled>
          </div>

          <hr class="my-4">

          {{-- Ganti Password (opsional) --}}
          <div class="col-12">
            <h5 class="fw-bold mb-3">
              <i class="fa-solid fa-lock me-2"></i>Ubah Kata Sandi (Opsional)
            </h5>
          </div>

          <div class="col-md-6">
            <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
            <input type="password" name="current_password" id="current_password" class="form-control">
          </div>

          <div class="col-md-6">
            <label for="password" class="form-label">Kata Sandi Baru</label>
            <input type="password" name="password" id="password" class="form-control">
          </div>

          <div class="col-md-6">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Simpan
          </button>
        </div>
      </form>

    </div>
  </div>

</div>
@endsection
