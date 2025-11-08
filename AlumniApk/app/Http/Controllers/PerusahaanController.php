<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
    /* =========================================================
     |  BIODATA (ringkas) — bisa diakses semua role saat login
     |=========================================================*/
    public function biodataShow(Perusahaan $perusahaan)
    {
        return view('perusahaan.biodata', compact('perusahaan'));
    }

    /* =========================================================
     |  SHOW (internal lengkap) — hanya admin/owner
     |=========================================================*/
    public function show(Perusahaan $perusahaan)
    {
        $this->authorize('viewInternal', $perusahaan);
        return view('perusahaan.show', compact('perusahaan'));
    }

    /* =========================================================
     |  ADMIN: DATA PERUSAHAAN (CRUD)
     |=========================================================*/
    public function index(Request $r)
    {
        $q        = trim((string) $r->get('q', ''));
        $industri = trim((string) $r->get('industri', ''));
        $verified = (bool) $r->get('verified', false);

        $perusahaans = Perusahaan::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")
                      ->orWhere('industri', 'like', "%{$q}%")
                      ->orWhere('kota', 'like', "%{$q}%");
                });
            })
            ->when($industri !== '', fn($x) => $x->where('industri', $industri))
            ->when($verified, fn($x) => $x->where('is_verified', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('perusahaan.index', compact('perusahaans', 'q', 'industri', 'verified'));
    }

    public function create()
    {
        // Form tambah perusahaan + (opsional) password
        return view('perusahaan.create');
    }

    /**
     * Admin menambahkan perusahaan:
     * - Membuat akun user role=company (email unik).
     * - Password: pakai input (password + confirmation) bila diisi, kalau kosong generate random.
     * - Membuat profil perusahaan terkait user tersebut.
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            // user login company
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],

            // profil perusahaan
            'nama'      => ['required', 'string', 'max:255'],
            'industri'  => ['nullable', 'string', 'max:100'],
            'website'   => ['nullable', 'string', 'max:255'],
            'telepon'   => ['nullable', 'string', 'max:30'],
            'alamat'    => ['nullable', 'string', 'max:500'],
            'kota'      => ['nullable', 'string', 'max:100'],
            'logo'          => ['nullable', 'image', 'max:2048'],
            'dokumen_legal' => ['nullable', 'file', 'max:4096'],
            'npwp'      => ['nullable', 'string', 'max:100'],
            'siup'      => ['nullable', 'string', 'max:100'],
        ]);

        // Tentukan password
        $plainPassword = $data['password'] ?? Str::random(10);

        // 1) Buat akun user role=company
        $user = User::create([
            'name'              => $data['nama'],
            'email'             => $data['email'],
            'role'              => 'company',
            'password'          => Hash::make($plainPassword),
            'email_verified_at' => now(),
        ]);

        // (opsional produksi) kirim reset password supaya tidak tampilkan password plain:
        // Password::sendResetLink(['email' => $user->email]);
        // $plainPassword = '(Reset password dikirim ke email)';

        // 2) Upload file
        $logoPath = null;
        $legalPath = null;
        if ($r->hasFile('logo')) {
            $logoPath = $r->file('logo')->store('company_logo', 'public');
        }
        if ($r->hasFile('dokumen_legal')) {
            $legalPath = $r->file('dokumen_legal')->store('company_legal', 'public');
        }

        // 3) Buat profil perusahaan
        Perusahaan::create([
            'user_id'       => $user->id,
            'nama'          => $data['nama'],
            'industri'      => $data['industri'] ?? null,
            'website'       => $data['website'] ?? null,
            'email'         => $data['email'], // email kontak = email login (boleh diubah nanti)
            'telepon'       => $data['telepon'] ?? null,
            'alamat'        => $data['alamat'] ?? null,
            'kota'          => $data['kota'] ?? null,
            'logo'          => $logoPath,
            'dokumen_legal' => $legalPath,
            'npwp'          => $data['npwp'] ?? null,
            'siup'          => $data['siup'] ?? null,
            'is_verified'   => false,
        ]);

        return redirect()
            ->route('perusahaan.index')
            ->with('success', 'Perusahaan ditambahkan. Akun company dibuat. Password: ' . $plainPassword);
    }

    public function edit(Perusahaan $perusahaan)
    {
        return view('perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $r, Perusahaan $perusahaan)
    {
        $data = $r->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'industri'  => ['nullable', 'string', 'max:100'],
            'website'   => ['nullable', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'], // email kontak publik, bukan login wajib
            'telepon'   => ['nullable', 'string', 'max:30'],
            'alamat'    => ['nullable', 'string', 'max:500'],
            'kota'      => ['nullable', 'string', 'max:100'],
            'logo'          => ['nullable', 'image', 'max:2048'],
            'dokumen_legal' => ['nullable', 'file', 'max:4096'],
            'npwp'      => ['nullable', 'string', 'max:100'],
            'siup'      => ['nullable', 'string', 'max:100'],
            'is_verified' => ['sometimes', 'boolean'],
        ]);

        // Upload baru (hapus lama bila ada)
        if ($r->hasFile('logo')) {
            if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }
            $data['logo'] = $r->file('logo')->store('company_logo', 'public');
        }

        if ($r->hasFile('dokumen_legal')) {
            if ($perusahaan->dokumen_legal && Storage::disk('public')->exists($perusahaan->dokumen_legal)) {
                Storage::disk('public')->delete($perusahaan->dokumen_legal);
            }
            $data['dokumen_legal'] = $r->file('dokumen_legal')->store('company_legal', 'public');
        }

        if (array_key_exists('is_verified', $data)) {
            $data['verified_at'] = $data['is_verified'] ? now() : null;
        }

        $perusahaan->update($data);

        // (opsional) sinkron nama ke user supaya navbar ikut berubah
        if ($perusahaan->user && $perusahaan->user->name !== $perusahaan->nama) {
            $perusahaan->user->update(['name' => $perusahaan->nama]);
        }

        return back()->with('success', 'Data perusahaan diperbarui.');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
            Storage::disk('public')->delete($perusahaan->logo);
        }
        if ($perusahaan->dokumen_legal && Storage::disk('public')->exists($perusahaan->dokumen_legal)) {
            Storage::disk('public')->delete($perusahaan->dokumen_legal);
        }
        $perusahaan->delete();

        return back()->with('success', 'Perusahaan dihapus.');
    }

    public function verifyToggle(Perusahaan $perusahaan)
    {
        $nowVerified = ! $perusahaan->is_verified;

        $perusahaan->update([
            'is_verified' => $nowVerified,
            'verified_at' => $nowVerified ? now() : null,
        ]);

        return back()->with('success', 'Status verifikasi diperbarui.');
    }

    /* =========================================================
     |  COMPANY AREA (owner perusahaan)
     |=========================================================*/
    public function dashboard()
    {
        $perusahaan = Perusahaan::where('user_id', Auth::id())->firstOrFail();
        return view('perusahaan.dashboard', compact('perusahaan'));
    }

    public function editOwn()
    {
        $perusahaan = Perusahaan::where('user_id', Auth::id())->firstOrFail();
        return view('perusahaan.edit_own', compact('perusahaan'));
    }

    public function updateOwn(Request $r)
    {
        $perusahaan = Perusahaan::where('user_id', Auth::id())->firstOrFail();

        $data = $r->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'industri'  => ['nullable', 'string', 'max:100'],
            'website'   => ['nullable', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'],
            'telepon'   => ['nullable', 'string', 'max:30'],
            'alamat'    => ['nullable', 'string', 'max:500'],
            'kota'      => ['nullable', 'string', 'max:100'],
            'logo'          => ['nullable', 'image', 'max:2048'],
            'dokumen_legal' => ['nullable', 'file', 'max:4096'],
            'npwp'      => ['nullable', 'string', 'max:100'],
            'siup'      => ['nullable', 'string', 'max:100'],
        ]);

        if ($r->hasFile('logo')) {
            if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }
            $data['logo'] = $r->file('logo')->store('company_logo', 'public');
        }
        if ($r->hasFile('dokumen_legal')) {
            if ($perusahaan->dokumen_legal && Storage::disk('public')->exists($perusahaan->dokumen_legal)) {
                Storage::disk('public')->delete($perusahaan->dokumen_legal);
            }
            $data['dokumen_legal'] = $r->file('dokumen_legal')->store('company_legal', 'public');
        }

        $perusahaan->update($data);

        if ($perusahaan->user && $perusahaan->user->name !== $perusahaan->nama) {
            $perusahaan->user->update(['name' => $perusahaan->nama]);
        }

        return redirect()->route('perusahaan.dashboard')->with('success', 'Profil perusahaan diperbarui.');
    }
}
