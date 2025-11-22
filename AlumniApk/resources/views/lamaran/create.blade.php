@extends('layout.main')
@section('title','Lamar: '.$lowongan->judul)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-3">Lamar Pekerjaan</h4>
                    <p class="text-muted mb-4">
                        <strong>{{ $lowongan->judul }}</strong> — {{ optional($lowongan->perusahaan)->nama }} ({{ $lowongan->lokasi }})
                    </p>

                    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif

                    <form method="POST" action="{{ route('lamaran.store', $lowongan) }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">CV <span class="text-danger">*</span></label>
                            <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" required>
                            <div class="form-text">PDF/DOC/DOCX, maks 4MB.</div>
                            @error('cv') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Surat Lamaran</label>
                            <input type="file" name="surat_lamaran" class="form-control @error('surat_lamaran') is-invalid @enderror">
                            <div class="form-text">Opsional. PDF/DOC/DOCX, maks 4MB.</div>
                            @error('surat_lamaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URL Portofolio (opsional)</label>
                            <input type="url" name="portfolio_url" value="{{ old('portfolio_url') }}"
                                    class="form-control @error('portfolio_url') is-invalid @enderror" placeholder="https://...">
                            @error('portfolio_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lowongan.show', $lowongan) }}" class="btn btn-light">
                                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-paper-plane me-1"></i> Kirim Lamaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
