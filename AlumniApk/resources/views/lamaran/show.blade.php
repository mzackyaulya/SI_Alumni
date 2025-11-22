@extends('layout.main')

@section('title', 'Detail Lamaran')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                <h4 class="fw-bold mb-3">Detail Lamaran</h4>

                @php
                    $statusColors = [
                    'submitted' => 'primary',
                    'review'    => 'info',
                    'shortlist' => 'success',
                    'interview' => 'warning',
                    'accepted'  => 'success',
                    'rejected'  => 'danger',
                    'withdrawn' => 'secondary'
                    ];
                @endphp

                {{-- STATUS --}}
                <div class="mb-3">
                    <span class="badge badge-{{ $statusColors[$lamaran->status] ?? 'secondary' }} px-3 py-2">
                    {{ ucfirst($lamaran->status) }}
                    </span>

                    @if($lamaran->jadwal_interview)
                    <div class="mt-2 text-muted">
                        <i class="fa-solid fa-calendar me-1"></i>
                        Interview: {{ $lamaran->jadwal_interview->format('d M Y H:i') }}
                    </div>
                    @endif
                </div>

                <hr>

                {{-- LOWONGAN --}}
                <h5 class="fw-semibold">Lowongan</h5>
                <p class="mb-1">
                    <strong>{{ $lamaran->lowongan->judul }}</strong>
                </p>
                <div class="text-muted mb-3">
                    {{ optional($lamaran->lowongan->perusahaan)->nama }}
                    • {{ $lamaran->lowongan->lokasi }}
                </div>

                <hr>

                {{-- DATA PELAMAR --}}
                <h5 class="fw-semibold">Data Pelamar</h5>
                <div class="mb-3">
                    <p class="m-0"><strong>{{ $lamaran->alumni->nama }}</strong></p>
                    <p class="m-0 text-muted">{{ optional($lamaran->alumni->user)->email }}</p>
                </div>

                <hr>

                {{-- BERKAS --}}
                <h5 class="fw-semibold">Berkas Lamaran</h5>

                <div class="mb-2">
                    <i class="fa-solid fa-file-lines me-2"></i>
                    <strong>CV:</strong>
                    <a href="{{ asset('storage/'.$lamaran->cv_path) }}" target="_blank" class="ms-1">
                    Download
                    </a>
                </div>

                @if($lamaran->surat_lamaran_path)
                    <div class="mb-2">
                    <i class="fa-solid fa-file me-2"></i>
                    <strong>Surat Lamaran:</strong>
                    <a href="{{ asset('storage/'.$lamaran->surat_lamaran_path) }}" target="_blank" class="ms-1">
                        Download
                    </a>
                    </div>
                @endif

                @if($lamaran->portfolio_url)
                    <div class="mb-2">
                    <i class="fa-solid fa-link me-2"></i>
                    <strong>Portfolio:</strong>
                    <a href="{{ $lamaran->portfolio_url }}" target="_blank">
                        {{ $lamaran->portfolio_url }}
                    </a>
                    </div>
                @endif

                <hr>

                {{-- CATATAN --}}
                @if($lamaran->catatan)
                <h5 class="fw-semibold">Catatan Pelamar</h5>
                <p class="text-muted">{{ $lamaran->catatan }}</p>
                <hr>
                @endif

                {{-- ACTIONS --}}
                <div class="d-flex justify-content-between">

                    <button type="button" class="btn btn-light" onclick="window.history.back()">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </button>

                    {{-- Alumni bisa batalkan --}}
                    @if(auth()->user()->role === 'alumni' && !in_array($lamaran->status, ['withdrawn','accepted','rejected']))
                    <form method="POST" action="{{ route('lamaran.withdraw', $lamaran) }}"
                            onsubmit="return confirm('Batalkan lamaran ini?')">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-danger">
                        <i class="fa-solid fa-xmark me-1"></i> Batalkan
                        </button>
                    </form>
                    @endif
                </div>


                {{-- COMPANY UPDATE --}}
                @if(auth()->user()->role === 'company')
                    <hr class="mt-4">

                    <h5 class="fw-semibold mb-3">Update Status Lamaran</h5>

                    <form action="{{ route('lamaran.updateStatus', $lamaran) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                            @foreach(['submitted','review','shortlist','interview','accepted','rejected'] as $s)
                                <option value="{{ $s }}" @selected($lamaran->status == $s)>
                                {{ ucfirst($s) }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jadwal Interview (opsional)</label>
                            <input type="datetime-local" name="jadwal_interview"
                                value="{{ $lamaran->jadwal_interview ? $lamaran->jadwal_interview->format('Y-m-d\TH:i') : '' }}"
                                class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan HR (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3">{{ $lamaran->catatan }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                        </div>
                    </form>
                @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
