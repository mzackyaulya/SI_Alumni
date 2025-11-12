@extends('layout.main')

@section('title','Edit Data Perusahaan')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0">Edit Data Perusahaan</h4>
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

          <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

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
                         value="{{ old('nama', $perusahaan->nama) }}" required>
                  @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                  <label for="industri" class="form-label">Industri</label>
                  <input type="text" name="industri" id="industri"
                         class="form-control @error('industri') is-invalid @enderror"
                         value="{{ old('industri', $perusahaan->industri) }}" placeholder="Teknologi / F&B / Retail">
                  @error('industri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="website" class="form-label">Website</label>
                  <input type="url" name="website" id="website"
                         class="form-control @error('website') is-invalid @enderror"
                         value="{{ old('website', $perusahaan->website) }}" placeholder="https://www.nama-perusahaan.com">
                  @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                  <label for="telepon" class="form-label">Telepon</label>
                  <input type="text" name="telepon" id="telepon"
                         class="form-control @error('telepon') is-invalid @enderror"
                         value="{{ old('telepon', $perusahaan->telepon) }}" placeholder="0812xxxxxxx">
                  @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                  <label for="kota" class="form-label">Kota</label>
                  <input type="text" name="kota" id="kota"
                         class="form-control @error('kota') is-invalid @enderror"
                         value="{{ old('kota', $perusahaan->kota) }}" placeholder="Palembang">
                  @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                  <label for="alamat" class="form-label">Alamat Lengkap</label>
                  <textarea name="alamat" id="alamat" rows="3"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap perusahaan">{{ old('alamat', $perusahaan->alamat) }}</textarea>
                  @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- ====== Kontak Publik (opsional) ====== --}}
            <div class="border rounded-3 p-3 p-md-4 mb-4">
              <div class="d-flex align-items-center mb-3 gap-2">
                <i class="fa-solid fa-envelope"></i>
                <h6 class="mb-0">Kontak Publik</h6>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="email" class="form-label">Email Perusahaan (publik)</label>
                  <input type="email" name="email" id="email"
                         class="form-control @error('email') is-invalid @enderror"
                         value="{{ old('email', $perusahaan->email) }}" placeholder="email@perusahaan.com">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="form-text">Ini email kontak yang tampil di profil. <strong>Tidak</strong> mengubah email login.</div>
                </div>
              </div>
            </div>

            {{-- ====== Berkas & Legalitas ====== --}}
            <div class="border rounded-3 p-3 p-md-4 mb-4">
              <div class="d-flex align-items-center mb-3 gap-2">
                <i class="fa-solid fa-file-shield"></i>
                <h6 class="mb-0">Berkas & Legalitas</h6>
              </div>

              @php
                use Illuminate\Support\Facades\Storage;
                $logoUrl = ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo))
                          ? Storage::disk('public')->url($perusahaan->logo)
                          : asset('assets/img/company.png');
                $legalUrl = ($perusahaan->dokumen_legal && Storage::disk('public')->exists($perusahaan->dokumen_legal))
                          ? Storage::disk('public')->url($perusahaan->dokumen_legal)
                          : null;
              @endphp

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label d-flex align-items-center justify-content-between">
                    <span>Logo Perusahaan</span>
                    <small class="text-muted">JPG/PNG • Maks 2MB</small>
                  </label>

                  <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="{{ $logoUrl }}" id="logoPreview" alt="Logo"
                         class="rounded border" style="width:72px;height:72px;object-fit:cover">
                    <div class="flex-grow-1">
                      <input type="file" name="logo" id="logo"
                             class="form-control @error('logo') is-invalid @enderror"
                             accept="image/*">
                      @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      <div class="form-text">Unggah untuk mengganti logo.</div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label d-flex align-items-center justify-content-between">
                    <span>Dokumen Legal (opsional)</span>
                    <small class="text-muted">PDF/DOC/Gambar • Maks 4MB</small>
                  </label>

                  <div class="mb-2">
                    @if($legalUrl)
                      <a href="{{ $legalUrl }}" target="_blank" class="small">
                        <i class="fa-regular fa-file-lines me-1"></i>Lihat dokumen saat ini
                      </a>
                    @else
                      <span class="small text-muted">Belum ada dokumen.</span>
                    @endif
                  </div>

                  <input type="file" name="dokumen_legal" id="dokumen_legal"
                         class="form-control @error('dokumen_legal') is-invalid @enderror"
                         accept=".pdf,.doc,.docx,image/*">
                  @error('dokumen_legal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="npwp" class="form-label">NPWP (opsional)</label>
                  <input type="text" name="npwp" id="npwp"
                         class="form-control @error('npwp') is-invalid @enderror"
                         value="{{ old('npwp', $perusahaan->npwp) }}">
                  @error('npwp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="siup" class="form-label">SIUP (opsional)</label>
                  <input type="text" name="siup" id="siup"
                         class="form-control @error('siup') is-invalid @enderror"
                         value="{{ old('siup', $perusahaan->siup) }}">
                  @error('siup') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- ====== Verifikasi (admin saja, kalau mau tampil di sini) ====== --}}
            @if(auth()->user()->role === 'admin')
                <div class="border rounded-3 p-3 p-md-4 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch"
                        id="is_verified" name="is_verified" value="1"
                        {{ old('is_verified', $perusahaan->is_verified) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_verified">Tandai sebagai terverifikasi</label>
                </div>
                <div class="form-text">Jika dinyalakan, sistem akan mengisi <em>verified_at</em> secara otomatis.</div>
                </div>
            @endif

            {{-- ====== Submit ====== --}}
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('perusahaan.index') }}" class="btn btn-light">Batal</a>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="text-muted small mt-3">
        <i class="fa-regular fa-circle-question me-1"></i>
        Mengunggah file baru akan menggantikan file lama. Pastikan ukuran dan format sesuai.
      </div>
    </div>
  </div>
</div>

<script>
  // Preview logo saat pilih file
  document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('logo');
    const img   = document.getElementById('logoPreview');
    if(!input || !img) return;

    input.addEventListener('change', function(e){
      const f = e.target.files && e.target.files[0];
      if(!f) return;
      const url = URL.createObjectURL(f);
      img.src = url;
      img.onload = () => URL.revokeObjectURL(url);
    });
  });
</script>
@endsection
