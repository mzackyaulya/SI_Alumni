@extends('layout.main')

@section('title','Edit Lowongan')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h4 class="fw-bold mb-3">Edit Lowongan</h4>

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('lowongan.update',$lowongan) }}">
            @csrf @method('PUT')

            {{-- Admin boleh ganti perusahaan (opsional bila dikirim $perusahaans) --}}
            @if(auth()->user()->role === 'admin')
              <div class="mb-3">
                <label class="form-label">Perusahaan</label>
                <select name="perusahaan_id" class="form-select" disabled>
                  <option value="">{{ optional($lowongan->perusahaan)->nama ?? '—' }}</option>
                </select>
                <small class="text-muted">Perusahaan pengiklan tidak diubah di sini.</small>
              </div>
            @endif

            <div class="mb-3">
              <label class="form-label">Judul <span class="text-danger">*</span></label>
              <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                     value="{{ old('judul',$lowongan->judul) }}" required>
              @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Tipe</label>
                <select name="tipe" class="form-select @error('tipe') is-invalid @enderror">
                  <option value="">—</option>
                  @foreach(['fulltime','parttime','intern','contract'] as $t)
                    <option value="{{ $t }}" {{ old('tipe',$lowongan->tipe)===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                  @endforeach
                </select>
                @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Level</label>
                <select name="level" class="form-select @error('level') is-invalid @enderror">
                  <option value="">—</option>
                  @foreach(['junior','middle','senior'] as $l)
                    <option value="{{ $l }}" {{ old('level',$lowongan->level)===$l?'selected':'' }}>{{ ucfirst($l) }}</option>
                  @endforeach
                </select>
                @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                       value="{{ old('lokasi',$lowongan->lokasi) }}" placeholder="Kota / Remote / Hybrid">
                @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Gaji Minimum</label>
                <input type="number" name="gaji_min" class="form-control @error('gaji_min') is-invalid @enderror"
                       value="{{ old('gaji_min',$lowongan->gaji_min) }}" min="0" step="1">
                @error('gaji_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Gaji Maksimum</label>
                <input type="number" name="gaji_max" class="form-control @error('gaji_max') is-invalid @enderror"
                       value="{{ old('gaji_max',$lowongan->gaji_max) }}" min="0" step="1">
                @error('gaji_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            @php
              $dl = old('deadline', optional($lowongan->deadline)->format('Y-m-d'));
            @endphp
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Deadline</label>
                <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                       value="{{ $dl }}">
                @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6 mb-3 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="aktif" id="aktif"
                         value="1" {{ old('aktif',$lowongan->aktif) ? 'checked' : '' }}>
                  <label class="form-check-label" for="aktif">Aktif</label>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Kualifikasi</label>
              <textarea name="kualifikasi" rows="3"
                        class="form-control @error('kualifikasi') is-invalid @enderror">{{ old('kualifikasi',$lowongan->kualifikasi) }}</textarea>
              @error('kualifikasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi Pekerjaan</label>
              <textarea name="deskripsi" rows="5"
                        class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi',$lowongan->deskripsi) }}</textarea>
              @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Tag Skill</label>
              <input type="text" id="tagInput" class="form-control mb-2" placeholder="Ketik lalu Enter">
              <div id="tagList" class="d-flex flex-wrap gap-2"></div>
              <div id="tagHidden"></div>
              @error('tag') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('lowongan.index') }}" class="btn btn-light">Kembali</a>
              <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Tags helper --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  const tagInput = document.getElementById('tagInput');
  const tagList  = document.getElementById('tagList');
  const tagHidden= document.getElementById('tagHidden');
  let tags = @json(old('tag', $lowongan->tag ?? []));

  function render(){
    tagList.innerHTML = '';
    tagHidden.innerHTML = '';
    tags.forEach((t, i) => {
      const b = document.createElement('span');
      b.className = 'badge text-bg-secondary d-inline-flex align-items-center';
      b.innerHTML = `${t}<button type="button" class="btn-close btn-close-white ms-2" style="font-size:.6rem"></button>`;
      b.querySelector('button').onclick = () => { tags.splice(i,1); render(); };
      tagList.appendChild(b);

      const h = document.createElement('input');
      h.type = 'hidden'; h.name = 'tag[]'; h.value = t;
      tagHidden.appendChild(h);
    });
  }
  render();

  tagInput?.addEventListener('keydown', e=>{
    if(e.key === 'Enter'){
      e.preventDefault();
      const v = tagInput.value.trim();
      if(v && !tags.includes(v)){ tags.push(v); render(); }
      tagInput.value = '';
    }
  });
});
</script>
@endsection
