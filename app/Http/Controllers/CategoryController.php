<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Jenis::all();
        return view('admin.kategori.daftar', compact('categories'));
    }

    public function create()
    {
        // Arahkan ke file view yang akan kita buat di langkah 3
        return view('admin.kategori.tambah'); 
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'jenis_produk' => 'required|string|max:255|',
        ]);


        // 3. Simpan data ke database
        Jenis::create($validatedData);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('category.index') // Asumsi Anda punya halaman daftar kategori
                         ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function edit(Jenis $category)
    {
        // Mengirim data kategori yang akan diedit ke view
        return view('admin.kategori.edit', compact('category'));
    }

    public function update(Request $request, Jenis $category)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            // Rule 'unique' memastikan tidak ada nama kategori yang sama,
            // kecuali untuk data yang sedang diedit itu sendiri.
            'jenis_produk' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis')->ignore($category->id),
            ]
        ]);

        // 2. Update data di database
        $category->update([
            'jenis_produk' => $validatedData['jenis_produk'],
        ]);

        // 3. Redirect ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('category.index')
        ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Jenis $category)
    {
        // Hapus data
        $category->delete();

        // Redirect ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('category.index')
        ->with('success', 'Kategori berhasil dihapus!');
    }
}
