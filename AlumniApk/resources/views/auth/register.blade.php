@extends('layout.main')

@section('title', 'Register Akun')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0">
        <div class="card-body p-4">
          <h4 class="text-center mb-4">Daftar Akun</h4>

          <form method="POST" action="{{ route('register') }}">
            @csrf
            {{-- Nama --}}
            <div class="mb-3">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input id="name" type="text" name="name"
                     class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name') }}" required autofocus>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}" required autocomplete="email">
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Nomor HP --}}
            <div class="mb-3">
              <label for="phone" class="form-label">Nomor Handphone</label>
              <input id="phone" type="text" name="phone"
                     class="form-control @error('phone') is-invalid @enderror"
                     value="{{ old('phone') }}" required placeholder="0812xxxxxxx">
              @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Role --}}
            <div class="mb-3">
              <label for="role" class="form-label">Daftar sebagai</label>
              <select id="role" name="role"
                      class="form-select @error('role') is-invalid @enderror" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role</option>
                <option value="alumni" {{ old('role')=='alumni'?'selected':'' }}>Alumni</option>
                <option value="perusahaan" {{ old('role')=='perusahaan'?'selected':'' }}>Perusahaan</option>
                <option value="waka_siswa" {{ old('role')=='waka_siswa'?'selected':'' }}>Waka Siswa</option>
              </select>
              @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input id="password" type="password" name="password"
                     class="form-control @error('password') is-invalid @enderror"
                     required autocomplete="new-password">
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
              <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
              <input id="password_confirmation" type="password" name="password_confirmation"
                     class="form-control" required autocomplete="new-password">
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">Daftar Sekarang</button>
            </div>

            <p class="text-center mt-3 mb-0 small">
              Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
