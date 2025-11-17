@extends('layout.main')

@section('title','Event Alumni')

@section('content')
<div class="container py-4">

  {{-- Header: Judul + Aksi Admin + Pencarian --}}
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
    <div class="d-flex align-items-center gap-2">
      <h3 class="fw-bold mb-0">Event Alumni</h3>

      @auth
        @if(auth()->user()->role === 'admin')
          <div class="d-flex gap-2">
            <a href="{{ route('admin.event.create') }}" class="btn btn-primary btn-sm">
              <i class="fa fa-plus me-1"></i>Tambah Event
            </a>
            <a href="{{ route('admin.event.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="fa fa-gear me-1"></i>Kelola Event
            </a>
          </div>
        @endif
      @endauth
    </div>

    <form method="GET" class="w-80 w-md-auto">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Cari judul, lokasi..."
               value="{{ $q ?? '' }}">
        <button class="btn btn-primary">
          <i class="fa fa-search me-1"></i>Cari
        </button>
      </div>
    </form>
  </div>

  {{-- Flash success --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- List Event --}}
  @forelse($events as $e)
    @php
      $start = optional($e->start_at)->timezone(config('app.timezone'))->format('d M Y, H:i');
      $end   = $e->end_at ? $e->end_at->timezone(config('app.timezone'))->format('d M Y, H:i') : null;
      $when  = $start . ($end ? ' — ' . $end : '');
    @endphp

    <div class="card shadow-sm border-0 mb-3">
      <div class="card-body d-flex gap-3">
        {{-- Badge tanggal besar (desktop) --}}
        <div class="d-none d-md-flex align-items-center justify-content-center rounded bg-light"
             style="width:90px; min-width:90px;">
          <div class="text-center">
            <div class="fw-bold" style="font-size:28px;">
              {{ optional($e->start_at)->timezone(config('app.timezone'))->format('d') }}
            </div>
            <div class="text-uppercase small text-muted">
              {{ optional($e->start_at)->timezone(config('app.timezone'))->format('M') }}
            </div>
          </div>
        </div>

        <div class="flex-grow-1">
          <h5 class="mb-1">
            <a href="{{ route('events.show', $e) }}" class="text-decoration-none">{{ $e->title }}</a>
          </h5>

          <div class="small text-muted mb-2">
            <i class="fa-regular fa-calendar me-1"></i>{{ $when }}
            @if($e->location)
              <span class="mx-2">•</span>
              <i class="fa-solid fa-location-dot me-1"></i>{{ $e->location }}
            @endif
          </div>

          <p class="mb-0 text-secondary">
            {{ \Illuminate\Support\Str::limit(strip_tags($e->deskripsi ?? ''), 180) }}
          </p>
        </div>
      </div>
    </div>
  @empty
    <div class="text-center text-muted py-5">Belum ada event.</div>
  @endforelse

  {{-- Pagination --}}
  <div class="mt-3">
    {{ $events->links() }}
  </div>
</div>
@endsection
