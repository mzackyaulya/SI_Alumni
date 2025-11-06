@extends('layout.main')

@section('title', 'Edit Data Alumni')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Edit Data Alumni</h4>

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('alumni.update', $alumni->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Baris 1: NIS & NISN --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input type="text" id="nis" name="nis"
                  class="form-control @error('nis') is-invalid @enderror"
                  value="{{ old('nis', $alumni->nis) }}" required>
                @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="nisn" class="form-label">NISN</label>
                <input type="text" id="nisn" name="nisn"
                  class="form-control @error('nisn') is-invalid @enderror"
                  value="{{ old('nisn', $alumni->nisn) }}" required>
                @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Baris 2: Nama & Email --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama"
                  class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $alumni->nama) }}" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email"
                  class="form-control @error('email') is-invalid @enderror"
                  value="{{ old('email', $alumni->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Baris 3: Tempat & Tanggal Lahir --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="tempat_lahir" class="form-label">Tempat Lahir (opsional)</label>
                <input type="text" id="tempat_lahir" name="tempat_lahir"
                  class="form-control @error('tempat_lahir') is-invalid @enderror"
                  value="{{ old('tempat_lahir', $alumni->tempat_lahir) }}">
                @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir (opsional)</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                  class="form-control @error('tanggal_lahir') is-invalid @enderror"
                  value="{{ old('tanggal_lahir', $alumni->tanggal_lahir) }}">
                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Baris 4: Phone & Angkatan --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Nomor Handphone (opsional)</label>
                <input type="text" id="phone" name="phone"
                  class="form-control @error('phone') is-invalid @enderror"
                  value="{{ old('phone', $alumni->phone) }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="angkatan" class="form-label">Angkatan (opsional)</label>
                <input type="text" id="angkatan" name="angkatan"
                  class="form-control @error('angkatan') is-invalid @enderror"
                  value="{{ old('angkatan', $alumni->angkatan) }}">
                @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Baris 5: Jurusan & Pekerjaan --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="jurusan" class="form-label">Jurusan (opsional)</label>
                <input type="text" id="jurusan" name="jurusan"
                  class="form-control @error('jurusan') is-invalid @enderror"
                  value="{{ old('jurusan', $alumni->jurusan) }}">
                @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="pekerjaan" class="form-label">Pekerjaan/Kuliah (opsional)</label>
                <input type="text" id="pekerjaan" name="pekerjaan"
                  class="form-control @error('pekerjaan') is-invalid @enderror"
                  value="{{ old('pekerjaan', $alumni->pekerjaan) }}">
                @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Baris 6: Perusahaan & Alamat --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="perusahaan" class="form-label">Perusahaan (opsional)</label>
                <input type="text" id="perusahaan" name="perusahaan"
                  class="form-control @error('perusahaan') is-invalid @enderror"
                  value="{{ old('perusahaan', $alumni->perusahaan) }}">
                @error('perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="alamat" class="form-label">Alamat (opsional)</label>
                <textarea id="alamat" name="alamat" rows="1"
                  class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $alumni->alamat) }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Foto: preview + hapus/ganti --}}
            <div class="mb-3">
              <label class="form-label d-block">Foto (opsional)</label>

              <div class="d-flex align-items-center gap-3 flex-wrap">
                <img
                  src="{{ $alumni->foto ? asset('storage/'.$alumni->foto) : asset('assets/img/profil.jpg') }}"
                  alt="foto"
                  style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb">
                <div class="flex-grow-1">
                  <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                  @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror

                  {{-- centang ini jika ingin menghapus foto tanpa upload baru --}}
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="1" id="hapus_foto" name="hapus_foto">
                    <label class="form-check-label" for="hapus_foto">
                      Hapus foto saat ini
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('alumni.index') }}" class="btn btn-light">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
