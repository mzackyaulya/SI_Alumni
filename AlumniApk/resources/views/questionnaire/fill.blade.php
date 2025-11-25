@extends('layout.main')

@section('title', 'Isi Kuesioner')

@section('content')
<div class="container py-5">

    {{-- Alert info / error --}}
    @if(session('info'))
        <div class="alert alert-info mb-4">
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8 text-center">
            <h1 class="fw-bold mb-2" style="font-size: 2rem;">
                {{ $questionnaire->title ?? 'Kuesioner' }}
            </h1>

            @if($questionnaire->description)
                <p class="text-muted mb-1">
                    {{ $questionnaire->description }}
                </p>
            @endif

            <p class="text-muted mb-0" style="font-size: .9rem;">
                Mohon isi kuesioner ini dengan jujur. Jawaban Anda akan membantu sekolah
                meningkatkan kualitas layanan.
            </p>
        </div>
    </div>

    {{-- Card form --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">

                    {{-- Info jumlah pertanyaan --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 border">
                            {{ $questionnaire->questions->count() }} Pertanyaan
                        </span>
                    </div>

                    <hr class="mb-4">

                    <form action="{{ route('questionnaire.submit', $questionnaire->id) }}" method="POST">
                        @csrf

                        @foreach($questionnaire->questions as $q)
                            <div class="mb-4 pb-3" style="border-bottom: 1px dashed #e2e6f0;">
                                <label class="fw-semibold mb-2 d-block">
                                    {{ $loop->iteration }}. {{ $q->question_text }}
                                    <span class="text-danger">*</span>
                                </label>

                                {{-- ============================ --}}
                                {{--          PILIHAN GANDA        --}}
                                {{-- ============================ --}}
                                @if($q->question_type === 'choice')

                                    @foreach($q->options as $opt)
                                        <div class="form-check mb-1">
                                            <input
                                                type="radio"
                                                name="q_{{ $q->id }}"
                                                id="q{{ $q->id }}_{{ $loop->index }}"
                                                value="{{ $opt }}"        {{-- FIX: gunakan string --}}
                                                class="form-check-input"
                                                required
                                            >
                                            <label class="form-check-label" for="q{{ $q->id }}_{{ $loop->index }}">
                                                {{ $opt }}
                                            </label>
                                        </div>
                                    @endforeach

                                @endif


                                {{-- ============================ --}}
                                {{--             SKALA            --}}
                                {{-- ============================ --}}
                                @if($q->question_type === 'scale')

                                    <select name="q_{{ $q->id }}" class="form-select" required>
                                        <option value="">-- pilih jawaban --</option>

                                        @foreach($q->options as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach

                                    </select>

                                @endif


                                {{-- ============================ --}}
                                {{--             TEXT             --}}
                                {{-- ============================ --}}
                                @if($q->question_type === 'text')

                                    <textarea
                                        class="form-control"
                                        name="q_{{ $q->id }}"
                                        rows="3"
                                        placeholder="Tulis jawaban Anda di sini..."
                                        required
                                    ></textarea>

                                @endif

                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted">
                                Terima kasih atas partisipasi Anda.
                            </small>

                            <button type="submit" class="btn btn-primary px-4">
                                Kirim Jawaban
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
