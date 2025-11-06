@extends('layout.main')

@section('title','Data Alumni')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Data  Alumni</h4>
        <div class="d-flex align-items-center gap-2">
            {{-- Form Search --}}
            <form action="{{ route('alumni.index') }}" method="GET" class="d-flex">
            <input type="text"
                    name="q"
                    class="form-control form-control-sm me-2"
                    placeholder="Pencarian..."
                    value="{{ request('q') }}"
                    style="min-width: 200px;">
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            </form>
            @if(auth()->user()->role === 'admin')
                {{-- Tombol Tambah Alumni --}}
                <a href="{{ route('alumni.create') }}" class="btn btn-primary btn-sm">
                + Tambah Alumni
                </a>
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

    {{-- Table data alumni --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0 align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Role</th>
                            <th style="width: 18%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumni as $index => $a)
                            <tr>
                                <td>{{ $alumni->firstItem() + $index }}</td>
                                <td>{{ $a->nama }}</td>
                                <td>{{ $a->email }}</td>
                                <td>{{ $a->phone ?? '-' }}</td>
                                <td>
                                <span class="badge bg-info text-dark text-uppercase">{{ $a->user->role ?? 'alumni' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown position-static">
                                        <button
                                            class="btn btn-sm bg-transparant border-0 d-inline-flex align-items-center justify-content-center"
                                            type="button"
                                            id="aksiDropdown{{ $a->id }}"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            style="width:36px;height:36px"
                                            >
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="aksiDropdown{{ $a->id }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('alumni.show', $a->id) }}">
                                                <i class="fa-solid fa-eye me-2 text-info"></i> Lihat
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('alumni.edit', $a->id) }}">
                                                <i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Edit
                                                </a>
                                            </li>
                                            @if(auth()->user()->role === 'admin')
                                                <li>
                                                    <form action="{{ route('alumni.destroy', $a->id) }}" method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fa-solid fa-trash me-2 text-danger"></i> Hapus
                                                    </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-muted py-4">Belum ada data alumni.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($alumni instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer">
                {{ $alumni->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
