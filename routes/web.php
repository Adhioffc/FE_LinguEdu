<?php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

// ================= FRONTEND ROUTES =================

Route::view('/', 'home')->name('home');

// ======== AUTH ========

Route::view('/login', 'auth.login')->name('login');

Route::post('/login', function (Request $request) {
    // Validasi form
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Normalisasi email ke lowercase
    $email = strtolower($request->input('email'));
    $password = $request->input('password');

    // Cari user berdasarkan email (case-insensitive untuk jaga-jaga)
    $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

    // Cek user & password
    if (!$user || !Hash::check($password, $user->password)) {
        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

    // (Opsional) kalau mau blokir member yang belum aktif, taruh di sini
    // if ($user->role === 'member' && is_null($user->email_verified_at)) {
    //     return back()
    //         ->withErrors(['email' => 'Akun Anda belum aktif. Silakan hubungi admin.'])
    //         ->withInput();
    // }

    // Login manual
    Auth::login($user);
    $request->session()->regenerate();

    // Redirect berdasarkan role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('dashboard.index');
})->name('login.perform');

Route::get('/register', function () {
    $response = Http::get('http://127.0.0.1:8000/api/paket');
    $paket = $response->json('data') ?? [];
    return view('auth.register', compact('paket'));
})->name('register.simulasi');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.simulasi');


// ======== MEMBER PAGES ========
Route::prefix('member')->middleware('auth')->group(function () {

    Route::view('/dashboard', 'member.dashboard.index')->name('dashboard.index');

    // ✅ MATERI ROUTE (API MEMBER + LOGIC UNLOCK)
    // ✅ MATERI ROUTE (UPDATED: Cek Level Gembok)
    Route::get('/materi', function () {
        $userId = Auth::id();

        // 1. Ambil Level Terakhir User dari Database
        // Kita pakai Query Builder langsung biar praktis
        $userLevel = DB::table('registrasi_kursus')
            ->where('id_member', $userId)
            ->value('last_unlocked_level') ?? 1; // Default 1 kalau belum ada data

        // 2. Ambil Data Materi (Sama kayak sebelumnya)
        $response = Http::get('http://127.0.0.1:8000/api/member/materi-list', [
            'id_member' => $userId,
        ]);
        $semuaMateri = collect($response->json('data') ?? []);

        // 3. Logic Tombol Level 1 Selesai (Sama kayak sebelumnya)
        $isLevel1Complete = $semuaMateri->where('level', 1)->every(function ($item) {
            return $item['progress'] == 100;
        });

        // 4. Mapping Data (Sama kayak sebelumnya)
        $materiLevel1 = $semuaMateri->where('level', 1)->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc'  => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Belajar via Video',
                'img'   => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80',
                'progress' => $item['progress'],
                'slug'  => $item['slug']
            ];
        });

        $materiLevel2 = $semuaMateri->where('level', 2)->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc'  => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Materi Lanjutan',
                'img'   => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?auto=format&fit=crop&w=800&q=80',
                'progress' => $item['progress'],
                'slug'  => $item['slug']
            ];
        });

        return view('member.dashboard.materi', [
            'materiLevel1' => $materiLevel1,
            'materiLevel2' => $materiLevel2,
            'isLevel1Finished' => $isLevel1Complete,
            'userLevel' => $userLevel // <--- KITA KIRIM DATA LEVEL KE VIEW
        ]);
    })->name('dashboard.materi');

    Route::view('/laporan', 'member.dashboard.laporan')->name('dashboard.laporan');
    Route::view('/sertifikasi', 'member.dashboard.sertifikasi')->name('dashboard.sertifikasi');

    Route::get('/teori/{slug}', function ($slug) {
        return view('member.dashboard.teori', ['slug' => $slug]);
    })->name('member.teori');

    Route::get('/kuis/{slug}', function ($slug) {
        return view('member.dashboard.kuis.show', ['slug' => $slug]);
    })->name('member.kuis.show');

    // VIDEO ROUTE
    Route::get('/video/{slug}', function ($slug) {
        $response = Http::get("http://127.0.0.1:8000/api/materi/{$slug}");
        if ($response->failed()) {
            abort(404, 'Materi tidak ditemukan di API.');
        }
        $materi = $response->json('data');
        return view('member.dashboard.video', [
            'slug' => $slug,
            'materi' => $materi
        ]);
    })->name('member.video');
});


// ======== PROFILE ROUTES (LOGIC LANGSUNG) ========
Route::middleware(['auth'])->group(function () {
    Route::view('/profile', 'profile.dashboard')->name('dashboard.profile');
    Route::view('/profile/edit', 'profile.edit')->name('profile.edit');

    Route::put('/profile/update', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    })->name('profile.update');
});

// Route Session Tambahan Adhi
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
