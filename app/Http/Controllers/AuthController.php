<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login dan redirect berdasarkan role.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect berdasarkan role
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_KEPSEK])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('guru.dashboard'));
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
