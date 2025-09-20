<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        // 1. Logout pengguna dari guard 'web'
        Auth::guard('web')->logout();

        // 2. Invalidate session pengguna
        $request->session()->invalidate();

        // 3. Regenerate token CSRF untuk keamanan
        $request->session()->regenerateToken();

        // 4. Redirect ke halaman utama atau halaman login
        return redirect('/register');
    }
}
