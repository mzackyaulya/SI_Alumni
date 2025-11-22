<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    /**
     * Daftar lamaran milik alumni yang login.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'alumni') abort(403);

        $alumni = Alumni::where('user_id', $user->id)->firstOrFail();

        $lamarans = Lamaran::with('lowongan.perusahaan')
            ->where('alumni_id', $alumni->id)
            ->latest()
            ->paginate(10);

        return view('lamaran.index', compact('lamarans'));
    }

    /**
     * Form lamar (alumni) – URL: /lowongan/{lowongan}/lamar
     */
    public function create(Lowongan $lowongan)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'alumni') abort(403);

        // lowongan harus aktif & belum lewat
        $deadlineOk = is_null($lowongan->deadline) || now()->lte($lowongan->deadline);
        if (!$lowongan->aktif || !$deadlineOk) {
            return redirect()->route('lowongan.show', $lowongan)
                ->with('error', 'Lowongan sudah tidak menerima lamaran.');
        }

        // Cek sudah pernah melamar
        $alumniId = Alumni::where('user_id', $user->id)->value('id');
        $sudah = Lamaran::where('lowongan_id', $lowongan->id)
                ->where('alumni_id', $alumniId)->exists();

        if ($sudah) {
            return redirect()->route('lowongan.show', $lowongan)
                ->with('info', 'Kamu sudah melamar lowongan ini.');
        }

        return view('lamaran.create', compact('lowongan'));
    }

    /**
     * Simpan lamaran (alumni).
     */
    public function store(Request $request, Lowongan $lowongan)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'alumni') abort(403);

        $alumni = Alumni::where('user_id', $user->id)->firstOrFail();

        $data = $request->validate([
            'cv'             => ['required','file','mimes:pdf,doc,docx','max:4096'],
            'surat_lamaran'  => ['nullable','file','mimes:pdf,doc,docx','max:4096'],
            'portfolio_url'  => ['nullable','url','max:255'],
            'catatan'        => ['nullable','string','max:2000'],
        ]);

        // upload
        $cvPath = $request->file('cv')->store('lamaran/cv', 'public');
        $suratPath = $request->hasFile('surat_lamaran')
            ? $request->file('surat_lamaran')->store('lamaran/surat', 'public')
            : null;

        try {
            Lamaran::create([
                'lowongan_id'        => $lowongan->id,
                'alumni_id'          => $alumni->id,
                'status'             => 'submitted',
                'cv_path'            => $cvPath,
                'surat_lamaran_path' => $suratPath,
                'portfolio_url'      => $data['portfolio_url'] ?? null,
                'catatan'            => $data['catatan'] ?? null,
            ]);
        } catch (QueryException $e) {
            // handle unique (lowongan_id, alumni_id)
            return redirect()->back()
                ->with('info', 'Kamu sudah melamar lowongan ini.');
        }

        return redirect()->route('lamaran.index')
            ->with('success', 'Lamaran terkirim. Pantau statusnya di menu Riwayat Lamaran.');
    }

    public function companyIndex()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'company') {
            abort(403);
        }

        // cari perusahaan berdasarkan user_id pemilik akun company ini
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();

        // ambil semua lamaran utk lowongan yang dimiliki perusahaan ini
        $lamarans = Lamaran::with(['lowongan', 'alumni.user'])
            ->whereHas('lowongan', function ($q) use ($perusahaan) {
                $q->where('perusahaan_id', $perusahaan->id);
            })
            ->latest()
            ->paginate(10);

        return view('perusahaan.lamaran.index', compact('lamarans', 'perusahaan'));
    }

    /**
     * Ubah status (admin/company).
     */
    public function updateStatus(Request $request, Lamaran $lamaran)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin','company'])) abort(403);

        // kalau company, pastikan pemilik lowongan
        if ($user->role === 'company') {
            if (optional($lamaran->lowongan->perusahaan)->user_id !== $user->id) abort(403);
        }

        $data = $request->validate([
            'status' => ['required', Rule::in([
                'submitted','review','shortlist','interview','accepted','rejected','withdrawn'
            ])],
            'jadwal_interview' => ['nullable','date'],
            'catatan'          => ['nullable','string','max:2000'],
        ]);

        $lamaran->update($data);

        return back()->with('success', 'Status lamaran diperbarui.');
    }

    /**
     * Batalkan lamaran (alumni).
     */
    public function withdraw(Lamaran $lamaran)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'alumni') abort(403);

        $alumniId = Alumni::where('user_id', $user->id)->value('id');
        if ($lamaran->alumni_id !== $alumniId) abort(403);

        $lamaran->update(['status' => 'withdrawn']);
        return back()->with('success', 'Lamaran dibatalkan.');
    }

    /**
     * (Opsional) Detail lamaran – milik sendiri atau pemilik lowongan.
     */
    public function show(Lamaran $lamaran)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        $isOwnerAlumni = $user->role === 'alumni'
            && Alumni::where('user_id', $user->id)->value('id') === $lamaran->alumni_id;

        $isOwnerCompany = $user->role === 'company'
            && optional($lamaran->lowongan->perusahaan)->user_id === $user->id;

        if (!($isOwnerAlumni || $isOwnerCompany || $user->role === 'admin')) abort(403);

        $lamaran->load('lowongan.perusahaan','alumni');
        return view('lamaran.show', compact('lamaran'));
    }
}
