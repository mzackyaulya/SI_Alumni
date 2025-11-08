@extends('layout.main')

@section('title','Tambah Data Perusahaan')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0">Tambah Data Perusahaan</h4>
            <a href="{{ route('perusahaan.index') }}" class="btn btn-light btn-sm">
              <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
          </div>

          {{-- Flash success --}}
          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Global validation summary (opsional) --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <div class="fw-semibold mb-1">Periksa kembali isian berikut:</div>
              <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('perusahaan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- ====== Kredensial Akun Company ====== --}}
            <div class="border rounded-3 p-3 p-md-4 mb-4">
              <div class="d-flex align-items-center mb-3 gap-2">
                <i class="fa-solid fa-user-shield"></i>
                <h6 class="mb-0">Kredensial Akun (Role: Company)</h6>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="email" class="form-label">Email Login <span class="text-danger">*</span></label>
                  <input type="email" name="email" id="email"
                         class="form-control @error('email') is-invalid @enderror"
                         value="{{ old('email') }}" placeholder="email@perusahaan.com" required>
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="form-text">Akan menjadi email login untuk akun perusahaan (company).</div>
                </div>

                <div class="col-md-3">
                  <label for="password" class="form-label">Password (opsional)</label>
                  <div class="input-group">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter">
                    <button class="btn btn-outline-secondary" type="button" id="togglePass">
                      <i class="fa-regular fa-eye"></i>
                    </button>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                  </div>
                  <div class="form-text">Kosongkan bila ingin dibuat otomatis oleh sistem.</div>
                </div>

                <div class="col-md-3">
                  <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                  <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Konfirmasi">
                    <button class="btn btn-outline-secondary" type="button" id="togglePass2">
                      <i class="fa-regular fa-eye"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            {{-- ====== Informasi Perusahaan ====== --}}
            <div class="border rounded-3 p-3 p-md-4 mb-4">
              <div class="d-flex align-items-center mb-3 gap-2">
                <i class="fa-solid fa-building"></i>
                <h6 class="mb-0">Informasi Perusahaan</h6>
              </div>

              <div class="row g-3">
                <div class="col-md-8">
                  <label for="nama" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                  <input type="text" name="nama" id="nama"
                         class="form-control @error('nama') is-invalid @enderror"
                         value="{{ old('nama') }}" required>
                  @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                  <label for="industri" class="form-label">Industri</label>
                  <input type="text" name="industri" id="industri"
                         class="form-control @error('industri') is-invalid @enderror"
                         value="{{ old('industri') }}" placeholder="Teknologi / F&B / Retail">
                  @error('industri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="website" class="form-label">Website</label>
                  <input type="url" name="website" id="website"
                         class="form-control @error('website') is-invalid @enderror"
                         value="{{ old('website') }}" placeholder="https://www.nama-perusahaan.com">
                  @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                  <label for="telepon" class="form-label">Telepon</label>
                  <input type="text" name="telepon" id="telepon"
                         class="form-control @error('telepon') is-invalid @enderror"
                         value="{{ old('telepon') }}" placeholder="0812xxxxxxx">
                  @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                  <label for="kota" class="form-label">Kota</label>
                  <input type="text" name="kota" id="kota"
                         class="form-control @error('kota') is-invalid @enderror"
                         value="{{ old('kota') }}" placeholder="Palembang">
                  @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                  <label for="alamat" class="form-label">Alamat Lengkap</label>
                  <textarea name="alamat" id="alamat" rows="3"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap perusahaan">{{ old('alamat') }}</textarea>
                  @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- ====== Berkas & Legalitas ====== --}}
            <div class="border rounded-3 p-3 p-md-4 mb-4">
              <div class="d-flex align-items-center mb-3 gap-2">
                <i class="fa-solid fa-file-shield"></i>
                <h6 class="mb-0">Berkas & Legalitas</h6>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="logo" class="form-label">Logo Perusahaan</label>
                  <input type="file" name="logo" id="logo"
                         class="form-control @error('logo') is-invalid @enderror"
                         accept="image/*">
                  @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="form-text">JPG/PNG • Maks 2MB.</div>
                </div>

                <div class="col-md-6">
                  <label for="dokumen_legal" class="form-label">Dokumen Legal (opsional)</label>
                  <input type="file" name="dokumen_legal" id="dokumen_legal"
                         class="form-control @error('dokumen_legal') is-invalid @enderror"
                         accept=".pdf,.doc,.docx,image/*">
                  @error('dokumen_legal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="form-text">PDF/DOC/Gambar • Maks 4MB.</div>
                </div>

                <div class="col-md-6">
                  <label for="npwp" class="form-label">NPWP (opsional)</label>
                  <input type="text" name="npwp" id="npwp"
                         class="form-control @error('npwp') is-invalid @enderror"
                         value="{{ old('npwp') }}">
                  @error('npwp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="siup" class="form-label">SIUP (opsional)</label>
                  <input type="text" name="siup" id="siup"
                         class="form-control @error('siup') is-invalid @enderror"
                         value="{{ old('siup') }}">
                  @error('siup') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- ====== Submit ====== --}}
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('perusahaan.index') }}" class="btn btn-light">
                Batal
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- tips kecil --}}
      <div class="text-muted small mt-3">
        <i class="fa-regular fa-circle-question me-1"></i>
        Kosongkan password untuk membiarkan sistem membuat password otomatis (atau aktifkan reset password via email di controller).
      </div>
    </div>
  </div>
</div>

{{-- Toggle show/hide password --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const pass = document.getElementById('password');
    const pass2 = document.getElementById('password_confirmation');
    const t1 = document.getElementById('togglePass');
    const t2 = document.getElementById('togglePass2');

    function toggle(el, btn){
      if(!el || !btn) return;
      btn.addEventListener('click', function(){
        const isText = el.type === 'text';
        el.type = isText ? 'password' : 'text';
        const icon = this.querySelector('i');
        if(icon){
          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
        }
      });
    }

    toggle(pass, t1);
    toggle(pass2, t2);
  });
</script>
@endsection
