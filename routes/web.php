<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
// 👇 Tambahan Import untuk fitur Adhi
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\FrontendAuth;

// ================= FRONTEND ROUTES =================

Route::view('/', 'home')->name('home');

// ======== AUTH (Versi Dex - Teruji) ========

// 1. Halaman Login (UI)
Route::view('/login', 'auth.login')->name('login');

// 2. Proses Login (POST)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        if (Auth::user()->role === 'admin') {
             return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard.index');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
})->name('login.perform');

// Halaman Register
Route::get('/register', function () {
    $response = Http::get('http://127.0.0.1:8000/api/paket');
    $paket = $response->json('data') ?? [];
    return view('auth.register', compact('paket'));
})->name('register.simulasi');

// Proses Logout
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.simulasi');


// ======== MEMBER PAGES (Materi & Video - Versi Dex API) ========
Route::prefix('member')->middleware('auth')->group(function () {

    Route::view('/dashboard', 'member.dashboard.index')->name('dashboard.index');

    // Route Materi (Fetch dari API Backend)
    Route::get('/materi', function () {
        $response = Http::get('http://127.0.0.1:8000/api/admin/materi');
        $semuaMateri = collect($response->json('data') ?? []);

        $materiLevel1 = $semuaMateri->where('level', 1)->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc'  => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Belajar via Video',
                'img'   => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80',
                'progress' => 0,
                'slug'  => Str::slug($item['judul'], '-')
            ];
        });

        $materiLevel2 = $semuaMateri->where('level', 2)->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc'  => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Materi Lanjutan',
                'img'   => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?auto=format&fit=crop&w=800&q=80',
                'progress' => 0,
                'slug'  => Str::slug($item['judul'], '-')
            ];
        });

        return view('member.dashboard.materi', [
            'materiLevel1' => $materiLevel1,
            'materiLevel2' => $materiLevel2
        ]);
    })->name('dashboard.materi');

    Route::view('/laporan', 'member.dashboard.laporan')->name('dashboard.laporan');
    Route::view('/sertifikasi', 'member.dashboard.sertifikasi')->name('dashboard.sertifikasi');

    // Route Detail Materi Teori
    Route::get('/teori/{slug}', function ($slug) {
        return view('member.dashboard.teori', ['slug' => $slug]);
    })->name('member.teori');

    // Route Kuis
    Route::get('/kuis/{slug}', function ($slug) {
        return view('member.dashboard.kuis.show', ['slug' => $slug]);
    })->name('member.kuis.show');

    // ✅ Route Video (VERSI BENAR - API FETCH)
    Route::get('/video/{slug}', function ($slug) {
        // 1. Tembak API Backend
        $response = Http::get("http://127.0.0.1:8000/api/materi/{$slug}");

        // 2. Cek jika materi tidak ditemukan
        if ($response->failed()) {
            abort(404, 'Materi tidak ditemukan di API.');
        }

        // 3. Ambil datanya
        $materi = $response->json('data');

        // 4. Kirim ke View
        return view('member.dashboard.video', [
            'slug' => $slug,
            'materi' => $materi
        ]);
    })->name('member.video');
});


// ======== PROFILE ROUTES (Fitur Adhi) ========
// Kita bungkus pakai middleware FrontendAuth sesuai kode Adhi
// Note: Pastikan class FrontendAuth sudah ada, kalau error bisa dihapus middleware-nya sementara
Route::middleware(['auth'])->group(function () {
    Route::view('/profile', 'profile.dashboard')->name('dashboard.profile');
    Route::view('/profile/edit', 'profile.edit')->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Route Session Tambahan Adhi (Kita simpan jaga-jaga)
Route::post('/frontend-login', function (Request $request) {
    session(['user' => $request->user]);
    return response()->json(['status' => 'ok']);
});

Route::post('/frontend-update-session', function (Request $request) {
    session(['user' => $request->all()]);
    return response()->json(['success' => true]);
});


// ======== ADMIN PAGES ========
Route::view('/admin/login', 'auth.loginadmin')->name('admin.login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/users', function () {
    $response = Http::get('http://127.0.0.1:8000/api/admin/users');
    $users = $response->json() ?? [];
    return view('Admin.Pages.users', compact('users'));
})->name('admin.users');

Route::view('/admin/paket', 'Admin.Pages.paket')->name('admin.paket');
Route::view('/admin/bahasa', 'Admin.Pages.bahasa')->name('admin.bahasa');
Route::view('/admin/materi', 'Admin.Pages.materi')->name('admin.materi');
Route::view('/admin/kuis', 'Admin.Pages.kuis')->name('admin.kuis');
Route::view('/admin/sertifikasi', 'Admin.Pages.sertifikasi')->name('admin.sertifikasi');
Route::view('/admin/sertifikat', 'Admin.Pages.sertifikat')->name('admin.sertifikat');
