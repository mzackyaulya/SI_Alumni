<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LowonganController extends Controller
{
    /**
     * KATALOG PUBLIK: daftar lowongan dengan filter.
     * URL contoh: GET /lowongan?q=laravel&tipe=intern&level=junior&lokasi=Palembang
     */
    public function index(Request $request)
    {
        $filter = $request->only(['q','tipe','level','lokasi']);

        $lowongans = Lowongan::with('perusahaan')
            ->where('aktif', true)
            ->where(function($w){
                // Sembunyikan yang lewat deadline (opsional)
                $w->whereNull('deadline')->orWhereDate('deadline', '>=', now());
            })
            ->filter($filter)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('lowongan.index', compact('lowongans', 'filter'));
    }

    /**
     * FORM BUAT LOWONGAN (hanya company).
     */
    public function create()
    {
       $user = Auth::user();

        if (! $user || ! in_array($user->role, ['admin','company'])) {
            abort(403);
        }

        if ($user->role === 'company') {
            // company: pakai perusahaannya sendiri, tidak perlu dropdown
            $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
            return view('lowongan.create', [
                'mode'         => 'company',
                'perusahaan'   => $perusahaan,   // single
                'perusahaans'  => collect(),     // kosong agar view aman
            ]);
        }

        // admin: kirim semua perusahaan utk dropdown
        $perusahaans = Perusahaan::select('id','nama')->orderBy('nama')->get();

        return view('lowongan.create', [
            'mode'        => 'admin',
            'perusahaans' => $perusahaans,      // list
            'perusahaan'  => null,
        ]);

    }

    /**
     * SIMPAN LOWONGAN BARU (hanya company).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['company','admin'])) {
            abort(403, 'Hanya perusahaan yang dapat membuat lowongan.');
        }

        $data = $request->validate([
            'judul'       => ['required','string','max:255'],
            'tipe'        => ['nullable','string','max:50'],   // fulltime/parttime/intern/contract
            'level'       => ['nullable','string','max:50'],   // junior/middle/senior
            'lokasi'      => ['nullable','string','max:100'],  // kota / remote / hybrid
            'gaji_min'    => ['nullable','integer','min:0'],
            'gaji_max'    => ['nullable','integer','min:0'],
            'deadline'    => ['nullable','date'],
            'aktif'       => ['sometimes','boolean'],
            'deskripsi'   => ['nullable','string'],
            'kualifikasi' => ['nullable','string'],
            'tag'         => ['nullable'],
        ]);

        $rawTag = $request->input('tag');

        if (is_array($rawTag)) {
            // dari input name="tag[]"
            $data['tag'] = array_values(array_filter(array_map('trim', $rawTag)));
        } elseif (is_string($rawTag) && $rawTag !== '') {
            // coba parse sebagai JSON (Tagify kadang kirim JSON string)
            $decoded = json_decode($rawTag, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Tagify format: [{value:"Laravel"}, {value:"SQL"}]
                if (isset($decoded[0]['value'])) {
                    $data['tag'] = array_values(array_filter(array_map(
                        fn($it) => trim((string)($it['value'] ?? '')),
                        $decoded
                    )));
                } else {
                    // JSON array biasa: ["Laravel","SQL"]
                    $data['tag'] = array_values(array_filter(array_map('trim', $decoded)));
                }
            } else {
                // fallback: “Laravel, SQL, Komunikasi”
                $data['tag'] = array_values(array_filter(array_map('trim', explode(',', $rawTag))));
            }
        } else {
            $data['tag'] = null;
        }

        // Tentukan perusahaan_id:
        if ($user->role === 'company') {
            $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
            $data['perusahaan_id'] = $perusahaan->id;
        } else {
            // Admin wajib memilih perusahaan_id dari form (atau Anda bisa buat select di view)
            $data['perusahaan_id'] = $request->validate([
                'perusahaan_id' => ['required','uuid','exists:perusahaans,id'],
            ])['perusahaan_id'];
        }

        Lowongan::create($data);

        return redirect()->route('lowongan.index')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    /**
     * DETAIL LOWONGAN (publik).
     * Route model binding berbasis slug jika di model Lowongan: getRouteKeyName() = 'slug'
     */
    public function show(Lowongan $lowongan)
    {
        $user  = Auth::user();
        $owner = optional($lowongan->perusahaan)->user_id;

        // Jika lowongan non-aktif, hanya admin atau pemilik perusahaan yang boleh melihat
        if (! $lowongan->aktif && !($user && ($user->role === 'admin' || $user->id === $owner))) {
            abort(404);
        }

        return view('lowongan.show', compact('lowongan'));
    }

    /**
     * FORM EDIT LOWONGAN (hanya admin/pemilik).
     */
    public function edit(Lowongan $lowongan)
    {
        $this->otorisasiPemilik($lowongan);
        return view('lowongan.edit', compact('lowongan'));
    }

    /**
     * UPDATE LOWONGAN (hanya admin/pemilik).
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        $this->otorisasiPemilik($lowongan);

        $data = $request->validate([
            'judul'       => ['required','string','max:255'],
            'tipe'        => ['nullable','string','max:50'],
            'level'       => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'gaji_min'    => ['nullable','integer','min:0'],
            'gaji_max'    => ['nullable','integer','min:0'],
            'deadline'    => ['nullable','date'],
            'aktif'       => ['sometimes','boolean'],
            'deskripsi'   => ['nullable','string'],
            'kualifikasi' => ['nullable','string'],
            'tag'         => ['nullable'],
        ]);

        // --- Normalisasi TAG menjadi array ---
        $rawTag = $request->input('tag'); // bisa array / string / JSON

        if (is_array($rawTag)) {
            // dari input name="tag[]"
            $data['tag'] = array_values(array_filter(array_map('trim', $rawTag)));
        } elseif (is_string($rawTag) && $rawTag !== '') {
            // coba parse sebagai JSON (Tagify kadang kirim JSON string)
            $decoded = json_decode($rawTag, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Tagify format: [{value:"Laravel"}, {value:"SQL"}]
                if (isset($decoded[0]['value'])) {
                    $data['tag'] = array_values(array_filter(array_map(
                        fn($it) => trim((string)($it['value'] ?? '')),
                        $decoded
                    )));
                } else {
                    // JSON array biasa: ["Laravel","SQL"]
                    $data['tag'] = array_values(array_filter(array_map('trim', $decoded)));
                }
            } else {
                // fallback: “Laravel, SQL, Komunikasi”
                $data['tag'] = array_values(array_filter(array_map('trim', explode(',', $rawTag))));
            }
        } else {
            $data['tag'] = null;
        }

        $lowongan->update($data);

        return back()->with('success', 'Lowongan diperbarui.');
    }

    /**
     * HAPUS LOWONGAN (hanya admin/pemilik).
     */
    public function destroy(Lowongan $lowongan)
    {
        $this->otorisasiPemilik($lowongan);
        $lowongan->delete();

        return redirect()->route('lowongan.index')->with('success', 'Lowongan dihapus.');
    }

    /* =========================================================
     |  HELPER: cek pemilik (admin atau owner perusahaan)
     |=========================================================*/
    protected function otorisasiPemilik(Lowongan $lowongan): void
    {
        $user  = Auth::user();
        $owner = optional($lowongan->perusahaan)->user_id;

        if (!($user && ($user->role === 'admin' || $user->id === $owner))) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }
    }
}
