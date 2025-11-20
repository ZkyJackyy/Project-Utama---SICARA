<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ulasan;

class UlasanController extends Controller
{
    public function store(Request $request, $id)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'ulasan' => 'nullable|string',
    ]);

    // Cek apakah pesanan sudah pernah diberi ulasan
    if (Ulasan::where('pesanan_id', $id)->exists()) {
        return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
    }

    Ulasan::create([
        'pesanan_id' => $id,
        'user_id' => auth()->id(),
        'rating' => $request->rating,
        'ulasan' => $request->ulasan,
    ]);

    return back()->with('success', 'Ulasan berhasil dikirim!');
}


    public function form($id)
{
    return view('customer.ulasan.form', [
        'pesanan_id' => $id
    ]);
}

}
