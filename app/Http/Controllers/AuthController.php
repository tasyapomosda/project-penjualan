<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginView() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Staf diarahkan ke showcase, admin ke dashboard
            $default = $request->user()->isAdmin() ? '/admin' : '/';
            return redirect()->intended($default);
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function editPassword() {
         /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin tetap memakai halaman di dalam panel admin, staf memakai halaman sendiri
        return $user->isAdmin()
            ? view('auth.ganti-password')
            : view('auth.ganti-password-staf');
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        /** @var \App\Models\User $user */ 

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}