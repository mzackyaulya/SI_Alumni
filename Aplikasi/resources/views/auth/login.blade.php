@extends('layout.main')

@section('title', 'Login - Sistem Informasi Alumni')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<section class="py-5 mt-3 d-flex align-items-center bg-light" style="min-height: 80vh;">
  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-lg-5 col-md-7">
        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white">
          <div class="text-center mb-4">
            <img src="{{ url('images/logo.jpg') }}" alt="Logo Sekolah"
                 class="rounded-circle mb-3 border shadow-sm"
                 style="height: 90px; width: 90px; object-fit: cover;">
            <h4 class="fw-bold mb-1 text-dark">Selamat Datang</h4>
            <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
          </div>

          {{-- Pesan sukses/error --}}
          @if (session('status'))
            <div class="alert alert-info text-center">{{ session('status') }}</div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
          @endif

          {{-- Form Login --}}
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" id="email" name="email"
                     class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                     placeholder="Masukkan email anda" value="{{ old('email') }}" required autofocus>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 position-relative">
              <label for="password" class="form-label fw-semibold">Password</label>
              <div class="input-group">
                <input type="password" id="password" name="password"
                       class="form-control form-control-lg rounded-start-3 @error('password') is-invalid @enderror"
                       placeholder="Masukkan password" required>
                <button type="button" id="togglePassword" class="btn btn-outline-secondary rounded-end-3">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Ingat saya</label>
              </div>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small text-decoration-none text-success">Lupa password?</a>
              @endif
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 rounded-3 shadow-sm fw-semibold">
              Masuk
            </button>
          </form>

          <div class="text-center mt-4">
            <small class="text-muted">© {{ date('Y') }} — Sistem Informasi Alumni</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Bootstrap Icons & JS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Toggle Password --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.querySelector("#togglePassword");
  const password = document.querySelector("#password");
  togglePassword.addEventListener("click", function () {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.innerHTML = type === "password"
      ? '<i class="bi bi-eye"></i>'
      : '<i class="bi bi-eye-slash"></i>';
  });
});
</script>

<style>
body {
  font-family: "Poppins", sans-serif;
}
.btn-success {
  background: linear-gradient(135deg, #16a34a, #15803d);
  border: none;
}
.btn-success:hover {
  background: linear-gradient(135deg, #15803d, #166534);
}
.card {
  animation: fadeInUp 0.6s ease-out;
}
@keyframes fadeInUp {
  from {opacity: 0; transform: translateY(30px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
@endsection
