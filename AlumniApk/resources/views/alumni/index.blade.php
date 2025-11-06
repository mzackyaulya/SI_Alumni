@extends('layout.main')

@section('title','Data Alumni')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Data  Alumni</h4>
    <a href="{{ route('alumni.create') }}" class="btn btn-primary">
      + Tambah Alumni
    </a>
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
                <td>
                  <a href="{{ route('alumni.show', $a->id) }}" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-eye"></i> Lihat
                  </a>
                  <a href="{{ route('alumni.edit', $a->id) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <form action="{{ route('alumni.destroy', $a->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-muted py-4">Belum ada data alumni.</td>
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
