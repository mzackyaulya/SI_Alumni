<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','string','email','max:255','unique:users,email'],
            'phone'    => ['required','string','max:20','unique:users,phone'],
            'password' => ['required','confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated) {
            // 1) Buat user
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'],
                'role'     => 'alumni',
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            // 2) Buat data alumni minimal
            Alumni::create([
                'user_id' => $user->id,
                'nama'    => $validated['name'],
                'email'   => $validated['email'],
                'phone'   => $validated['phone'],
                'nis'     => 'REG-'.str_pad((string)$user->id, 6, '0', STR_PAD_LEFT),
                'nisn'    => 'PENDING',
            ]);

            return $user;
        });

        Auth::login($user);

        // pastikan relasi sudah ada/terload
        $user->load('alumni');
        $alumniId = optional($user->alumni)->id
            ?? Alumni::where('user_id', $user->id)->value('id');

        return $alumniId
            ? redirect()->route('alumni.show', $alumniId)
                ->with('success','Akun berhasil dibuat. Lengkapi biodata Anda.')
            : redirect()->route('alumni.biodata')
                ->with('success','Akun berhasil dibuat.');
    }
}
