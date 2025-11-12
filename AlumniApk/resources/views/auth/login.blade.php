@extends('layout.main')

@section('title', 'FORM LOGIN')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 12px;">
                <div class="row g-0">
                    <div class="col-12 p-4 p-lg-5 bg-light">

                        {{-- Status sukses --}}
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
                                <label for="password" class="form-label">Kata Sandi</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukan Password Anda">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="password-eye-open fa-solid fa-eye"></i>
                                        <i class="password-eye-close fa-solid fa-eye-slash d-none"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Remember me --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                            </div>
                        </form>

                        <p class="text-center text-muted small mt-4 mb-0">
                            Dengan masuk, Anda menyetujui <a href="#" class="link-secondary">Ketentuan</a> &
                            <a href="#" class="link-secondary">Kebijakan Privasi</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toggle Password --}}
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
