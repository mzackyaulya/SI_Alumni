@extends('layout.main')

@section('title', 'Kelola Kuesioner')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Daftar Kuesioner</h3>

        <a href="{{ route('admin.questionnaire.create') }}" class="btn btn-primary">
            + Buat Kuesioner
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pertanyaan</th>
                    <th>Responden</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                <tbody>
                @foreach($questionnaires as $q)
                    <tr>
                        <td>{{ $q->title }}</td>
                        <td>{{ $q->questions_count }}</td>
                        <td>{{ $q->respondents_count }}</td>
                        <td>
                            @if($q->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.questionnaire.edit', $q->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('admin.questionnaire.results', $q->id) }}" class="btn btn-info btn-sm">Hasil</a>
                            <form action="{{ route('admin.questionnaire.destroy', $q->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus kuesioner ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
