@extends('layout.main')

@section('title', 'Buat Kuesioner')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">Buat Kuesioner Baru</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.questionnaire.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Kuesioner</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi (opsional)</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active">
                    <label class="form-check-label">Aktifkan kuesioner ini</label>
                </div>

                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.questionnaire.index') }}" class="btn btn-secondary">Batal</a>

            </form>
        </div>
    </div>
</div>
@endsection
