<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $alumni = Alumni::query()
            ->with('user')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('nisn', 'like', "%{$q}%")
                        ->orWhere('angkatan', 'like', "%{$q}%")
                        ->orWhere('jurusan', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('alumni.index', compact('alumni', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alumni.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255',
                'unique:alumnis,email',
                'unique:users,email',
            ],
            'phone'    => ['required', 'string', 'max:20',
                'unique:users,phone',
            ],
            'nisn'     => ['required', 'string', 'max:30', 'unique:alumnis,nisn'],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'jurusan'  => ['nullable', 'string', 'max:100'],
            'alamat'   => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($data) {
            // 1) Buat user role=alumni (password random → user set sendiri via email)
            $tempPassword = Str::random(12);

            $user = User::create([
                'name'              => $data['nama'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'role'              => 'alumni',
                'password'          => Hash::make($tempPassword),
                'email_verified_at' => now(), // opsional, hapus kalau pakai verifikasi email
            ]);

            // 2) Buat record alumni
            Alumni::create([
                'user_id'       => $user->id,
                'nisn'          => $data['nisn'],
                'nama'          => $data['nama'],
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'angkatan'      => $data['angkatan'] ?? null,
                'jurusan'       => $data['jurusan'] ?? null,
                'alamat'        => $data['alamat'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            ]);

            // 3) Kirim link reset password supaya user set password sendiri
            Password::sendResetLink(['email' => $user->email]);
        });

        return redirect()
            ->route('alumni.index')
            ->with('success', 'Alumni berhasil ditambahkan. Link setel password telah dikirim ke email.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumni $alumni)
    {
        $alumni->load('user');
        return view('alumni.show', compact('alumni'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni)
    {
        $alumni->load('user');
        return view('alumni.edit', compact('alumni'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255',
                Rule::unique('alumnis', 'email')->ignore($alumni->id, 'id'),
                Rule::unique('users', 'email')->ignore($alumni->user_id, 'id'),
            ],
            'phone'    => ['required', 'string', 'max:20',
                Rule::unique('users', 'phone')->ignore($alumni->user_id, 'id'),
            ],
            'nisn'     => ['required', 'string', 'max:30',
                Rule::unique('alumnis', 'nisn')->ignore($alumni->id, 'id'),
            ],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'jurusan'  => ['nullable', 'string', 'max:100'],
            'alamat'   => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($alumni, $data) {
            // Update alumni
            $alumni->update([
                'nisn'          => $data['nisn'],
                'nama'          => $data['nama'],
                'email'         => $data['email'],
                'phone'         => $data['phone'],
                'angkatan'      => $data['angkatan'] ?? null,
                'jurusan'       => $data['jurusan'] ?? null,
                'alamat'        => $data['alamat'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            ]);

            // Sinkron ke user
            if ($alumni->user) {
                $alumni->user->update([
                    'name'  => $data['nama'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ]);
            }
        });

        return redirect()
            ->route('alumni.index')
            ->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni)
    {
        //
    }
}
