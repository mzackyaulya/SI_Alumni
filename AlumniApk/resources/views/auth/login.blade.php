@extends('layout.main')

@section('title', 'FORM LOGIN')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card border-0 shadow-lg overflow-hidden">
        <div class="row g-0">
          {{-- Panel kiri (branding) --}}
          <div class="d-none d-md-block col-md-6 bg-dark text-white p-4 position-relative">
            <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-primary opacity-25" style="width:140px;height:140px;"></div>
            <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle bg-info opacity-25" style="width:120px;height:120px;"></div>

            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <span class="fw-bold text-white">A</span>
              </div>
              <div>
                <small class="text-uppercase text-white-50">Aplikasi Alumni</small>
                <h5 class="mb-0 text-light">SMA NEGERI 1 BELIMBING</h5>
              </div>
            </div>

            <h3 class="fw-bold text-light">Selamat Datang</h3>
            <p class="text-white mb-4 mt-3">Masuk untuk mengelola profil alumni, ikut event, dan lihat lowongan kerja terkini.</p>
          </div>

          {{-- Panel kanan (form) --}}
          <div class="col-12 col-md-6 p-4 p-lg-5 bg-light">
            {{-- Status sukses (mis: setelah reset password) --}}
            @if (session('status'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <div class="mb-3 text-center text-md-start">
              <h4 class="fw-bold mb-1">Masuk</h4>
              <div class="text-muted">Gunakan email dan kata sandi terdaftar.</div>
            </div>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        placeholder="Masukan Email Anda">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label mb-0">Kata Sandi</label>
                    </div>

                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukan Password Anda">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan/sembunyikan password">
                            <span class="password-eye-open">Tampil</span>
                            <span class="password-eye-close d-none">Sembunyi</span>
                        </button>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Remember me + Register --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                </div>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
              Dengan masuk, Anda menyetujui <a href="#" class="link-secondary">Ketentuan</a> & <a href="#" class="link-secondary">Kebijakan Privasi</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Toggle password (vanilla JS, tanpa icon dependency) --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('password');
    const openTxt = btn?.querySelector('.password-eye-open');
    const closeTxt = btn?.querySelector('.password-eye-close');

    if (btn && input) {
      btn.addEventListener('click', function () {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        openTxt?.classList.toggle('d-none', show);
        closeTxt?.classList.toggle('d-none', !show);
      });
    }
  });
</script>
@endsection
