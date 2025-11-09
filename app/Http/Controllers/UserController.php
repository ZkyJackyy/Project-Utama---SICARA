<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function showProfil(){
        $user = Auth::user();
        return view('customer.pages.profil', [
            'user' => Auth::user(),
            'hasPassword' => !is_null($user->password)
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = $request->user();

        // Validasi data yang masuk
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id) // Abaikan email user saat ini
            ],
            'alamat' => ['nullable', 'string', 'max:500'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

        // Update data user
        $user->update($validatedData); // Ini akan GAGAL jika $fillable di Model User salah

        // Kembalikan respons JSON yang sukses
        return response()->json([
            'message' => 'Profil berhasil diperbarui!',
            'user' => $user->fresh() // Mengambil data user terbaru dari database
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Tentukan aturan validasi dasar
        $rules = [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];

        // JIKA PENGGUNA SUDAH PUNYA PASSWORD (bukan null)
        if ($user->password) {
            // Maka, WAJIBKAN validasi password saat ini
            $rules['current_password'] = ['required', 'current_password'];
        } else {
            // JIKA PENGGUNA BELUM PUNYA PASSWORD (null)
            // Maka, abaikan field password saat ini (buat jadi opsional)
            $rules['current_password'] = ['nullable'];
        }
        
        // Validasi data berdasarkan aturan yang sudah disesuaikan
        $validatedData = $request->validate($rules);

        // Update password
        $request->user()->update([
            'password' => Hash::make($validatedData['password'])
        ]);

        // Kembalikan respons JSON yang sukses
        return response()->json([
            'message' => 'Password berhasil diperbarui!'
        ]);
    }
}
