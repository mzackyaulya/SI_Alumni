@extends('layout.main')

@section('title','Data Alumni')

<style>
  /* wrapper bisa scroll ke kanan, tapi biarkan Y terlihat agar dropdown tidak kepotong */
  .table-wrap{
    width:100%;
    overflow-x:auto;
    overflow-y:visible;
    -webkit-overflow-scrolling: touch;
  }

  .table-fixed{
    table-layout: fixed;
    min-width: 1400px;
  }
  .table-fixed thead th,
  .table-fixed tbody td{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
  }

  .colw-50  { width: 50px;  }
  .colw-80  { width: 80px;  }
  .colw-100 { width: 100px; }
  .colw-120 { width: 120px; }
  .colw-150 { width: 150px; }
  .colw-180 { width: 180px; }
  .colw-200 { width: 200px; }
  .colw-220 { width: 220px; }

    .action-menu{
    z-index: 2000;
    }

    .dropdown-menu{
    margin-top: .25rem;
    }
</style>

@section('content')
<div class="container py-4">
    {{-- header & search --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Data Alumni</h4>
        <div class="d-flex align-items-center gap-2">
            <form action="{{ route('alumni.index') }}" method="GET" class="m-0">
                <div class="input-group input-group-sm">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Pencarian..."
                    value="{{ request('q') }}">
                <button class="btn btn-outline-primary" type="submit" aria-label="Cari">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                </div>
            </form>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('alumni.create') }}" class="btn btn-primary btn-sm">+ Tambah Alumni</a>
            @endif
        </div>

    </div>
    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0 table-wrap">
            <table class="table table-bordered table-striped mb-0 text-center table-fixed">
                <thead class="table-primary">
                <tr>
                    <th class="colw-50">No</th>
                    <th class="colw-80">NIS</th>
                    <th class="colw-80">NISN</th>
                    <th class="colw-150">Nama</th>
                    <th class="colw-180">Email</th>
                    <th class="colw-150">No. HP</th>
                    <th class="colw-120">Jenis Kelamin</th>
                    <th class="colw-150">Nama Ortu</th>
                    <th class="colw-200">Tempat, Tanggal Lahir</th>
                    <th class="colw-120">STTP</th>
                    <th class="colw-100">Angkatan</th>
                    <th class="colw-180">Jurusan</th>
                    <th class="colw-80">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($alumni as $index => $a)
                    <tr>
                        <td>{{ $alumni->firstItem() + $index }}</td>
                        <td title="{{ $a->nis }}">{{ $a->nis }}</td>
                        <td title="{{ $a->nisn }}">{{ $a->nisn }}</td>
                        <td title="{{ $a->nama }}">{{ $a->nama }}</td>
                        <td title="{{ $a->email }}">{{ $a->email }}</td>
                        <td title="{{ $a->phone }}">{{ $a->phone ?? '-' }}</td>
                        <td>
                            @php $jk = $a->jenis_kelamin; @endphp
                            {{ $jk === 'L' ? 'Laki-laki' : ($jk === 'P' ? 'Perempuan' : '-') }}
                        </td>
                        <td title="{{ $a->nama_ortu }}">{{ $a->nama_ortu ?? '-' }}</td>
                        <td title="{{ $a->tempat_lahir }}, {{ $a->tanggal_lahir ? \Illuminate\Support\Carbon::parse($a->tanggal_lahir)->format('d M Y') : '-' }}">
                            {{ $a->tempat_lahir ?? '-' }}, {{ $a->tanggal_lahir ? \Illuminate\Support\Carbon::parse($a->tanggal_lahir)->format('d M Y') : '-' }}
                        </td>
                        <td title="{{ $a->sttp }}">{{ $a->sttp ?? '-' }}</td>
                        <td>{{ $a->angkatan ?? '-' }}</td>
                        <td title="{{ $a->jurusan }}">{{ $a->jurusan ?? '-' }}</td>
                        <td class="text-center">
                            <div class="dropdown position-static">
                                <button class="btn btn-sm bg-transparent border-0"
                                        type="button"
                                        id="aksiDropdown{{ $a->id }}"
                                        data-bs-toggle="dropdown"
                                        data-bs-display="static"
                                        data-bs-offset="0,8"
                                        data-bs-container="body" 
                                        data-bs-boundary="viewport"
                                        aria-expanded="false"
                                        style="width:36px;height:36px">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm action-menu"
                                    aria-labelledby="aksiDropdown{{ $a->id }}">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('alumni.show', $a->id) }}">
                                        <i class="fa-solid fa-eye text-info"></i><span>Lihat</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('alumni.edit', $a->id) }}">
                                        <i class="fa-solid fa-pen-to-square text-warning"></i><span>Edit</span>
                                        </a>
                                    </li>
                                    @if(auth()->user()->role === 'admin')
                                    <li>
                                        <form action="{{ route('alumni.destroy', $a->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-trash text-danger"></i><span>Hapus</span>
                                        </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="16" class="text-muted py-4">Belum ada data alumni.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($alumni instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer">{{ $alumni->links() }}</div>
        @endif
    </div>
</div>
@endsection
