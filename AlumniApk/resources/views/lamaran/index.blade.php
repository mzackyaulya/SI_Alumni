@extends('layout.main')
@section('title','Riwayat Lamaran')

@section('content')
<div class="container py-4">
  <h4 class="fw-bold mb-3">Riwayat Lamaran Saya</h4>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Lowongan</th>
            <th>Perusahaan</th>
            <th>Status</th>
            <th>Dikirim</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($lamarans as $l)
          <tr>
            <td>
              <a href="{{ route('lowongan.show', $l->lowongan) }}" class="text-decoration-none">
                {{ $l->lowongan->judul }}
              </a>
              <div class="small text-muted">{{ $l->lowongan->lokasi }}</div>
            </td>
            <td>{{ optional($l->lowongan->perusahaan)->nama }}</td>
            <td>
              @php
                $map = [
                  'submitted'=>'secondary','review'=>'info','shortlist'=>'primary',
                  'interview'=>'warning','accepted'=>'success','rejected'=>'danger','withdrawn'=>'dark'
                ];
              @endphp
              <span class="badge text-bg-{{ $map[$l->status] ?? 'secondary' }}">{{ ucfirst($l->status) }}</span>
            </td>
            <td>{{ $l->created_at->format('d M Y') }}</td>
            <td class="text-end">
              <a href="{{ route('lamaran.show', $l) }}" class="btn btn-sm btn-outline-primary">Detail</a>
              @if($l->status !== 'withdrawn' && $l->status !== 'rejected' && $l->status !== 'accepted')
                <form action="{{ route('lamaran.withdraw', $l) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Batalkan lamaran ini?')">
                  @csrf @method('PATCH')
                  <button class="btn btn-sm btn-outline-danger">Batalkan</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-4">Belum ada lamaran.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if(method_exists($lamarans,'links'))
      <div class="card-footer">{{ $lamarans->links() }}</div>
    @endif
  </div>
</div>
@endsection
