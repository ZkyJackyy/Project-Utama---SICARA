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

    // --- HELPER: Cari ID Kue Kustom (Private) ---
    private function getCustomCakeId() {
        $product = Product::where('nama_produk', 'Kue Kustom')->first();
        return $product ? $product->id : 0;
    }
    //ini
    public function dashboard()
    {
        $idCustom = $this->getCustomCakeId(); // Ambil ID dinamis

        $totalPenjualan = Transaksi::where('status', 'Selesai')->sum('total');
        $pesananBaru = Transaksi::whereIn('status', ['Menunggu Konfirmasi', 'Akan Diproses'])->count();
        $pesananDiproses = Transaksi::where('status', 'Diproses')->count();

        // Kecualikan Kue Kustom dari stok
        $totalStok = Product::whereNull('deleted_at')
            ->where('id', '!=', $idCustom) 
            ->sum('stok');

        $stokMenipisCount = Product::whereNull('deleted_at')
            ->where('id', '!=', $idCustom)
            ->where('stok', '<', 10)
            ->where('stok', '>', 0)
            ->count();

        $produkTerjual = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->where('status', 'Selesai');
        })->sum('jumlah');

        $pesananTerbaru = Transaksi::with(['user', 'detailTransaksi'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjualan', 'pesananBaru', 'pesananDiproses',
            'totalStok', 'stokMenipisCount', 'produkTerjual', 'pesananTerbaru'
        ));
    }

// API UNTUK AJAX DASHBOARD
    public function getDashboardStats()
    {
        $idCustom = $this->getCustomCakeId(); // Ambil ID dinamis

        $totalPenjualan = Transaksi::where('status', 'Selesai')->sum('total');
        $pesananBaru = Transaksi::whereIn('status', ['Menunggu Konfirmasi', 'Akan Diproses'])->count();
        $pesananDiproses = Transaksi::where('status', 'Diproses')->count();
        $totalStok = Product::whereNull('deleted_at')->where('id', '!=', $idCustom)->sum('stok');
        $produkTerjual = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->where('status', 'Selesai');
        })->sum('jumlah');

        $latestOrders = Transaksi::with(['user', 'detailTransaksi'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $isCustom = $order->detailTransaksi->contains(fn($d) => !empty($d->catatan));
                
                $statusClass = match($order->status) {
                    'Menunggu Konfirmasi' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                    'Akan Diproses' => 'bg-blue-50 text-blue-700 border-blue-100',
                    'Diproses' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                    'Selesai' => 'bg-green-50 text-green-700 border-green-100',
                    'Dibatalkan' => 'bg-red-50 text-red-700 border-red-100',
                    default => 'bg-gray-50 text-gray-600 border-gray-100'
                };

                return [
                    'id' => $order->id,
                    'kode' => $order->kode_transaksi ?? $order->id,
                    'waktu' => $order->created_at->diffForHumans(),
                    'customer_name' => $order->user->name ?? 'Guest',
                    'customer_email' => $order->user->email ?? '-',
                    'total' => number_format($order->total, 0, ',', '.'),
                    'status' => $order->status,
                    'status_class' => $statusClass,
                    'payment' => strtoupper(str_replace('_', ' ', $order->metode_pembayaran)),
                    'is_custom' => $isCustom,
                    'item_count' => $order->detailTransaksi->count(),
                    'link_detail' => route('admin.pesanan.show', $order->id)
                ];
            });

        return response()->json([
            'totalPenjualan' => number_format($totalPenjualan, 0, ',', '.'),
            'pesananBaru' => $pesananBaru,
            'pesananDiproses' => $pesananDiproses,
            'totalStok' => number_format($totalStok),
            'produkTerjual' => $produkTerjual,
            'orders' => $latestOrders
        ]);
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

// HALAMAN UTAMA (CUSTOMER)
    public function indexHome()
    {
        $idCustom = $this->getCustomCakeId(); // ID Dinamis

        $products = Product::query()
            ->where('id', '!=', $idCustom) // Jangan tampilkan kue kustom
            ->whereNull('deleted_at')      // Pastikan tidak terhapus
            ->withSum('detailTransaksi as total_terjual', 'jumlah')
            ->orderByDesc('total_terjual')
            ->take(3)
            ->get();

        if ($products->isEmpty() || $products->sum('total_terjual') == 0) {
            $products = Product::where('id', '!=', $idCustom)
                ->whereNull('deleted_at')
                ->latest()
                ->take(3)
                ->get();
        }

        return view('dashboard', compact('products'));
    }

// LIST PRODUK (CUSTOMER)
    public function daftarProdukCustomer()
    {
        $idCustom = $this->getCustomCakeId(); // ID Dinamis

        $products = Product::where('id', '!=', $idCustom)
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(8);
            
        $categories = Jenis::all();

        return view('customer.produk.list', compact('products', 'categories'));
    }

// DETAIL PRODUK
    public function showDetail(Product $product)
    {
        $idCustom = $this->getCustomCakeId(); // ID Dinamis

        $product->load(['ulasan.user' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $totalUlasan = $product->ulasan->count();
        $avgRating = $totalUlasan > 0 ? $product->ulasan->avg('rating') : 0;

        $relatedProducts = Product::where('jenis_id', $product->jenis_id)
            ->where('id', '!=', $product->id)
            ->where('id', '!=', $idCustom) // Exclude Custom Cake
            ->whereNull('deleted_at')
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
