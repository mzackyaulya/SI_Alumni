@extends('layout.main')

@section('title', 'Edit Data Alumni')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-3 fw-bold">Edit Data Alumni</h4>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('alumni.update', $alumni->id) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- simpan URL asal agar bisa redirect balik --}}
                        <input type="hidden" name="return_url" value="{{ url()->previous() }}">

                        {{-- Baris 1: NIS & NISN --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" id="nis" name="nis"
                                    class="form-control @error('nis') is-invalid @enderror"
                                    value="{{ old('nis', $alumni->nis) }}" required>
                                @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="text" id="nisn" name="nisn"
                                    class="form-control @error('nisn') is-invalid @enderror"
                                    value="{{ old('nisn', $alumni->nisn) }}" required>
                                @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 2: Nama & Email --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="nama" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $alumni->nama) }}" required>
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $alumni->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 3: Jenis Kelamin & Phone --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin"
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">— Pilih —</option>
                                <option value="L" {{ old('jenis_kelamin', $alumni->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $alumni->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">No. HP</label>
                                <input type="text" id="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $alumni->phone) }}" placeholder="08xxxxxxxxxx">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 4: Tempat & Tanggal Lahir --}}
                        @php
                            $tgl = old('tanggal_lahir', $alumni->tanggal_lahir);
                            try { if($tgl) $tgl = \Illuminate\Support\Carbon::parse($tgl)->format('Y-m-d'); } catch (\Throwable $e) {}
                        @endphp
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir"
                                    class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    value="{{ old('tempat_lahir', $alumni->tempat_lahir) }}" placeholder="Kota lahir">
                                @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ $tgl }}">
                                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 5: Angkatan & Jurusan --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <select id="angkatan" name="angkatan"
                                        class="form-select @error('angkatan') is-invalid @enderror">
                                <option value="">— Pilih —</option>
                                </select>
                                @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <select id="jurusan" name="jurusan"
                                        class="form-select @error('jurusan') is-invalid @enderror">
                                <option value="">— Pilih Jurusan —</option>
                                <option value="Teknik Komputer dan Jaringan"
                                    {{ old('jurusan', $alumni->jurusan) == 'Teknik Komputer dan Jaringan' ? 'selected' : '' }}>
                                    Teknik Komputer dan Jaringan
                                </option>
                                <option value="Teknik Kendaraan Ringan"
                                    {{ old('jurusan', $alumni->jurusan) == 'Teknik Kendaraan Ringan' ? 'selected' : '' }}>
                                    Teknik Kendaraan Ringan
                                </option>
                                <option value="Teknik Instalasi Tenaga Listrik"
                                    {{ old('jurusan', $alumni->jurusan) == 'Teknik Instalasi Tenaga Listrik' ? 'selected' : '' }}>
                                    Teknik Instalasi Tenaga Listrik
                                </option>
                                </select>
                                @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 6: Pekerjaan & Perusahaan --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan" class="form-label">Pekerjaan/Kuliah</label>
                                <input type="text" id="pekerjaan" name="pekerjaan"
                                    class="form-control @error('pekerjaan') is-invalid @enderror"
                                    value="{{ old('pekerjaan', $alumni->pekerjaan) }}">
                                @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="perusahaan" class="form-label">Perusahaan/Instansi</label>
                                <input type="text" id="perusahaan" name="perusahaan"
                                    class="form-control @error('perusahaan') is-invalid @enderror"
                                    value="{{ old('perusahaan', $alumni->perusahaan) }}">
                                @error('perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 7: Nama Ortu & STTP --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
                                <input type="text" id="nama_ortu" name="nama_ortu"
                                    class="form-control @error('nama_ortu') is-invalid @enderror"
                                    value="{{ old('nama_ortu', $alumni->nama_ortu) }}">
                                @error('nama_ortu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sttp" class="form-label">STTP Nomor/Tahun</label>
                                <input type="text" id="sttp" name="sttp"
                                    class="form-control @error('sttp') is-invalid @enderror"
                                    value="{{ old('sttp', $alumni->sttp) }}" placeholder="mis. 1234/2020">
                                @error('sttp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Baris 8: Alamat --}}
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        placeholder="Masukkan alamat lengkap">{{ old('alamat', $alumni->alamat) }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Foto: preview + ganti/hapus --}}
                        <div class="mb-3">
                            <label class="form-label d-block">Foto</label>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <img
                                src="{{ $alumni->foto ? asset('storage/'.$alumni->foto) : asset('assets/img/profil.jpg') }}"
                                id="preview-foto"
                                alt="foto"
                                style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:1px solid #eee">
                                <div class="flex-grow-1">
                                <input type="file" id="foto" name="foto"
                                        class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                @if($alumni->foto)
                                    <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="hapus_foto" name="hapus_foto">
                                    <label class="form-check-label" for="hapus_foto">Hapus foto saat ini</label>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('alumni.show', $alumni->id) }}" class="btn btn-light">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Helper Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Dropdown angkatan
  const select = document.getElementById('angkatan');
  if (select) {
    const current = new Date().getFullYear();
    const start = current - 25;
    const oldVal = @json(old('angkatan', (string)$alumni->angkatan));
    for (let y = current; y >= start; y--) {
      const opt = document.createElement('option');
      opt.value = y;
      opt.textContent = y;
      if (String(oldVal) === String(y)) opt.selected = true;
      select.appendChild(opt);
    }
  }

  // Preview foto saat pilih baru
  const inputFoto = document.getElementById('foto');
  const preview = document.getElementById('preview-foto');
  if (inputFoto && preview) {
    inputFoto.addEventListener('change', function (e) {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      preview.src = URL.createObjectURL(file);
    });
  }
});
</script>
@endsection
