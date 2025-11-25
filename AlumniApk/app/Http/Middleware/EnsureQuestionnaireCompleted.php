<?php

namespace App\Http\Middleware;

use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureQuestionnaireCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // kalau belum login atau bukan alumni -> jangan paksa isi kuesioner
        if (!$user || $user->role !== 'alumni') {
            return $next($request);
        }

        $alumni = $user->alumni;
        if (!$alumni) {
            return $next($request);
        }

        // skip halaman yang gak boleh kena redirect
        if ($request->routeIs(
            'questionnaire.fill',
            'questionnaire.submit',
            'login',
            'register',
            'password.*'
        )) {
            return $next($request);
        }

        // ambil kuesioner aktif & wajib
        $questionnaire = Questionnaire::where('is_active', true)
            ->where('is_mandatory', true)
            ->first();

        if (!$questionnaire) {
            return $next($request);
        }

        $totalQuestions = $questionnaire->questions()->count();

        $answeredCount = QuestionnaireAnswer::where('questionnaire_id', $questionnaire->id)
            ->where('alumni_id', $alumni->id)
            ->distinct('question_id')
            ->count('question_id');

        // kalau belum semua terisi -> paksa ke halaman kuesioner
        if ($totalQuestions > 0 && $answeredCount < $totalQuestions) {
            return redirect()
                ->route('questionnaire.fill', $questionnaire->id)
                ->with('info', 'Silakan isi kuesioner terlebih dahulu sebelum mengakses fitur lain.');
        }

        return $next($request);
    }

}
