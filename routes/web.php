<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\Environment\Console;

// ================= FRONTEND ROUTES (DEDICATED UI ONLY) =================

// Halaman utama
Route::view('/', 'home')->name('home');

// ======== AUTH FRONTEND (HANYA UI, TANPA LOGIKA) ========

// Login Page UI
Route::view('/login', 'auth.login')->name('login.simulasi');

// Register Page UI
Route::view('/register', 'auth.register')->name('register.simulasi');

// Logout UI (sementara pakai session simulasi)
Route::get('/logout', function () {
    session()->forget('user');
    return redirect()->route('login.simulasi');
})->name('logout.simulasi');

/*
|--------------------------------------------------------------------------
| 🚫 BACKEND LOGIN SIMULASI (NANTI DIPINDAH KE CONTROLLER)
|--------------------------------------------------------------------------
| Ini nanti digantikan oleh axios POST /api/login ke backend
| Untuk sekarang dikomentar biar tidak bentrok
|--------------------------------------------------------------------------
*/
// Route::post('/login', function (Request $request) {
//     $email = $request->email;
//     $password = $request->password;

//     if ($email === 'adminlinguedu@gmail.com' && $password === 'admin1234') {
//         session(['user_role' => 'admin']);
//         session(['user_email' => $email]);
//         return redirect()->route('admin.dashboard');
//     }

//     session(['user_role' => 'user']);
//     session(['user_email' => $email]);
//     return redirect()->route('dashboard.index');
// })->name('login.simulasi.post');


// ======== DASHBOARD USER PAGES ========
Route::prefix('member')->group(function () {
    Route::view('/dashboard', 'member.dashboard.index')->name('dashboard.index');
    Route::view('/materi', 'member.dashboard.materi')->name('dashboard.materi');
    Route::view('/laporan', 'member.dashboard.laporan')->name('dashboard.laporan');
    Route::view('/sertifikasi', 'member.dashboard.sertifikasi')->name('dashboard.sertifikasi');
});

Route::view('/dashboard/video', 'member.dashboard.video')->name('dashboard.video');
Route::get('/member/video/{slug}', function ($slug) {
    return view('member.dashboard.video', ['slug' => $slug]);
})->name('member.video');

Route::get('/member/teori', function () {
    return view('member.dashboard.teori');
})->name('member.teori');


// ======== ADMIN UI ONLY (Blade) ========
Route::view('/admin/login', 'auth.loginadmin')->name('admin.login');

/*
|--------------------------------------------------------------------------
| 🚫 BACKEND LOGIN ADMIN (NANTI PAKE CONTROLLER DAN JWT)
|--------------------------------------------------------------------------
*/
// Route::post('/admin/login', function (Request $request) {
//     session(['admin' => true]);
//     return redirect('/admin/dashboard');
// })->name('admin.login.post');

Route::get('/admin/dashboard', function () {
    return view('Admin.dashboard');
})->name('admin.dashboard');


// Admin Pages UI Only
// Route::get('/admin/users', function () {
//     $users = await User::all();

//     return view('Admin.Pages.users', [
//         'users' => $users
//     ]);
// })->name('admin.users');
Route::get('/admin/users', function () {

    // Ambil data dari backend API
    $response = Http::get('http://127.0.0.1:8000/api/admin/users');

    $users = $response->json();

    return view('Admin.Pages.users', compact('users'));
})->name('admin.users');
Route::view('/admin/paket', 'Admin.Pages.paket')->name('admin.paket');
Route::view('/admin/materi', 'Admin.Pages.materi')->name('admin.materi');
Route::view('/admin/kuis', 'Admin.Pages.kuis')->name('admin.kuis');
Route::view('/admin/sertifikasi', 'Admin.Pages.sertifikasi')->name('admin.sertifikasi');
