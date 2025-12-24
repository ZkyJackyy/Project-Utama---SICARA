<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\CustomCakeController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\NotificationController;

// ================== HOME (Customer) ==================
Route::get('/', [ProductController::class, 'indexHome'])->name('dashboard');

// ================== AUTH ==================
// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================== DASHBOARD (Role-based) ==================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [ProductController::class, 'dashboard'])->name('dashboard.admin');
     //sama ubah ini juga -- nashwa
    // CRUD Produk
    Route::get('/daftar-produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/tambah-produk', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/store-produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/hapus-produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

    // ✅ Tambahan: Manajemen Pesanan
    Route::get('/admin/pesanan', [App\Http\Controllers\PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('/admin/pesanan/{id}', [App\Http\Controllers\PesananController::class, 'show'])->name('admin.pesanan.show');
    Route::put('/admin/pesanan/{id}/status', [App\Http\Controllers\PesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');

    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
});


Route::middleware(['auth', 'role:user'])->group(function () {
    Route::view('/dashboard-user', 'dashboard')->name('dashboard.user');
});

// ================== LOGIN GOOGLE ==================
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');

// ================== CUSTOMER PROFIL ==================
Route::middleware('auth')->group(function () {
    Route::get('/profil', [UserController::class, 'showProfil'])->name('profile.show');
    Route::put('/profil/update', [UserController::class, 'updateProfil'])->name('profile.update');
Route::put('/password/update', [UserController::class, 'updatePassword'])->name('password.update');

});


//category
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');


//customer

Route::get('/products', [ProductController::class, 'daftarProdukCustomer'])->name('customer.produk.list');
Route::get('/produk/{product}', [ProductController::class, 'showDetail'])->name('customer.produk.detail');

Route::delete('/admin/produk/{product}/unpublish', [ProductController::class, 'unpublish'])->name('produk.unpublish');

// Route untuk PUBLISH (menampilkan kembali) produk
Route::post('/admin/produk/{product}/publish', [ProductController::class, 'publish'])
    ->name('produk.publish')
    ->withTrashed();

// ================== KERANJANG (CUSTOMER) ==================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah/{product}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::put('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
});

// ================== CHECKOUT (CUSTOMER) ==================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
    
    Route::get('/custom-cake', [CustomCakeController::class, 'showCustomCakeForm'])->name('custom-cake.index');
    Route::post('/custom-cake/store', [CustomCakeController::class, 'store'])->name('custom-cake.store');
Route::post('/keranjang/tambah-custom', [KeranjangController::class, 'tambahCustom'])->name('keranjang.tambahCustom');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan-saya', [App\Http\Controllers\PesananController::class, 'pesananCustomer'])
        ->name('customer.pesanan.index');
    Route::put('/pesanan-saya/{transaksi}/batal', [App\Http\Controllers\PesananController::class, 'batalPesanan'])
        ->name('customer.pesanan.batal');
});


Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

Route::post('/pesanan/{id}/ulasan', [UlasanController::class, 'store'])
    ->name('customer.ulasan.store');

    // Customer - Notifikasi
Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
Route::get('/notifikasi/read/{id}', [NotificationController::class, 'markRead'])->name('notifikasi.read');
Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllRead'])
    ->name('notifikasi.readAll');

Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index'); // List Tiket
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create'); // Form Lapor
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store'); // Simpan Lapor
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show'); // Detail Chat
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply'); // Balas Chat
    Route::post('/tickets/{id}/close', [TicketController::class, 'close'])->name('tickets.close'); // Tutup Tiket
    Route::get('/tickets/{id}/fetch', [TicketController::class, 'fetchMessages'])->name('tickets.fetch');
});

Route::get('/api/provinces', [RajaOngkirController::class, 'getProvinces']);
Route::get('/api/cities/{provinceId}', [RajaOngkirController::class, 'getCities']);
Route::post('/api/ongkir', [RajaOngkirController::class, 'checkOngkir']);




