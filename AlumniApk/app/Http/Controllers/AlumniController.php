<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\AlumniExport;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $alumni = Alumni::with('user')
            // alumni hanya melihat datanya sendiri
            ->when(Auth::check() && Auth::user()->role === 'alumni', function ($query) {
                $query->where('user_id', Auth::id());
            })
            // pencarian
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('nis', 'like', "%{$q}%")
                        ->orWhere('nisn', 'like', "%{$q}%")
                        ->orWhere('angkatan', 'like', "%{$q}%")
                        ->orWhere('jurusan', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('alumni.index', compact('alumni', 'q'));
    }


    public function export(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = Alumni::with('user')
            // alumni hanya boleh export datanya sendiri
            ->when(Auth::check() && Auth::user()->role === 'alumni', function ($query) {
                $query->where('user_id', Auth::id());
            })
            // filter pencarian sama seperti index()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('nis', 'like', "%{$q}%")
                        ->orWhere('nisn', 'like', "%{$q}%")
                        ->orWhere('angkatan', 'like', "%{$q}%")
                        ->orWhere('jurusan', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%");
                });
            })
            ->latest();

        // ambil semua (tanpa paginate)
        $rows = $query->get();

        $fileName = 'data_alumni_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AlumniExport($rows), $fileName);
    }


    public function create()
    {
        return view('alumni.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis'           => ['required','string','max:30','unique:alumnis,nis'],
            'nisn'          => ['required','string','max:30','unique:alumnis,nisn'],
            'nama'          => ['required','string','max:255'],
            'email'         => ['required','email','max:255','unique:alumnis,email','unique:users,email'],
            'phone'         => ['nullable','string','max:20','unique:users,phone'],
            'jenis_kelamin' => ['nullable', Rule::in(['L','P'])],
            'nama_ortu'     => ['nullable','string','max:255'],
            'sttp'          => ['nullable','string','max:100'],
            'angkatan'      => ['nullable','string','max:10'],
            'jurusan'       => ['nullable','string','max:100'],
            'pekerjaan'     => ['nullable','string','max:100'],
            'perusahaan'    => ['nullable','string','max:150'],
            'alamat'        => ['nullable','string','max:255'],
            'tempat_lahir'  => ['nullable','string','max:100'],
            'tanggal_lahir' => ['nullable','date'],
            'foto'          => ['nullable','image','max:2048'],
            'password'      => ['required','string','min:8','confirmed'],
        ]);

        DB::transaction(function () use ($request, $data) {
            // 1) buat user role alumni
            $user = User::create([
                'name'     => $data['nama'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'role'     => 'alumni',
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(), // opsional
            ]);

            // 2) simpan foto (jika ada)
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('alumni_foto', 'public');
            }

            // 3) buat data alumni
            Alumni::create([
                'user_id'       => $user->id,
                'nis'           => $data['nis'],
                'nisn'          => $data['nisn'],
                'nama'          => $data['nama'],
                'email'         => $data['email'],
                'phone'         => $data['phone'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
                'nama_ortu'     => $data['nama_ortu'] ?? null,
                'sttp'          => $data['sttp'] ?? null,
                'angkatan'      => $data['angkatan'] ?? null,
                'jurusan'       => $data['jurusan'] ?? null,
                'pekerjaan'     => $data['pekerjaan'] ?? null,
                'perusahaan'    => $data['perusahaan'] ?? null,
                'alamat'        => $data['alamat'] ?? null,
                'tempat_lahir'  => $data['tempat_lahir'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'foto'          => $fotoPath,
            ]);
        });

        return redirect()->route('alumni.index')->with('success', 'Alumni berhasil ditambahkan.');
    }

    public function show(Alumni $alumni)
    {
        $alumni->load('user');
        if (Auth::user()->role === 'alumni' && $alumni->user_id !== Auth::id()) {
            abort(403);
        }
        return view('alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        $alumni->load('user');
        if (Auth::user()->role === 'alumni' && $alumni->user_id !== Auth::id()) {
            abort(403);
        }
        return view('alumni.edit', compact('alumni'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        if (Auth::user()->role === 'alumni' && $alumni->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'nis'   => ['required','string','max:30', Rule::unique('alumnis','nis')->ignore($alumni->id, 'id')],
            'nisn'  => ['required','string','max:30', Rule::unique('alumnis','nisn')->ignore($alumni->id, 'id')],
            'nama'  => ['required','string','max:255'],
            'email' => ['required','email','max:255',
                Rule::unique('alumnis','email')->ignore($alumni->id, 'id'),
                Rule::unique('users','email')->ignore($alumni->user_id, 'id'),
            ],
            'phone' => ['nullable','string','max:20',
                Rule::unique('users','phone')->ignore($alumni->user_id, 'id'),
            ],
            'jenis_kelamin' => ['nullable', Rule::in(['L','P'])],
            'nama_ortu'     => ['nullable','string','max:255'],
            'sttp'          => ['nullable','string','max:100'],
            'angkatan'      => ['nullable','string','max:10'],
            'jurusan'       => ['nullable','string','max:100'],
            'pekerjaan'     => ['nullable','string','max:100'],
            'perusahaan'    => ['nullable','string','max:150'],
            'alamat'        => ['nullable','string','max:255'],
            'tempat_lahir'  => ['nullable','string','max:100'],
            'tanggal_lahir' => ['nullable','date'],
            'foto'          => ['nullable','image','max:2048'],
        ]);

        DB::transaction(function () use ($request, $alumni, $data) {
            $update = $data;

            if ($request->hasFile('foto')) {
                if ($alumni->foto && Storage::disk('public')->exists($alumni->foto)) {
                    Storage::disk('public')->delete($alumni->foto);
                }
                $update['foto'] = $request->file('foto')->store('alumni_foto', 'public');
            }

            $alumni->update($update);

            // sinkron sebagian field ke users
            if ($alumni->user) {
                $alumni->user->update([
                    'name'  => $alumni->nama,
                    'email' => $alumni->email,
                    'phone' => $alumni->phone,
                ]);
            }
        });

        // Ambil URL tujuan dari form (fallback ke index)
        $returnUrl = $request->input('return_url', route('alumni.index'));

        // Hindari open-redirect: pastikan masih di domain sendiri
        if (! Str::startsWith($returnUrl, url('/'))) {
            $returnUrl = route('alumni.index');
        }

        // Kalau URL tujuan ternyata halaman edit sendiri, fallback ke index biar tidak loop
        if (url()->current() === $returnUrl) {
            $returnUrl = route('alumni.index');
        }

        return redirect()->route('alumni.show', $alumni->id)->with('success', 'Data alumni berhasil diperbarui.');

    }

    public function destroy(Alumni $alumni)
    {
        DB::transaction(function () use ($alumni) {
            if ($alumni->foto && Storage::disk('public')->exists($alumni->foto)) {
                Storage::disk('public')->delete($alumni->foto);
            }
            // hapus user juga jika perlu:
            if ($alumni->user) {
                $alumni->user->delete();
            }
            $alumni->delete();
        });

        return back()->with('success', 'Data alumni dihapus.');
    }

    // Optional: halaman biodata grid
    public function biodata(Request $request)
    {
        $q = trim((string)$request->get('q', ''));

        $alumni = Alumni::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('jurusan', 'like', "%{$q}%")
                        ->orWhere('angkatan', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('alumni.biodata', compact('alumni', 'q'));
    }
}
