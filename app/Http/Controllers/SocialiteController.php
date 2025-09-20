<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google setelah otentikasi.
     */
    public function handleGoogleCallback()
    {
        try {
            // Mengambil data pengguna dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cari pengguna di database berdasarkan google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // Jika pengguna sudah ada, langsung login
                Auth::login($user);
                return redirect()->intended('/'); // Arahkan ke halaman setelah login
            } else {
                // Jika pengguna belum ada, cek apakah email sudah terdaftar
                $existingUser = User::where('email', $googleUser->getEmail())->first();
                if ($existingUser) {
                    // Jika email sudah ada, mungkin update google_id atau tampilkan pesan error
                    // Untuk contoh ini, kita anggap ini adalah error untuk mencegah duplikasi
                    return redirect('/login')->withErrors(['email' => 'Email ini sudah terdaftar dengan metode lain.']);
                }

                // Buat pengguna baru jika belum ada
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(24)) // Buat password acak
                ]);

                Auth::login($newUser);
                return redirect()->intended('/');
            }

        } catch (\Exception $e) {
            // Tangani jika ada error
            return redirect('/login')->withErrors(['msg' => 'Terjadi kesalahan saat login dengan Google.']);
        }
    }
}
