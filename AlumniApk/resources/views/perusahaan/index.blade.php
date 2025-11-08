{{-- resources/views/perusahaans/index.blade.php --}}
@extends('layout.main')

@section('title','Data Perusahaan')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="container py-4">

    {{-- Header + CTA --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-2">Data Perusahaan</h4>

        @if(auth()->user()->role === 'admin')
            <div class="d-flex gap-2">
                <a href="{{ route('perusahaan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Perusahaan
                </a>
            </div>
        @endif
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="q" class="form-control"
                           value="{{ $q ?? '' }}" placeholder="Nama / Industri / Kota">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Industri</label>
                    <input type="text" name="industri" class="form-control"
                           value="{{ $industri ?? '' }}" placeholder="IT / F&B / Manufaktur">
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="onlyVerified" name="verified" value="1"
                               {{ !empty($verified) ? 'checked' : '' }}>
                        <label class="form-check-label" for="onlyVerified">
                            Hanya yang Terverifikasi
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0 text-center">
                <thead class="table-primary">
                    <tr>
                        <th style="width:6%">No</th>
                        <th style="width:22%">Perusahaan</th>
                        <th>Industri</th>
                        <th>Kota</th>
                        <th>Kontak</th>
                        <th>Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perusahaans as $i => $p)
                        @php
                            $num = method_exists($perusahaans, 'firstItem')
                                ? $perusahaans->firstItem() + $i
                                : $i + 1;
                            $logo = ($p->logo && Storage::disk('public')->exists($p->logo))
                                ? Storage::disk('public')->url($p->logo)
                                : asset('assets/img/company.png');
                        @endphp
                        <tr>
                            <td>{{ $num }}</td>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $logo }}" alt="{{ $p->nama }}"
                                         class="rounded-circle border" style="width:34px;height:34px;object-fit:cover">
                                    <div class="text-start">
                                        <div class="fw-semibold">{{ $p->nama }}</div>
                                        @if($p->website)
                                            <a href="{{ $p->website }}" target="_blank" rel="noopener"
                                               class="small text-decoration-none">
                                                <i class="fa-solid fa-globe me-1"></i>Website
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->industri ?? '-' }}</td>
                            <td>{{ $p->kota ?? '-' }}</td>
                            <td class="text-start">
                                <div class="small">
                                    @if($p->email)
                                        <div><i class="fa-solid fa-envelope me-1"></i>{{ $p->email }}</div>
                                    @endif
                                    @if($p->telepon)
                                        <div><i class="fa-solid fa-phone me-1"></i>{{ $p->telepon }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($p->is_verified)
                                    <span class="badge bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown position-static">
                                    <button class="btn btn-sm btn-transpant border-0 d-inline-flex align-items-center justify-content-center"
                                            type="button" id="aksi{{ $p->id }}" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="width:34px;height:34px">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="aksi{{ $p->id }}" style="min-width: 180px;">
                                    {{-- Lihat (internal profil lengkap) --}}
                                    <li>
                                        <a class="dropdown-item" href="{{ route('perusahaan.show', $p->id) }}">
                                        <i class="fa-regular fa-eye me-2 text-primary"></i> Lihat
                                        </a>
                                    </li>

                                    {{-- Edit --}}
                                    <li>
                                        <a class="dropdown-item" href="{{ route('perusahaan.edit', $p->id) }}">
                                        <i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Edit
                                        </a>
                                    </li>

                                    @if(auth()->user()->role === 'admin')
                                        <li><hr class="dropdown-divider"></li>

                                        {{-- Toggle verifikasi --}}
                                        <li>
                                        <form action="{{ route('perusahaan.verify', $p->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                            <i class="fa-solid fa-check me-2 text-teal"></i>
                                            {{ $p->is_verified ? 'Batalkan Verifikasi' : 'Verifikasi' }}
                                            </button>
                                        </form>
                                        </li>

                                        {{-- Hapus --}}
                                        <li>
                                        <form action="{{ route('perusahaan.destroy', $p->id) }}" method="POST"
                                                class="m-0"
                                                onsubmit="return confirm('Hapus perusahaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa-solid fa-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                        </li>
                                    @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-4">
                                Belum ada data perusahaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="card-footer d-flex flex-wrap justify-content-between align-items-center">
            <div class="small text-muted mb-2 mb-md-0">
                @if($perusahaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    Menampilkan {{ $perusahaans->count() }} dari {{ $perusahaans->total() }} data
                @else
                    Total: {{ $perusahaans->count() }} data
                @endif
            </div>
            @if($perusahaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div>
                    {{ $perusahaans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
