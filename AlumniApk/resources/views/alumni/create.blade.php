@extends('layout.main')

@section('title', 'Tambah Data Alumni')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Tambah Data Alumni</h4>

          {{-- Pesan sukses --}}
          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Form tambah alumni --}}
          <form method="POST" action="{{ route('alumni.store') }}">
            @csrf

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="nisn" class="form-label">NISN</label>
                <input type="text" id="nisn" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                       value="{{ old('nisn') }}" required>
                @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Nomor Handphone</label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" required placeholder="0812xxxxxxx">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="angkatan" class="form-label">Angkatan</label>
                <input type="text" id="angkatan" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror"
                       value="{{ old('angkatan') }}" placeholder="2019 / 2020">
                @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <input type="text" id="jurusan" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror"
                       value="{{ old('jurusan') }}" placeholder="TKJ / RPL / AKL">
                @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="alamat" class="form-label">Alamat</label>
              <textarea id="alamat" name="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
              @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
              <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                     class="form-control @error('tanggal_lahir') is-invalid @enderror"
                     value="{{ old('tanggal_lahir') }}">
              @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('alumni.index') }}" class="btn btn-light">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
          </form>

          <div class="mt-3 small text-muted">
            Sistem akan membuat akun <strong>role = alumni</strong> dan mengirim
            <em>link setel password</em> ke email alumni secara otomatis.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
