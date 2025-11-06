@extends('layout.main')

@section('title', 'Detail Data Alumni')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4" style="background-color:#f8faff;">

                    {{-- Foto Profil --}}
                    @php
                        use Illuminate\Support\Facades\Storage;
                        $fotoPath = $alumni->foto
                            ? Storage::disk('public')->url($alumni->foto)
                            : asset('assets/img/profil.jpg');
                    @endphp

                    <img src="{{ $fotoPath }}" alt="{{ $alumni->nama }}"
                        class="rounded-circle border border-3 border-primary shadow-sm mb-3"
                        style="width:150px;height:150px;object-fit:cover;">

                    <h4 class="fw-bold mb-1">{{ $alumni->nama }}</h4>
                    <p class="text-muted mb-0">{{ $alumni->email }}</p>
                    <p class="text-muted small">{{ $alumni->phone ?? '-' }}</p>
                </div>

                <div class="card-body px-4 py-4">
                    <h5 class="fw-semibold mb-3 text-primary">📋 Informasi Pribadi</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width:35%">NIS</th>
                                    <td>{{ $alumni->nis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">NISN</th>
                                    <td>{{ $alumni->nisn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tempat, Tanggal Lahir</th>
                                    <td>
                                        {{ $alumni->tempat_lahir ?? '-' }},
                                        {{ $alumni->tanggal_lahir ? \Carbon\Carbon::parse($alumni->tanggal_lahir)->format('d M Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Alamat</th>
                                    <td>{{ $alumni->alamat ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="fw-semibold mt-4 mb-3 text-primary">🎓 Riwayat Pendidikan</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width:35%">Jurusan</th>
                                    <td>{{ $alumni->jurusan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Angkatan</th>
                                    <td>{{ $alumni->angkatan ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="fw-semibold mt-4 mb-3 text-primary">💼 Pekerjaan / Kuliah</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width:35%">Pekerjaan / Status</th>
                                    <td>{{ $alumni->pekerjaan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Perusahaan / Instansi</th>
                                    <td>{{ $alumni->perusahaan ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('alumni.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>

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
