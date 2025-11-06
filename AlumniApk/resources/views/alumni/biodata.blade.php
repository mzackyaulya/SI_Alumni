@extends('layout.main')

@section('title','Biodata Alumni')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-4">Biodata Alumni</h4>

        {{-- Search --}}
        <form method="GET" class="mb-4">
            <div class="input-group" style="max-width:400px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama, jurusan, angkatan..."
                        value="{{ $q ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>

        {{-- Grid Biodata --}}
        <div class="row g-4">
            @forelse ($alumni as $a)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border border-primary rounded-3">
                    <div class="text-center mt-3">
                        @php
                            $fotoPath = $a->foto
                            ? Storage::disk('public')->url($a->foto)
                            : asset('assets/img/profil.jpg');
                        @endphp

                        <img src="{{ $fotoPath }}" alt="{{ $a->nama }}"
                            class="rounded-circle border border-2 border-primary shadow-sm"
                            style="width:130px;height:130px;object-fit:cover;">
                    </div>

                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold mt-2 mb-1">{{ $a->nama }}</h5>
                        <p class="text-muted small mb-1">{{ $a->email }}</p>
                        <p class="text-muted small mb-1">{{ $a->tanggal_lahir ?? 'HH/BB/TT' }}</p>
                        <p class="mb-1">
                            <i class="fa-solid fa-graduation-cap me-2 text-primary"></i>
                            {{ $a->jurusan ?? '-' }} ({{ $a->angkatan ?? '-' }})
                        </p>
                        <p class="mb-0">
                            <i class="fa-solid fa-briefcase me-2 text-success"></i>
                            {{ $a->pekerjaan ?? '-' }}{{ $a->perusahaan ? ' di '.$a->perusahaan : '' }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-regular fa-circle-xmark fa-2x mb-2"></i><br>
                Tidak ada data alumni ditemukan.
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $alumni->links() }}
        </div>
    </div>
@endsection
