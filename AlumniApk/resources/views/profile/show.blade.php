@extends('layout.main')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h3 class="fw-bold mb-2">Profil Pengguna</h3>
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
      <i class="fa-solid fa-pen me-1"></i> Edit Profil
    </a>
  </div>

  {{-- Card Utama --}}
  <div class="card shadow border-0 mb-4">
    <div class="card-body p-4">

      <div class="d-flex flex-column flex-md-row align-items-center">
        {{-- Foto profil default (huruf inisial) --}}
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-md-4 mb-3 mb-md-0"
             style="width:100px; height:100px; font-size:40px;">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>

        <div class="text-center text-md-start">
          <h4 class="fw-bold mb-0">{{ Auth::user()->name }}</h4>
          <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
          <span class="badge bg-secondary text-uppercase">{{ Auth::user()->role }}</span>
        </div>
      </div>

      <hr class="my-4">

      <div class="row g-3">
        <div class="col-md-6">
          <p class="mb-1 text-muted">Nama Lengkap</p>
          <h6>{{ Auth::user()->name ?? '-' }}</h6>
        </div>

        <div class="col-md-6">
          <p class="mb-1 text-muted">Email</p>
          <h6>{{ Auth::user()->email ?? '-' }}</h6>
        </div>

        <div class="col-md-6">
          <p class="mb-1 text-muted">Peran</p>
          <h6 class="text-capitalize">{{ Auth::user()->role ?? '-' }}</h6>
        </div>

        <div class="col-md-6">
          <p class="mb-1 text-muted">Tanggal Bergabung</p>
          <h6>{{ Auth::user()->created_at->format('d M Y') }}</h6>
        </div>
      </div>

    </div>
  </div>

  {{-- Info tambahan --}}
  <div class="card shadow-sm border-0">
    <div class="card-body p-4">
      <h5 class="fw-bold mb-3">
        <i class="fa-solid fa-info-circle me-2"></i> Informasi Akun
      </h5>
      <p class="text-muted mb-0">
        Ini adalah halaman profil Anda. Jika ingin memperbarui data pribadi seperti nama, email,
        atau kata sandi, silakan klik tombol <strong>Edit Profil</strong> di atas.
      </p>
    </div>
  </div>

</div>
@endsection
