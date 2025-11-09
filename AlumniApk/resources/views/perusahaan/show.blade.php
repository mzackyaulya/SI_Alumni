@extends('layout.main')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
      <i class="fa-solid fa-building me-2 text-primary"></i>Profil Perusahaan
    </h4>
    <a href="{{ route('perusahaan.index') }}" class="btn btn-light btn-sm">
      <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
  </div>

  {{-- Card utama --}}
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
      <div class="d-flex flex-wrap align-items-start gap-4">

        {{-- Logo --}}
        <div>
          <img src="{{ $perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)
                      ? Storage::disk('public')->url($perusahaan->logo)
                      : asset('assets/img/company.png') }}"
               alt="{{ $perusahaan->nama }}"
               class="rounded border shadow-sm"
               style="width:110px;height:110px;object-fit:cover;">
        </div>

        {{-- Info dasar --}}
        <div class="flex-fill">
          <h5 class="fw-bold mb-1">{{ $perusahaan->nama }}</h5>
          <div class="text-muted small mb-2">
            <i class="fa-solid fa-briefcase me-1"></i>{{ $perusahaan->industri ?? '-' }}
            <span class="mx-2">|</span>
            <i class="fa-solid fa-location-dot me-1"></i>{{ $perusahaan->kota ?? '-' }}
          </div>

          <div class="mb-2">
            @if ($perusahaan->is_verified)
              <span class="badge bg-success"><i class="fa-solid fa-shield-check me-1"></i>Terverifikasi</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-hourglass-half me-1"></i>Belum Terverifikasi</span>
            @endif
          </div>

          @if ($perusahaan->website)
            <a href="{{ $perusahaan->website }}" target="_blank" rel="noopener" class="text-decoration-none small">
              <i class="fa-solid fa-globe me-1"></i> {{ $perusahaan->website }}
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Informasi detail --}}
  <div class="row g-3">
    {{-- Kolom kiri --}}
    <div class="col-md-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-light fw-semibold">
          <i class="fa-solid fa-circle-info me-1"></i>Informasi Umum
        </div>
        <div class="card-body">
          <dl class="row mb-0 small">
            <dt class="col-sm-4">Nama</dt>
            <dd class="col-sm-8">{{ $perusahaan->nama }}</dd>

            <dt class="col-sm-4">Industri</dt>
            <dd class="col-sm-8">{{ $perusahaan->industri ?? '-' }}</dd>

            <dt class="col-sm-4">Kota</dt>
            <dd class="col-sm-8">{{ $perusahaan->kota ?? '-' }}</dd>

            <dt class="col-sm-4">Alamat</dt>
            <dd class="col-sm-8">{{ $perusahaan->alamat ?? '-' }}</dd>
          </dl>
        </div>
      </div>
    </div>

    {{-- Kolom kanan --}}
    <div class="col-md-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-light fw-semibold">
          <i class="fa-solid fa-phone me-1"></i>Kontak & Dokumen
        </div>
        <div class="card-body">
          <dl class="row mb-0 small">
            <dt class="col-sm-4">Email</dt>
            <dd class="col-sm-8">{{ $perusahaan->email ?? '-' }}</dd>

            <dt class="col-sm-4">Telepon</dt>
            <dd class="col-sm-8">{{ $perusahaan->telepon ?? '-' }}</dd>

            <dt class="col-sm-4">NPWP</dt>
            <dd class="col-sm-8">{{ $perusahaan->npwp ?? '-' }}</dd>

            <dt class="col-sm-4">SIUP</dt>
            <dd class="col-sm-8">{{ $perusahaan->siup ?? '-' }}</dd>

            <dt class="col-sm-4">Dokumen Legal</dt>
            <dd class="col-sm-8">
              @if($perusahaan->dokumen_legal && Storage::disk('public')->exists($perusahaan->dokumen_legal))
                <a href="{{ Storage::disk('public')->url($perusahaan->dokumen_legal) }}"
                   target="_blank" class="btn btn-outline-primary btn-sm">
                   <i class="fa-solid fa-file-lines me-1"></i>Lihat Dokumen
                </a>
              @else
                <span class="text-muted">Tidak ada</span>
              @endif
            </dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  {{-- Tombol Aksi (khusus admin/owner) --}}
  @if(auth()->user()->role === 'admin' || auth()->id() === $perusahaan->user_id)
    <div class="d-flex justify-content-end gap-2 mt-4">
      <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning">
        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
      </a>

      <form action="{{ route('perusahaan.destroy', $perusahaan->id) }}" method="POST"
            onsubmit="return confirm('Hapus perusahaan ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
          <i class="fa-solid fa-trash me-1"></i> Hapus
        </button>
      </form>
    </div>
  @endif

</div>
@endsection
