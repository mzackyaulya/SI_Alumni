@extends('layout.main')

@section('title','Kelola Event')

@section('content')
<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Kelola Event</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.event.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-1"></i>Event Baru
      </a>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Filter --}}
  <form method="GET" class="card shadow-sm border-0 mb-3">
    <div class="card-body row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Pencarian</label>
        <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Judul, lokasi, isi...">
      </div>
      <div class="col-md-3">
        <label class="form-label">Mulai dari</label>
        <input type="date" name="start_from" class="form-control" value="{{ $range['start_from'] ?? '' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Sampai</label>
        <input type="date" name="start_to" class="form-control" value="{{ $range['start_to'] ?? '' }}">
      </div>
      <div class="col-md-2">
        <label class="form-label">Publikasi</label>
        <select name="published" class="form-select">
          <option value="">Semua</option>
          <option value="1" @selected(($pub ?? '')==='1')>Published</option>
          <option value="0" @selected(($pub ?? '')==='0')>Draft</option>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-outline-primary"><i class="fa fa-filter me-1"></i> Terapkan</button>
      </div>
    </div>
  </form>

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:50px;">#</th>
            <th>Judul</th>
            <th style="width:220px;">Waktu</th>
            <th style="width:180px;">Lokasi</th>
            <th style="width:120px;">Status</th>
            <th style="width:220px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($events as $i => $e)
          @php
            $start = optional($e->start_at)->timezone(config('app.timezone'))->format('d M Y, H:i');
            $end   = $e->end_at ? $e->end_at->timezone(config('app.timezone'))->format('d M Y, H:i') : null;
          @endphp
          <tr>
            <td>{{ $events->firstItem() + $i }}</td>
            <td>
              <div class="fw-semibold">{{ $e->title }}</div>
            </td>
            <td>
              <div>{{ $start }}</div>
              @if($end)<div class="text-muted small">s/d {{ $end }}</div>@endif
            </td>
            <td>{{ $e->location ?? '—' }}</td>
            <td>
              @if($e->is_published)
                <span class="badge text-success">Published</span>
              @else
                <span class="badge text-secondary">Draft</span>
              @endif
            </td>
            <td>
              <a class="btn btn-sm btn-light" href="{{ route('events.show', $e) }}" target="_blank">
                <i class="fa-regular fa-eye me-1"></i>Preview
              </a>
              <a class="btn btn-sm btn-warning" href="{{ route('admin.event.edit', $e) }}">
                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
              </a>

              <form action="{{ route('admin.event.toggle', $e) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-sm {{ $e->is_published ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                  <i class="fa-solid fa-toggle-{{ $e->is_published ? 'on' : 'off' }} me-1"></i>
                  {{ $e->is_published ? 'Unpublish' : 'Publish' }}
                </button>
              </form>

              <form action="{{ route('admin.event.destroy', $e) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus event ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="fa-solid fa-trash me-1"></i>Hapus
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $events->links() }}
    </div>
  </div>
</div>
@endsection
