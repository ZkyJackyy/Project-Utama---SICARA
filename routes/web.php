<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;

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
    Route::view('/dashboard-admin', 'admin.dashboard')->name('dashboard.admin');

    // CRUD Produk hanya untuk admin
    Route::get('/daftar-produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/tambah-produk', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/store-produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/hapus-produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');
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
});


//category
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');


//customer

Route::get('/products', [ProductController::class, 'daftarProdukCustomer'])->name('produk.list');
Route::get('/produk/{product}', [ProductController::class, 'showDetail'])->name('produk.detail');

Route::delete('/admin/produk/{product}/unpublish', [ProductController::class, 'unpublish'])->name('produk.unpublish');

// Route untuk PUBLISH (menampilkan kembali) produk
Route::post('/admin/produk/{product}/publish', [ProductController::class, 'publish'])
    ->name('produk.publish')
    ->withTrashed();