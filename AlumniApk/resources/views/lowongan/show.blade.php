@extends('layout.main')

@section('title', $lowongan->judul.' – Detail Lowongan')

@section('content')
<style>
  /* --- Scoped styles agar tidak ganggu halaman lain --- */
  .job-card{border:0;box-shadow:0 6px 20px rgba(0,0,0,.06);border-radius:14px}
  .job-meta dt{color:#6b7280;min-width:110px}
  .job-meta dd{margin-bottom:.5rem}
  .chip{display:inline-block;padding:.25rem .6rem;border-radius:999px;font-size:.8rem;border:1px solid #e5e7eb;background:#fff}
  .chip-primary{background:#eef2ff;border-color:#e0e7ff;color:#4338ca}
  .chip-soft{background:#f8fafc;border-color:#e5e7eb;color:#0f172a}
  .section-title{font-weight:800;letter-spacing:.2px}
  .btn-icon i{margin-right:.45rem}
  .divider{height:1px;background:#eef2f6;margin:1rem 0 1.25rem}
  .brand-avatar{width:56px;height:56px;border-radius:12px;object-fit:cover;border:1px solid #eef2f6}
  @media (min-width: 992px){
    .actions-right{justify-content:flex-end}
  }
</style>

@php
  $perusahaan = optional($lowongan->perusahaan);
  $selisih = $lowongan->deadline ? now()->diffInDays($lowongan->deadline, false) : null;

  // helper kecil
  $rupiah = function($n){ return 'Rp '.number_format((int)$n, 0, ',', '.'); };
@endphp

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10">
      <div class="card job-card">
        <div class="card-body p-4 p-lg-5">

          {{-- Header --}}
          <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
              <img src="{{ $perusahaan->logo_url ?? asset('assets/img/profil.jpg') }}" alt="logo"
                   class="brand-avatar">
              <div>
                <h2 class="mb-1 fw-black" style="line-height:1.2">{{ $lowongan->judul }}</h2>
                <div class="text-muted small">
                  <i class="fa-solid fa-building me-1"></i>{{ $perusahaan->nama ?? 'Perusahaan' }}
                  <span class="mx-2">•</span>
                  <i class="fa-solid fa-location-dot me-1"></i>{{ $lowongan->lokasi ?? 'Lokasi tidak disebutkan' }}
                </div>
              </div>
            </div>

            {{-- Deadline badge --}}
            @if($lowongan->deadline)
                <div>
                    @php
                    // Hitung selisih hari secara bulat
                    $selisih = floor(now()->diffInDays($lowongan->deadline, false));

                    // Tentukan warna badge
                    $badgeClass = $selisih < 0 ? 'text-bg-danger'
                                : ($selisih <= 3 ? 'text-bg-warning' : 'text-bg-light text-dark border');

                    // Tentukan label teks
                    $label = $selisih < 0 ? 'Lewat ' . $lowongan->deadline->format('d M Y')
                            : ($selisih == 0 ? 'Tutup hari ini'
                            : ($selisih == 1 ? 'Tutup besok'
                            : 'Tutup ' . $selisih . ' hari lagi'));
                    @endphp

                    <span class="badge {{ $badgeClass }}">
                    <i class="fa-regular fa-calendar me-1"></i>{{ $label }}
                    </span>
                </div>
            @endif
          </div>

          <div class="divider"></div>

          {{-- Meta 2 kolom --}}
          <div class="row g-4 mb-2">
            <div class="col-md-6">
              <dl class="job-meta m-0">
                <dt>Tipe</dt><dd>{{ $lowongan->tipe ?? '-' }}</dd>
                <dt>Level</dt><dd>{{ $lowongan->level ?? '-' }}</dd>
                <dt>Status</dt>
                <dd>
                  <span class="badge {{ $lowongan->aktif ? 'text-success' : 'text-secondary' }}">
                    {{ $lowongan->aktif ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </dd>
              </dl>
            </div>
            <div class="col-md-6">
              <dl class="job-meta m-0">
                <dt>Gaji</dt>
                <dd>
                  @if($lowongan->gaji_min || $lowongan->gaji_max)
                    {{ $lowongan->gaji_min ? $rupiah($lowongan->gaji_min) : '' }}
                    @if($lowongan->gaji_min && $lowongan->gaji_max) – @endif
                    {{ $lowongan->gaji_max ? $rupiah($lowongan->gaji_max) : '' }}
                  @else
                    -
                  @endif
                </dd>
                <dt>Dibuat</dt><dd>{{ $lowongan->created_at->format('d M Y') }}</dd>
              </dl>
            </div>
          </div>

          {{-- Tag / Skill --}}
          @if(!empty($lowongan->tag))
            <div class="mb-3">
              @foreach($lowongan->tag as $tag)
                <span class="chip chip-primary me-1 mb-1">{{ $tag }}</span>
              @endforeach
            </div>
          @endif

            {{-- Deskripsi --}}
            @if($lowongan->deskripsi)
                <div class="mt-4">
                    <h4 class="section-title mb-2"><i class="fa-solid fa-file-lines me-2"></i>Deskripsi Pekerjaan</h4>
                    <div class="text-secondary">{!! nl2br(e($lowongan->deskripsi)) !!}</div>
                </div>
            @endif

            {{-- Kualifikasi --}}
            @if($lowongan->kualifikasi)
                <div class="mt-4">
                    <h4 class="section-title mb-2"><i class="fa-solid fa-list-check me-2"></i>Kualifikasi</h4>
                    <div class="text-secondary">{!! nl2br(e($lowongan->kualifikasi)) !!}</div>
                </div>
            @endif

            <div class="divider"></div>

            {{--  Aksi: rata kanan pada layar lebar, tetap rapi di mobile  --}}
            <div class="d-flex flex-wrap gap-2 actions-right">
                <a href="{{ route('lowongan.index') }}" class="btn btn-light btn-icon">
                <i class="fa-solid fa-arrow-left"></i>Kembali
                </a>

                @if(Auth::check() && Auth::user()->role === 'alumni' && $lowongan->aktif && ($selisih === null || $selisih >= 0))
                <a href="{{ route('lamaran.create', ['lowongan' => $lowongan->id]) }}" class="btn btn-success btn-icon">
                    <i class="fa-solid fa-paper-plane"></i>Lamar Sekarang
                </a>
                @endif

                @php
                    $user = Auth::user();
                    $isOwner = $user && $user->role === 'company' && optional($lowongan->perusahaan)->user_id === $user->id;
                    $isAdmin = $user && $user->role === 'admin';
                @endphp

                @if($isOwner || $isAdmin)
                    <a href="{{ route('lowongan.edit', $lowongan->id) }}" class="btn btn-warning btn-icon">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>

                    <form action="{{ route('lowongan.destroy', $lowongan->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-icon">
                        <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
