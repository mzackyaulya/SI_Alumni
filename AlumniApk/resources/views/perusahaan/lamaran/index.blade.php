@extends('layout.main')

@section('title', 'Lamaran Masuk')

@section('content')
<div class="container py-4">
  <h4 class="fw-bold mb-3">Lamaran Masuk</h4>
  <p class="text-muted mb-4">
    Perusahaan: <strong>{{ $perusahaan->nama }}</strong>
  </p>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Lowongan</th>
            <th>Pelamar</th>
            <th>Status</th>
            <th>Dikirim</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($lamarans as $l)
          @php
            $statusColors = [
                'submitted' => 'bg-primary text-white',
                'review'    => 'bg-info text-dark',
                'shortlist' => 'bg-primary text-white',
                'interview' => 'bg-warning text-dark',
                'accepted'  => 'bg-success text-white',
                'rejected'  => 'bg-danger text-white',
                'withdrawn' => 'bg-secondary text-white',
            ];
          @endphp
          <tr>
            {{-- Lowongan --}}
            <td>
              <div class="fw-semibold">{{ $l->lowongan->judul }}</div>
              <div class="small text-muted">{{ $l->lowongan->lokasi }}</div>
            </td>

            {{-- Pelamar --}}
            <td>
              <div class="fw-semibold">{{ $l->alumni->nama ?? '-' }}</div>
              <div class="small text-muted">
                {{ optional($l->alumni->user)->email }}
                @if(!empty($l->alumni->angkatan))
                  · Angkatan {{ $l->alumni->angkatan }}
                @endif
              </div>
            </td>

            {{-- Status --}}
            <td>
              <span class="badge {{ $statusColors[$l->status] ?? 'badge-secondary' }}">
                {{ ucfirst($l->status) }}
              </span>
              @if($l->jadwal_interview)
                <div class="small text-muted">
                  <i class="fa-regular fa-calendar me-1"></i>
                  Interview: {{ $l->jadwal_interview->format('d M Y H:i') }}
                </div>
              @endif
            </td>

            {{-- Tanggal kirim --}}
            <td>{{ $l->created_at->format('d M Y') }}</td>

            {{-- Aksi --}}
            <td class="text-center">
              {{-- Detail lamaran (pakai show.blade.php yang tadi kita buat) --}}
              <a href="{{ route('lamaran.show', $l) }}" class="btn btn-sm btn-outline-primary">
                Detail
              </a>

              {{-- Form update status cepat --}}
              <form action="{{ route('lamaran.updateStatus', $l) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                  @foreach(['submitted','review','shortlist','interview','accepted','rejected'] as $s)
                    <option value="{{ $s }}" @selected($l->status === $s)>{{ ucfirst($s) }}</option>
                  @endforeach
                </select>
                <button class="btn btn-sm btn-success ms-1">Update</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              Belum ada lamaran yang masuk.
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($lamarans, 'links'))
      <div class="card-footer">
        {{ $lamarans->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
