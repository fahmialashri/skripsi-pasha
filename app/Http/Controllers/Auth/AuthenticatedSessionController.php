<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // LOGIN PAKAI NPM (student_id) UNTUK SEMUA ROLE
        $request->validate([
            'student_id' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

        $credentials = [
            'student_id' => $request->student_id,
            'password'   => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'student_id' => 'NPM atau password salah.',
            ])->onlyInput('student_id');
        }

        $request->session()->regenerate();

        // redirect sesuai role (admin / mahasiswa)
        $user = $request->user();

        return redirect()->intended(
            $user->role === 'admin'
                ? route('admin.dashboard')
                : route('student.dashboard')
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}