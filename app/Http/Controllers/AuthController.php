<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===== LOGIN =====
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
            }

            return redirect()->intended(route('menu.index'))
                ->with('success', 'Senang kamu kembali, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['username' => 'Nama pengguna atau kata sandi salah.'])
            ->onlyInput('username');
    }

    // ===== REGISTER =====
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|alpha_dash|min:4|max:30|unique:users,username',
            'email'       => 'required|email|max:255|unique:users,email',
            'no_whatsapp' => 'nullable|string|max:20',
            'password'    => 'required|string|min:6|confirmed',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'username.required'   => 'Nama pengguna wajib diisi.',
            'username.alpha_dash' => 'Nama pengguna hanya boleh huruf, angka, strip, dan garis bawah.',
            'username.min'        => 'Nama pengguna minimal 4 karakter.',
            'username.unique'     => 'Nama pengguna sudah dipakai.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.required'   => 'Kata sandi wajib diisi.',
            'password.min'        => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'        => $validated['name'],
            'username'    => $validated['username'],
            'email'       => $validated['email'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'password'    => Hash::make($validated['password']),
            'role'        => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('menu.index')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    // ===== LOGOUT =====
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')
            ->with('success', 'Kamu sudah keluar. Sampai jumpa!');
    }
}