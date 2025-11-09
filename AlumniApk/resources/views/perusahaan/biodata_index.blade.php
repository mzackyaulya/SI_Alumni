@extends('layout.main')

@section('title', 'Biodata Perusahaan')

@section('content')
@php use Illuminate\Support\Facades\Storage; @endphp
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">
      <i class="fa-solid fa-building me-2 text-primary"></i>Biodata Perusahaan
    </h4>
  </div>

  {{-- Filter --}}
  <form method="GET" class="card shadow-sm mb-3 border-0">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-5">
          <label class="form-label">Cari</label>
          <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}"
                 placeholder="Nama / Industri / Kota">
        </div>
        <div class="col-md-3">
          <label class="form-label">Industri</label>
          <input type="text" name="industri" class="form-control" value="{{ $industri ?? '' }}"
                 placeholder="IT / F&B / Manufaktur">
        </div>
        <div class="col-md-3">
          <label class="form-label">Kota</label>
          <input type="text" name="kota" class="form-control" value="{{ $kota ?? '' }}"
                 placeholder="Palembang">
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button class="btn btn-outline-primary w-100">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
          </button>
        </div>
      </div>
    </div>
  </form>

  {{-- Grid biodata --}}
  <div class="row g-3">
    @forelse($perusahaans as $p)
      @php
        $logo = ($p->logo && Storage::disk('public')->exists($p->logo))
                  ? Storage::disk('public')->url($p->logo)
                  : asset('assets/img/company.png');
      @endphp
      <div class="col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-2">
              <img src="{{ $logo }}" alt="{{ $p->nama }}"
                   class="rounded border" style="width:60px;height:60px;object-fit:cover">
              <div>
                <div class="fw-semibold">{{ $p->nama }}</div>
                <div class="small text-muted">
                  <i class="fa-solid fa-briefcase me-1"></i>{{ $p->industri ?? '-' }}
                </div>
                <div class="small text-muted">
                  <i class="fa-solid fa-location-dot me-1"></i>{{ $p->kota ?? '-' }}
                </div>
              </div>
            </div>

            @if($p->email)
              <div class="small mb-1">
                <i class="fa-solid fa-envelope me-1"></i>{{ $p->email }}
              </div>
            @endif
            @if($p->telepon)
              <div class="small mb-2">
                <i class="fa-solid fa-phone me-1"></i>{{ $p->telepon }}
              </div>
            @endif

            <div class="d-flex justify-content-between align-items-center">
              <a href="{{ route('perusahaan.biodata.show', $p->id) }}"
                 class="btn btn-sm btn-outline-primary">
                <i class="fa-regular fa-eye me-1"></i>Lihat
              </a>
              @if($p->website)
                <a href="{{ $p->website }}" target="_blank" rel="noopener"
                   class="small text-decoration-none">
                  <i class="fa-solid fa-globe me-1"></i>Website
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-5">
        Belum ada data perusahaan.
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  <div class="mt-3 d-flex justify-content-end">
    {{ $perusahaans->links() }}
  </div>
</div>
@endsection
