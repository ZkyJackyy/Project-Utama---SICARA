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
        // 1. Total Pendapatan (Hanya yang Selesai - Uang nyata)
        $totalPenjualan = Transaksi::where('status', 'Selesai')->sum('total');

        // 2. Pesanan Baru (URGENT: Status 'Menunggu Konfirmasi')
        // Logika: Ini adalah angka notifikasi yang membutuhkan tindakan Admin segera.
        $pesananBaru = Transaksi::whereIn('status', ['Menunggu Konfirmasi', 'Akan Diproses'])->count();

        // 3. Pesanan Aktif (Sedang Dapur/Proses)
        // Logika: Ini pesanan yang sudah oke, tinggal dibuat/dikirim.
        $pesananDiproses = Transaksi::where('status', 'Diproses')->count();

        // 4. Total Stok Produk (KECUALI Custom Cake ID 23)
        // Logika: Custom cake stoknya 999 (dummy), jadi harus dibuang dari hitungan agar data akurat.
        $totalStok = Product::whereNull('deleted_at')
            ->where('id', '!=', 23) // <-- ID Custom Cake dikecualikan
            ->sum('stok');

        // 5. Produk Terjual (Hanya dari pesanan Selesai)
        $produkTerjual = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->where('status', 'Selesai');
        })->sum('jumlah');

        // 6. Tabel Pesanan Terbaru (Ambil 5)
        $pesananTerbaru = Transaksi::with(['user', 'detailTransaksi']) // Eager load untuk performa
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjualan',
            'pesananBaru',
            'pesananDiproses',
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
        $idProdukKustom = 23;
        $products = Product::query()
            ->where('id', '!=', $idProdukKustom) // 1. Jangan tampilkan kue kustom              // 2. Pastikan stok ada
            ->withSum('detailTransaksi as total_terjual', 'jumlah') // 3. Hitung total 'jumlah' dari tabel detail_transaksi
            ->orderByDesc('total_terjual')       // 4. Urutkan dari yang paling banyak terjual
            ->take(3)                            // 5. Ambil 3 atau 4 produk teratas
            ->get();


        // Jika data penjualan masih kosong (toko baru), 
        // fallback ke produk terbaru agar tidak kosong
        if ($products->isEmpty() || $products->sum('total_terjual') == 0) {
            $products = Product::where('id', '!=', $idProdukKustom)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('dashboard', compact('products'));
    }

    public function daftarProdukCustomer()
    {
        // Ambil semua produk, urutkan dari yang terbaru, dan gunakan paginasi
        // Paginate(8) berarti 8 produk per halaman
        $products = Product::where('id', '!=', 23)->latest()->paginate(8);
        $categories = Jenis::all();

        // Kirim data products ke view
        return view('customer.produk.list', compact('products', 'categories'));
    }

    public function showDetail(Product $product)
    {
        // 1. Eager load ulasan beserta user yang menulisnya agar hemat query
        // Urutkan dari ulasan terbaru
        $product->load(['ulasan.user' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        // 2. Hitung statistik rating (Opsional, untuk tampilan bintang)
        $totalUlasan = $product->ulasan->count();
        $avgRating = $totalUlasan > 0 ? $product->ulasan->avg('rating') : 0;

        // 3. Produk Terkait (Logika lama Anda)
        $relatedProducts = Product::where('jenis_id', $product->jenis_id)
            ->where('id', '!=', $product->id)->where('id', '!=', 23)
            ->latest()
            ->take(4)
            ->get();

        return view('customer.produk.detail', compact('product', 'relatedProducts', 'totalUlasan', 'avgRating'));
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
}
