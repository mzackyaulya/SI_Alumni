@extends('layout.main')

@section('title','Lowongan Kerja')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h3 class="mb-2 fw-bold">Lowongan Kerja</h3>

        {{-- tombol buat lowongan (admin & company) --}}
        @auth
            @php $role = auth()->user()->role ?? null; @endphp
            @if(in_array($role, ['admin','company']))
                <a href="{{ route('lowongan.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Buat Lowongan
                </a>
            @endif
        @endauth
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('lowongan.index') }}" class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" name="q" class="form-control" placeholder="Cari judul / lokasi / tag…"
                            value="{{ $filter['q'] ?? '' }}">
                </div>
                <div class="col-6 col-md-2">
                    <select name="tipe" class="form-select">
                        <option value="">Tipe</option>
                        @foreach(['fulltime','parttime','intern','contract'] as $t)
                            <option value="{{ $t }}" {{ ($filter['tipe']??'')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="level" class="form-select">
                        <option value="">Level</option>
                        @foreach(['junior','middle','senior'] as $l)
                        <option value="{{ $l }}" {{ ($filter['level']??'')===$l?'selected':'' }}>{{ ucfirst($l) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <input type="text" name="lokasi" class="form-control" placeholder="Lokasi"
                            value="{{ $filter['lokasi'] ?? '' }}">
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Cari</button>
                </div>
            </div>
        </div>
    </form>

  {{-- List Lowongan --}}
  @if($lowongans->count())
    <div class="row g-3">
      @foreach($lowongans as $l)
        @php
          $ownerId = optional($l->perusahaan)->user_id;
          $canManage = auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $ownerId);
        @endphp
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <h5 class="fw-semibold mb-1">
                <a href="{{ route('lowongan.show',$l) }}" class="text-decoration-none">
                  {{ $l->judul }}
                </a>
              </h5>
              <div class="small text-muted mb-2">
                {{ optional($l->perusahaan)->nama ?? '— Perusahaan' }}
              </div>

              <div class="d-flex flex-wrap gap-2 mb-2 small">
                @if($l->tipe)<span class="badge text-bg-light text-dark border">{{ ucfirst($l->tipe) }}</span>@endif
                @if($l->level)<span class="badge text-bg-light text-dark border">{{ ucfirst($l->level) }}</span>@endif
                @if($l->lokasi)<span class="badge text-bg-light text-dark border"><i class="fa-solid fa-location-dot me-1"></i>{{ $l->lokasi }}</span>@endif
                @if(!is_null($l->gaji_min) || !is_null($l->gaji_max))
                  <span class="badge text-bg-light text-dark border">
                    <i class="fa-solid fa-money-bill me-1"></i>
                    {{ is_null($l->gaji_min) ? '—' : 'Rp '.number_format($l->gaji_min,0,',','.') }}
                    –
                    {{ is_null($l->gaji_max) ? '—' : 'Rp '.number_format($l->gaji_max,0,',','.') }}
                  </span>
                @endif
                @if($l->deadline)
                    @php
                        // selisih hari dibulatkan (negatif kalau sudah lewat)
                        $selisih = now()->floatDiffInDays($l->deadline, false);
                        $selisih = (int) floor($selisih);
                    @endphp

                    <span class="badge
                        {{ $selisih < 0
                            ? 'text-bg-danger'
                            : ($selisih <= 3 ? 'text-bg-warning' : 'text-bg-light text-dark border') }}">
                        <i class="fa-regular fa-calendar me-1"></i>

                        @if($selisih < 0)
                        Tutup {{ $l->deadline->format('d M Y') }} (lewat)
                        @elseif($selisih == 0)
                        Tutup hari ini
                        @elseif($selisih == 1)
                        Tutup besok
                        @else
                        Tutup {{ $selisih }} hari lagi
                        @endif
                    </span>
                @endif
              </div>

              @if(is_array($l->tag) && count($l->tag))
                <div class="mb-2 small">
                  @foreach(array_slice($l->tag,0,5) as $t)
                    <span class="badge rounded-pill text-bg-secondary">{{ $t }}</span>
                  @endforeach
                </div>
              @endif

              <p class="mb-0 text-truncate-2" style="-webkit-line-clamp:3;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                {{ strip_tags($l->deskripsi) ?: '—' }}
              </p>
            </div>

            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
              <a href="{{ route('lowongan.show',$l) }}" class="btn btn-sm btn-outline-primary">Detail</a>

              @if($canManage)
                <div class="dropdown">
                  <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                    Kelola
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('lowongan.edit',$l) }}"><i class="fa-solid fa-pen-to-square me-2"></i>Edit</a></li>
                    <li>
                      <form action="{{ route('lowongan.destroy',$l) }}" method="POST"
                            onsubmit="return confirm('Hapus lowongan ini?')">
                        @csrf @method('DELETE')
                        <button class="dropdown-item text-danger"><i class="fa-solid fa-trash me-2"></i>Hapus</button>
                      </form>
                    </li>
                  </ul>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $lowongans->links() }}
    </div>
  @else
    <div class="text-center text-muted py-5">
      Tidak ada lowongan.
    </div>
  @endif
</div>
@endsection
