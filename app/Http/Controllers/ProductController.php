<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //ini
    public function dashboard()
    {
        // Card 1: Total Penjualan (Hanya menghitung pesanan yang sudah "Selesai")
        $totalPenjualan = Transaksi::where('status', 'Selesai')->sum('total');

        // Card 2: Pesanan Baru (Pesanan yang perlu diproses admin)
        $pesananBaru = Transaksi::whereIn('status', ['Menunggu Konfirmasi', 'Akan Diproses'])->count();

        // Card 3: Total Stok Produk (Stok dari produk yang 'deleted_at' nya NULL)
        // Saya asumsikan Anda memiliki kolom 'stok' di tabel 'products'
        $totalStok = Product::whereNull('deleted_at')->sum('stok');

        // Card 4: Produk Terjual (Hanya dari pesanan yang "Selesai")
        $produkTerjual = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->where('status', 'Selesai');
        })->sum('jumlah');

        // Tabel: Pesanan Terbaru (Ambil 5 data terbaru)
        $pesananTerbaru = Transaksi::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5) // Ambil 5 pesanan paling baru
            ->get();

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalPenjualan',
            'pesananBaru',
            'totalStok',
            'produkTerjual',
            'pesananTerbaru'
        ));
    }

    public function index()
    {
        $products = Product::withTrashed()->latest()->paginate(10); // <--- AMBIL SEMUA
        return view('admin.daftar_produk', compact('products'));
    }
    public function create()
    {
        $categories = Jenis::all();
        return view('admin.produk.tambah', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_id'    => 'required|exists:jenis,id',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // max 2MB
            'deskripsi'   => 'nullable|string',
        ]);

        // 2. Handle Upload Gambar
        $path = $request->file('gambar')->store('public/produk');
        $validatedData['gambar'] = basename($path);

        Product::create($validatedData);

        // 4. Redirect dengan Pesan Sukses
        return redirect()->route('produk.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $jenisProduk = Jenis::all();
        return view('admin.produk.edit', compact('product', 'jenisProduk'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_id'    => 'required|exists:jenis,id',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        // ✅ Update gambar kalau ada file baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau masih ada
            if ($product->gambar && Storage::exists('public/produk/' . $product->gambar)) {
                Storage::delete('public/produk/' . $product->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('public/produk');
            $validatedData['gambar'] = basename($path);
        }

        // ✅ Update data ke database
        $product->update($validatedData);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        try {
            // Hapus gambar jika ada
            if ($product->gambar) {
                Storage::delete('public/produk/' . $product->gambar);
            }

            // Hapus data produk dari database
            $product->forceDelete();

            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('produk.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function indexHome()
    {
        $products = Product::where('stok', '>', 0)->where('id', '!=', 23)->latest()->take(4)->get();
        
        return view('dashboard', compact('products'));
    }

    public function daftarProdukCustomer()
    {
        // Ambil semua produk, urutkan dari yang terbaru, dan gunakan paginasi
        // Paginate(8) berarti 8 produk per halaman
        $products = Product::where('stok', '>', 0)->where('id', '!=', 23)->latest()->paginate(8);
        $categories = Jenis::all();

        // Kirim data products ke view
        return view('customer.produk.list', compact('products', 'categories'));
    }

    public function showDetail(Product $product)
    {
        // Logika ini sudah benar dan akan bekerja dengan ID
        $relatedProducts = Product::where('jenis_id', $product->jenis_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        // Pastikan path view Anda benar, contoh: 'customer.detail'
        return view('customer.produk.detail', compact('product', 'relatedProducts'));
    }

    public function unpublish(Product $product)
    {
        $product->delete(); // Ini akan melakukan SOFT DELETE
        return redirect()->back()->with('success', 'Produk berhasil disembunyikan.');
    }

    public function publish(Product $product)
    {
        $product->restore(); // Ini akan mengembalikan produk dari "tong sampah"
        return redirect()->back()->with('success', 'Produk berhasil ditampilkan kembali.');
    }


    public function showCustomCakeForm()
{
    // GANTI 50 DENGAN ID PRODUK "Kue Kustom" ANDA DARI LANGKAH 1
    $product = Product::find(23); 

    if (!$product) {
        // Jika produk "Kue Kustom" tidak ditemukan
        return redirect()->route('customer.produk.list')->with('error', 'Halaman kustomisasi tidak tersedia.');
    }
    
    // Kirim data produk (untuk harga dasar) ke view
    return view('customer.produk.custom', compact('product'));
}
}
