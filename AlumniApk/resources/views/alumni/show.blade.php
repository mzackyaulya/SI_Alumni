@extends('layout.main')

@section('title', 'Detail Data Alumni')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    // helper kecil untuk fallback "-"
    $d = fn($v) => filled($v) ? $v : '—';

    // foto
    $fotoUrl = $alumni->foto ? Storage::disk('public')->url($alumni->foto) : asset('assets/img/profil.jpg');

    // tanggal lahir -> d M Y aman meski null/string
    $tgl = $alumni->tanggal_lahir;
    try { $tgl = $tgl ? \Illuminate\Support\Carbon::parse($tgl)->format('d M Y') : '—'; } catch (\Throwable $e) { $tgl = '—'; }

    // jenis kelamin label
    $jkLabel = $alumni->jenis_kelamin === 'L' ? 'Laki-laki' : ($alumni->jenis_kelamin === 'P' ? 'Perempuan' : '—');
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                {{-- Header profil --}}
                <div class="card-body text-center" style="background:#f7f9ff;">
                    <img src="{{ $fotoUrl }}" alt="{{ $alumni->nama }}"
                        class="rounded-circle border border-3 border-primary shadow-sm mb-3"
                        style="width:140px;height:140px;object-fit:cover;">
                    <h4 class="fw-bold mb-1">{{ $alumni->nama }}</h4>

                    <div class="d-flex flex-wrap gap-2 justify-content-center mt-2">
                        <span class="badge rounded-pill text-bg-light text-dark border">
                            <i class="fa-solid fa-venus-mars me-1"></i>{{ $jkLabel }}
                        </span>
                        @if(filled($alumni->angkatan))
                            <span class="badge rounded-pill text-bg-light text-dark border">
                                <i class="fa-solid fa-graduation-cap me-1 text-dark"></i>Angkatan {{ $alumni->angkatan }}
                            </span>
                        @endif
                        @if(filled($alumni->jurusan))
                            <span class="badge rounded-pill text-bg-light text-dark border">
                                <i class="fa-solid fa-diagram-project me-1"></i>{{ $alumni->jurusan }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-2 small text-muted">
                        <i class="fa-solid fa-envelope me-1"></i>
                        <a href="mailto:{{ $alumni->email }}">{{ $alumni->email }}</a>
                        @if(filled($alumni->phone))
                            <span class="mx-1">•</span>
                            <i class="fa-solid fa-phone me-1"></i>
                            <a href="tel:{{ $alumni->phone }}">{{ $alumni->phone }}</a>
                            <span class="mx-1">/</span>
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $alumni->phone) }}" target="_blank">WhatsApp</a>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Grid 2 kolom responsif --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-primary fw-semibold mb-3">
                                <i class="fa-solid fa-id-card-clip me-2"></i>Identitas
                                </h6>
                                <dl class="row mb-0 small">
                                <dt class="col-5 text-muted">NIS</dt><dd class="col-7">{{ $d($alumni->nis) }}</dd>
                                <dt class="col-5 text-muted">NISN</dt><dd class="col-7">{{ $d($alumni->nisn) }}</dd>
                                <dt class="col-5 text-muted">STTP</dt><dd class="col-7">{{ $d($alumni->sttp) }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-primary fw-semibold mb-3">
                                <i class="fa-solid fa-cake-candles me-2"></i>Tempat & Tanggal Lahir
                                </h6>
                                <div class="small">
                                {{ $d($alumni->tempat_lahir) }}, {{ $tgl }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-primary fw-semibold mb-3">
                                <i class="fa-solid fa-user-group me-2"></i>Keluarga
                                </h6>
                                <dl class="row mb-0 small">
                                <dt class="col-5 text-muted">Nama Orang Tua</dt>
                                <dd class="col-7">{{ $d($alumni->nama_ortu) }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-primary fw-semibold mb-3">
                                <i class="fa-solid fa-location-dot me-2"></i>Alamat
                                </h6>
                                <div class="small">{{ $d($alumni->alamat) }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-primary fw-semibold mb-3">
                                <i class="fa-solid fa-graduation-cap me-2"></i>Pendidikan
                                </h6>
                                <dl class="row mb-0 small">
                                <dt class="col-5 text-muted">Jurusan</dt>
                                <dd class="col-7">{{ $d($alumni->jurusan) }}</dd>
                                <dt class="col-5 text-muted">Angkatan</dt>
                                <dd class="col-7">{{ $d($alumni->angkatan) }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-primary fw-semibold mb-3">
                                <i class="fa-solid fa-briefcase me-2"></i>Pekerjaan / Kuliah
                                </h6>
                                <dl class="row mb-0 small">
                                <dt class="col-5 text-muted">Status</dt>
                                <dd class="col-7">{{ $d($alumni->pekerjaan) }}</dd>
                                <dt class="col-5 text-muted">Perusahaan/Instansi</dt>
                                <dd class="col-7">{{ $d($alumni->perusahaan) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('alumni.index') }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                            </a>
                        @endif
                        <a href="{{ route('alumni.edit', $alumni->id) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
