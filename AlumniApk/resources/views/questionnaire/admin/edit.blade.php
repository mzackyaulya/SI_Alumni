@extends('layout.main')

@section('title', 'Edit Kuesioner')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Edit Kuesioner</h3>

    {{-- FORM UPDATE KUESIONER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.questionnaire.update', $questionnaire->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ $questionnaire->title }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control">{{ $questionnaire->description }}</textarea>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active"
                           {{ $questionnaire->is_active ? 'checked' : '' }}>
                    <label class="form-check-label">Aktif</label>
                </div>

                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>


    {{-- TAMBAH PERTANYAAN --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">Tambah Pertanyaan</div>
        <div class="card-body">

            <form action="{{ url('/admin/questionnaire/'.$questionnaire->id.'/questions') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="fw-semibold">Teks Pertanyaan</label>
                    <input type="text" name="question_text" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Jenis Pertanyaan</label>
                    <select name="question_type" class="form-select" required>
                        <option value="choice">Pilihan Ganda</option>
                        <option value="scale">Skala (1-5)</option>
                        <option value="text">Isian</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">
                        Opsi Jawaban (tiap baris = 1 opsi, hanya untuk pilihan/scale)
                    </label>
                    <textarea name="options_raw" class="form-control" rows="4"></textarea>
                </div>

                <button class="btn btn-success">Tambah Pertanyaan</button>

            </form>

        </div>
    </div>


    {{-- LIST PERTANYAAN --}}
    <div class="card">
        <div class="card-header fw-bold">Daftar Pertanyaan</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Pertanyaan</th>
                    <th>Jenis</th>
                    <th>Opsi</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                <tbody>
                @foreach($questionnaire->questions as $q)
                    <tr>
                        <td>{{ $q->question_text }}</td>
                        <td>{{ $q->question_type }}</td>
                        <td>
                            @if($q->options)
                                <ul class="mb-0">
                                    @foreach($q->options as $o)
                                        <li>{{ $o }}</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form action="{{ url('/admin/questions/'.$q->id) }}" method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus pertanyaan ini?')">
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
