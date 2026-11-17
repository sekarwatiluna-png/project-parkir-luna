<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\TiketController;


// =====================================================
// LOGIN
// =====================================================

Route::post('/login', [
    AuthController::class,
    'login'
]);


// =====================================================
// FORGOT PASSWORD
// =====================================================

Route::post('/forgot-password/check-email', [
    ForgotPasswordController::class,
    'checkEmail'
]);

Route::post('/forgot-password/check-phone', [
    ForgotPasswordController::class,
    'checkPhone'
]);

Route::post('/forgot-password/verify-otp', [
    ForgotPasswordController::class,
    'verifyOtp'
]);

Route::post('/forgot-password/reset', [
    ForgotPasswordController::class,
    'resetPassword'
]);


// =====================================================
// MEMBER
// =====================================================

// TAMBAH MEMBER
Route::post('/members', [
    MemberController::class,
    'store'
]);

// ROUTE LAMA - tetap dipertahankan agar tidak merusak
// frontend atau proses lain yang masih menggunakan /member
Route::post('/member', [
    MemberController::class,
    'store'
]);

// CEK MEMBER
Route::post('/member/check', [
    MemberController::class,
    'check'
]);

// CEK MEMBER BERDASARKAN TOKEN
Route::get('/member/check/{token}', [
    MemberController::class,
    'checkToken'
]);

// LIST MEMBER
Route::get('/members', [
    MemberController::class,
    'index'
]);

// DETAIL MEMBER
Route::get('/members/{id}', [
    MemberController::class,
    'show'
]);

// UPDATE MEMBER
Route::put('/members/{id}', [
    MemberController::class,
    'update'
]);

// HAPUS MEMBER
Route::delete('/members/{id}', [
    MemberController::class,
    'destroy'
]);

// PEMBAYARAN MEMBER
Route::put('/members/{id}/pembayaran', [
    MemberController::class,
    'pembayaran'
]);


// =====================================================
// PETUGAS
// =====================================================

// LIST PETUGAS
Route::get('/petugas', [
    PetugasController::class,
    'index'
]);

// TAMBAH PETUGAS
Route::post('/petugas', [
    PetugasController::class,
    'store'
]);

// DETAIL PETUGAS
Route::get('/petugas/{id}', [
    PetugasController::class,
    'show'
]);

// UPDATE PETUGAS
Route::put('/petugas/{id}', [
    PetugasController::class,
    'update'
]);

// HAPUS PETUGAS
Route::delete('/petugas/{id}', [
    PetugasController::class,
    'destroy'
]);


// =====================================================
// TIKET
// =====================================================

// LIST TIKET
Route::get('/tiket', [
    TiketController::class,
    'index'
]);

// TAMBAH TIKET
Route::post('/tiket', [
    TiketController::class,
    'store'
]);

// KOMPATIBILITAS ROUTE LAMA
Route::get('/tiketparkir', [
    TiketController::class,
    'index'
]);

Route::post('/tiketparkir', [
    TiketController::class,
    'store'
]);


// =====================================================
// SCAN PARKIR
// =====================================================

// SCAN MASUK
Route::post('/scan/masuk', [
    TiketController::class,
    'scanMasuk'
]);

// SCAN KELUAR
Route::post('/scan/keluar', [
    TiketController::class,
    'scanKeluar'
]);

// SELESAIKAN TRANSAKSI KELUAR
Route::post('/scan/keluar/selesaikan', [
    TiketController::class,
    'selesaikanKeluar'
]);


// =====================================================
// AUTH / SANCTUM
// =====================================================

Route::middleware('auth:sanctum')->group(function () {

    // LOGOUT
    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    // DATA USER YANG SEDANG LOGIN
    Route::get('/me', [
        AuthController::class,
        'me'
    ]);

});