<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;

Route::get('/', [ProductController::class, 'indexHome'])->name('dashboard');

// Halaman Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Halaman Register
Route::get('/register', function () {
    return view('auth.registrasi');
})->name('register');

// Halaman Dashboard
Route::get('/dashboard-admin', function () {
    return view('admin.dashboard');
})->name('dashboardAdmin');

//produk
Route::get('/daftar-produk',[ProductController::class,'index'])->name('produk.index');
Route::get('/tambah-produk',[ProductController::class,'create'])->name('tambahProduk');
Route::post('/store-produk', [ProductController::class, 'store'])->name('produk.store');
// Route::get('/detail-produk/{id}',[ProductController::class,'show'])->name('detailProduk');
Route::get('/produk/{product}/edit',[ProductController::class,'edit'])->name('produk.edit');
Route::put('/produk/{product}',[ProductController::class,'update'])->name('product.update');
Route::delete('/hapus-produk/{product}',[ProductController::class,'destroy'])->name('produk.destroy');


//login googel
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
// Rute untuk callback dari Google
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');


//auth
Route::middleware('auth')->group(function () {
    // ... rute lain yang butuh login ...

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


//customer
// Route::get('/profil',[UserController::class, 'showProfil']);
Route::middleware('auth')->group(function () {
    // Rute untuk menampilkan profil
    Route::get('/profil', [UserController::class, 'showProfil'])->name('profile.show');

    // Rute untuk memproses update data (TAMBAHKAN INI)
    Route::put('/profil/update', [UserController::class, 'updateProfil'])->name('profile.update');
});
