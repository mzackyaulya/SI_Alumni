<?php

namespace App\Http\Controllers;

use id;
use App\Models\Alumni;
use Illuminate\Http\Request;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionnaireAnswer;

class QuestionnaireAnswerController extends Controller
{

    /**
     * Halaman form pengisian kuesioner untuk alumni.
     */
    public function fill($questionnaireId)
    {
        $questionnaire = Questionnaire::with('questions')->findOrFail($questionnaireId);

        // cari alumni berdasar user_id (lebih aman daripada tebak relasi)
        $alumni = Alumni::where('user_id', auth()->id())->firstOrFail();

        // cek apakah alumni ini sudah pernah isi
        $already = QuestionnaireAnswer::where('questionnaire_id', $questionnaireId)
            ->where('alumni_id', $alumni->id)
            ->exists();

        if ($already) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengisi kuesioner ini.');
        }

        return view('questionnaire.fill', compact('questionnaire'));
    }

    /**
     * Proses submit jawaban kuesioner dari alumni.
     */
    public function submit(Request $request, $questionnaireId)
    {
        $questionnaire = Questionnaire::with('questions')->findOrFail($questionnaireId);
        $alumni = Alumni::where('user_id', auth()->id())->firstOrFail();

        DB::transaction(function () use ($request, $questionnaire, $alumni) {

            foreach ($questionnaire->questions as $q) {
                $field = 'q_' . $q->id;
                $value = $request->input($field);

                // kalau mau buat required per pertanyaan, bisa dicek di sini

                QuestionnaireAnswer::updateOrCreate(
                    [
                        'questionnaire_id' => $questionnaire->id,
                        'question_id'      => $q->id,
                        'alumni_id'        => $alumni->id,
                    ],
                    [
                        'answer'           => $value,
                    ]
                );
            }
        });

        return redirect()->route('dashboard')
            ->with('success', 'Terima kasih, kuesioner berhasil dikirim.');
    }
}
