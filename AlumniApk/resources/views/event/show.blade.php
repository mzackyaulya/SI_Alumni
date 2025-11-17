@extends('layout.main')

@section('title', $event->title)

@section('content')
<div class="container py-4" style="max-width: 900px;">
  <a href="{{ route('event.index') }}" class="btn btn-light mb-3">
    <i class="fa-solid fa-arrow-left me-1"></i>Kembali
  </a>

  <div class="card shadow-sm border-0">
    <div class="card-body p-4">
      <h2 class="fw-bold mb-2">{{ $event->title }}</h2>

      @php
        $start = optional($event->start_at)->timezone(config('app.timezone'))->format('d M Y, H:i');
        $end   = $event->end_at ? $event->end_at->timezone(config('app.timezone'))->format('d M Y, H:i') : null;
        $when  = $start . ($end ? ' — ' . $end : '');
      @endphp

      <div class="small text-muted mb-3">
        <i class="fa-regular fa-calendar me-1"></i>{{ $when }}
        @if($event->location)
          <span class="mx-2">•</span>
          <i class="fa-solid fa-location-dot me-1"></i>{{ $event->location }}
        @endif
      </div>

      @if(!$event->is_published)
        <div class="alert alert-warning py-2">Event ini belum dipublikasikan.</div>
      @endif

      <div class="mt-3">
        {!! nl2br(e($event->deskripsi)) !!}
      </div>
    </div>
  </div>
</div>
@endsection
