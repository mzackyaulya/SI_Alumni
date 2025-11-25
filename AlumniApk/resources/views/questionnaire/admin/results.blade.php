@extends('layout.main')

@section('title', 'Hasil Kuesioner')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">
        Hasil Kuesioner: {{ $questionnaire->title }}
    </h3>

    @foreach($questionnaire->questions as $q)

        <div class="card mb-4">
            <div class="card-body">

                <h5>{{ $loop->iteration }}. {{ $q->question_text }}</h5>

                @if(isset($stats[$q->id]['labels']))

                    <canvas id="chart-{{ $q->id }}" height="120"></canvas>

                @else
                    <p class="text-muted mt-3">
                        Pertanyaan isian. Jumlah responden: {{ $stats[$q->id]['text_count'] }}
                    </p>
                @endif

            </div>
        </div>

    @endforeach

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @foreach($questionnaire->questions as $q)
        @if(isset($stats[$q->id]['labels']))
        new Chart(
            document.getElementById('chart-{{ $q->id }}'),
            {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stats[$q->id]['labels']) !!},
                    datasets: [{
                        label: 'Jumlah Jawaban',
                        data: {!! json_encode($stats[$q->id]['values']) !!}
                    }]
                }
            }
        );
        @endif
    @endforeach
</script>
@endsection


