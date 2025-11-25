<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;

class QuestionnaireAdminController extends Controller
{

    /**
     * Batasi akses hanya admin & waka.
     */
    private function authorizeAdmin()
    {
        $user = auth()->user();

        if (! $user || ! in_array($user->role, ['admin', 'waka'])) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }
    }

    /**
     * Tampilkan daftar kuesioner.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $questionnaires = Questionnaire::query()
            ->withCount('questions')
            ->withCount([
                'answers as respondents_count' => function ($q) {
                    $q->select(DB::raw('COUNT(DISTINCT alumni_id)'));
                }
            ])
            ->latest()
            ->get();

        return view('questionnaire.admin.index', compact('questionnaires'));
    }

    /**
     * Form tambah kuesioner.
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('questionnaire.admin.create');
    }

    /**
     * Simpan kuesioner baru.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // checkbox -> boolean manual
        $data['is_active']    = $request->has('is_active');
        $data['is_mandatory'] = true; // default wajib

        if ($data['is_active']) {
            Questionnaire::query()->update(['is_active' => false]);
        }

        $questionnaire = Questionnaire::create($data);

        return redirect()
            ->route('admin.questionnaire.edit', $questionnaire->id)
            ->with('success', 'Kuesioner berhasil dibuat. Silakan tambahkan pertanyaan.');
    }


    /**
     * Detail kuesioner + daftar pertanyaan.
     */
    public function show($id)
    {
        $this->authorizeAdmin();

        $questionnaire = Questionnaire::with('questions')->findOrFail($id);

        return view('questionnaire.admin.show', compact('questionnaire'));
    }

    /**
     * Form edit kuesioner (judul, deskripsi, aktif/tidak).
     */
    public function edit($id)
    {
        $this->authorizeAdmin();

        $questionnaire = Questionnaire::with('questions')->findOrFail($id);

        return view('questionnaire.admin.edit', compact('questionnaire'));
    }

    /**
     * Update data kuesioner.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $questionnaire = Questionnaire::findOrFail($id);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
            'is_mandatory' => 'nullable|boolean',
        ]);

        $data['is_active']    = $request->boolean('is_active');
        $data['is_mandatory'] = $request->boolean('is_mandatory', true);

        if ($data['is_active']) {
            Questionnaire::where('id', '!=', $questionnaire->id)
                ->update(['is_active' => false]);
        }

        $questionnaire->update($data);

        return redirect()
            ->route('admin.questionnaire.edit', $questionnaire->id)
            ->with('success', 'Kuesioner berhasil diperbarui.');
    }

    /**
     * Hapus kuesioner (pertanyaan & jawaban ikut terhapus karena cascade).
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->delete();

        return redirect()
            ->route('admin.questionnaire.index')
            ->with('success', 'Kuesioner berhasil dihapus.');
    }

    // ==============================
    // PERTANYAAN DALAM KUESIONER
    // ==============================

    /**
     * Simpan pertanyaan baru.
     */
    public function storeQuestion(Request $request, $questionnaireId)
    {
        $this->authorizeAdmin();

        $questionnaire = Questionnaire::findOrFail($questionnaireId);

        $data = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:choice,scale,text',
            'options_raw'   => 'nullable|string', // opsi, diinput per baris
        ]);

        $options = null;

        if (in_array($data['question_type'], ['choice', 'scale']) && !empty($data['options_raw'])) {
            $options = collect(preg_split("/\r\n|\n|\r/", $data['options_raw']))
                ->map(fn($v) => trim($v))
                ->filter()
                ->values()
                ->toArray();
        }

        QuestionnaireQuestion::create([
            'questionnaire_id' => $questionnaire->id,
            'question_text'    => $data['question_text'],
            'question_type'    => $data['question_type'],
            'options'          => $options,
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    /**
     * Update pertanyaan.
     */
    public function updateQuestion(Request $request, $questionId)
    {
        $this->authorizeAdmin();

        $question = QuestionnaireQuestion::findOrFail($questionId);

        $data = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:choice,scale,text',
            'options_raw'   => 'nullable|string',
        ]);

        $options = null;

        if (in_array($data['question_type'], ['choice', 'scale']) && !empty($data['options_raw'])) {
            $options = collect(preg_split("/\r\n|\n|\r/", $data['options_raw']))
                ->map(fn($v) => trim($v))
                ->filter()
                ->values()
                ->toArray();
        }

        $question->update([
            'question_text' => $data['question_text'],
            'question_type' => $data['question_type'],
            'options'       => $options,
        ]);

        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Hapus pertanyaan.
     */
    public function destroyQuestion($questionId)
    {
        $this->authorizeAdmin();

        $question = QuestionnaireQuestion::findOrFail($questionId);
        $question->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    // ==============================
    // HASIL & STATISTIK
    // ==============================

    /**
     * Halaman hasil kuesioner + data statistik untuk Chart.js.
     */
    public function results($id)
    {
        $this->authorizeAdmin();

        $questionnaire = Questionnaire::with('questions')->findOrFail($id);

        $stats = [];

        foreach ($questionnaire->questions as $q) {
            if (in_array($q->question_type, ['choice', 'scale'])) {
                $data = QuestionnaireAnswer::where('question_id', $q->id)
                    ->select('answer', DB::raw('COUNT(*) as total'))
                    ->groupBy('answer')
                    ->get();

                $stats[$q->id] = [
                    'labels' => $data->pluck('answer'),
                    'values' => $data->pluck('total'),
                ];
            } else {
                // text: simpan jumlah responden saja (detail bisa di tabel lain)
                $count = QuestionnaireAnswer::where('question_id', $q->id)->count();
                $stats[$q->id] = [
                    'text_count' => $count,
                ];
            }
        }

        return view('questionnaire.admin.results', compact('questionnaire', 'stats'));
    }
}
