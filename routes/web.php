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
    $data = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 1. Panggil API backend
    $response = Http::post('http://127.0.0.1:8000/api/login', $data);

    if ($response->status() === 401) {
        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

    if ($response->status() === 403) {
        return back()
            ->withErrors(['email' => 'Akun Anda belum aktif. Hubungi admin.'])
            ->withInput();
    }

    if ($response->failed()) {
        return back()
            ->withErrors(['email' => 'Server bermasalah. Coba lagi nanti.'])
            ->withInput();
    }

    // 2. Ambil data user dari API
    $apiUser = $response->json('user');
    $token = $response->json('token');

    // 3. Login-kan ke Laravel guard "web"
    //    (pastikan FE pakai DB yang sama dengan backend)
    $userModel = User::find($apiUser['id'])
        ?? User::where('email', $apiUser['email'])->first();

    if ($userModel) {
        Auth::login($userModel);
        $request->session()->regenerate();
    }

    // 4. Simpan juga token API kalau nanti mau dipakai
    session([
        'api_user' => $apiUser,
        'api_token' => $token,
    ]);

    // 5. Redirect sesuai role
    if ($apiUser['role'] === 'admin') {
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
    $request->session()->forget(['api_user', 'api_token']);
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.simulasi');


// ======== MEMBER PAGES ========
Route::prefix('member')->middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Ambil registrasi kursus terakhir milik user ini
        $registrasi = DB::table('registrasi_kursus as r')
            ->join('kursus as k', 'r.id_course', '=', 'k.id_course')
            ->join('bahasa as b', 'k.id_bahasa', '=', 'b.id')
            ->join('paket as p', 'k.id_paket', '=', 'p.id')
            ->select(
                'r.progress',
                'r.level',          // di DB kamu sekarang isinya "Beginner / Intermediate / Advanced"
                'b.nama_bahasa',
                'p.nama_paket',
                'k.id_course'
            )
            ->where('r.id_member', $user->id) // pastikan id_member = users.id
            ->orderByDesc('r.created_at')
            ->first();

        // Siapkan nilai default kalau belum pernah registrasi kursus
        $courseName = null;
        $packageName = null;
        $languageName = null;
        $progress = 0;
        $levelLabel = null;

        if ($registrasi) {
            $languageName = $registrasi->nama_bahasa;       // contoh: "Bahasa Jepang"
            $packageName = $registrasi->nama_paket;        // contoh: "Intermediate"
            $courseName = $languageName . ' - ' . $packageName;
            $progress = (int) $registrasi->progress;    // 0–100
            $levelLabel = $registrasi->level;             // contoh: "Intermediate"
        }

        return view('member.dashboard.index', compact(
            'user',
            'courseName',
            'packageName',
            'languageName',
            'progress',
            'levelLabel'
        ));
    })->name('dashboard.index');

    // ✅ MATERI ROUTE (API MEMBER + LOGIC UNLOCK)
    // ✅ MATERI ROUTE (UPDATED: Cek Level Gembok)
    Route::get('/materi', function () {
        $userId = Auth::id();

        // 1. Ambil level terakhir yang kebuka (last_unlocked_level)
        $userLevel = DB::table('registrasi_kursus')
            ->where('id_member', $userId)
            ->value('last_unlocked_level') ?? 1;

        // 2. Ambil semua materi user dari API backend
        $response = Http::get('http://127.0.0.1:8000/api/member/materi-list', [
            'id_member' => $userId,
        ]);
        $semuaMateri = collect($response->json('data') ?? []);

        // ==== LEVEL 1 ====
        $level1Collection = $semuaMateri->where('level', 1);
        $isLevel1Complete = $level1Collection->count() > 0 &&
            $level1Collection->every(fn($item) => $item['progress'] == 100);
        $materiLevel1 = $level1Collection->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc' => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Belajar via Video',
                'img' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80',
                'progress' => $item['progress'],
                'slug' => $item['slug'],
            ];
        });

        // ==== LEVEL 2 ====
        $level2Collection = $semuaMateri->where('level', 2);
        $isLevel2Complete = $level2Collection->count() > 0 &&
            $level2Collection->every(fn($item) => $item['progress'] == 100);
        $materiLevel2 = $level2Collection->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc' => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Materi Lanjutan',
                'img' => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?auto=format&fit=crop&w=800&q=80',
                'progress' => $item['progress'],
                'slug' => $item['slug'],
            ];
        });

        // ==== LEVEL 3 ====
        $level3Collection = $semuaMateri->where('level', 3);
        $isLevel3Complete = $level3Collection->count() > 0 &&
            $level3Collection->every(fn($item) => $item['progress'] == 100);
        $materiLevel3 = $level3Collection->map(function ($item) {
            return [
                'title' => $item['judul'],
                'desc' => Str::limit(strip_tags($item['teks_teori'] ?? ''), 100) ?: 'Materi Lanjutan',
                'img' => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?auto=format&fit=crop&w=800&q=80',
                'progress' => $item['progress'],
                'slug' => $item['slug'],
            ];
        });

        return view('member.dashboard.materi', [
            'materiLevel1' => $materiLevel1,
            'materiLevel2' => $materiLevel2,
            'materiLevel3' => $materiLevel3,
            'isLevel1Finished' => $isLevel1Complete,
            'isLevel2Finished' => $isLevel2Complete,
            'isLevel3Finished' => $isLevel3Complete,
            'userLevel' => $userLevel,
        ]);
    })->name('dashboard.materi');

    Route::get('/laporan', function () {
        $userId = Auth::id();

        // ambil level dari registrasi_kursus
        $registrasi = DB::table('registrasi_kursus')
            ->where('id_member', $userId)
            ->orderByDesc('created_at')
            ->first();

        $userLevel = $registrasi->last_unlocked_level ?? 1;
        $levelLabel = match ($userLevel) {
            1       => 'Beginner',
            2       => 'Intermediate',
            3       => 'Advanced',
            default => 'Level ' . $userLevel,
        };

        // 🔹 PANGGIL API BACKEND
        $response = Http::get('http://127.0.0.1:8000/api/admin/hasil-tes', [
            'member' => $userId,   // ini akan nge-filter di index()
        ]);

        $hasil = collect();
        $summary = [
            'total'      => 0,
            'completed'  => 0,
            'avg_score'  => 0,
        ];

        if ($response->ok()) {
            $rows  = collect($response->json('data') ?? []);
            $hasil = $rows;

            $summary['total']     = $rows->count();
            $summary['completed'] = $rows->where('desc', 'Lulus')->count();
            $summary['avg_score'] = $rows->count() ? round($rows->avg('skor')) : 0;
        }

        return view('member.dashboard.laporan', [
            'userLevel'  => $userLevel,
            'levelLabel' => $levelLabel,
            'hasil'      => $hasil,
            'summary'    => $summary,
        ]);
    })->name('dashboard.laporan');
    Route::get('/sertifikasi', function () {
        $userId = Auth::id();

        $userLevel = DB::table('registrasi_kursus')
            ->where('id_member', $userId)
            ->value('last_unlocked_level') ?? 1;

        return view('member.dashboard.sertifikasi', [
            'userLevel' => $userLevel,
        ]);
    })->name('dashboard.sertifikasi');
    // 👇 ROUTE BARU: HALAMAN UJIAN SERTIFIKASI
    Route::get('/sertifikasi/ujian', function () {
        $userId = Auth::id();

        // Cek lagi level user biar nggak bisa langsung akses via URL
        $userLevel = DB::table('registrasi_kursus')
            ->where('id_member', $userId)
            ->value('last_unlocked_level') ?? 1;

        if ($userLevel < 4) {
            // Belum buka sertifikasi → balikin ke halaman info
            return redirect()
                ->route('dashboard.sertifikasi')
                ->with('error', 'Selesaikan dulu semua level sampai Level 3 sebelum ikut sertifikasi 😊');
        }

        // Nanti di sini kita bisa panggil API buat ambil soal sertifikasi
        // Untuk sekarang kirim view kosong dulu
        return view('member.dashboard.sertifikasi-ujian');
    })->name('dashboard.sertifikasi.ujian');
    Route::get('/sertifikasi/sertifikat', function () {
        $user = Auth::user();
        $userId = $user->id;

        // Ambil hasil sertifikasi TERBARU yang LULUS untuk user ini
        $hasil = DB::table('hasil_sertifikasi as h')
            ->join('kursus as k', 'h.id_course', '=', 'k.id_course')
            ->join('bahasa as b', 'k.id_bahasa', '=', 'b.id')
            ->join('paket as p', 'k.id_paket', '=', 'p.id')
            ->select(
                'h.id_hasil',
                'h.skor',
                'h.tanggal',
                'h.status',
                'k.id_course',
                'b.nama_bahasa',
                'p.nama_paket'
            )
            ->where('h.id_member', $userId)
            ->where('h.status', 'Lulus')        // cuma yang lulus
            ->orderByDesc('h.tanggal')
            ->first();

        if (!$hasil) {
            return redirect()
                ->route('dashboard.sertifikasi')
                ->with('error', 'Sertifikat belum tersedia. Ikuti dan lulus ujian sertifikasi terlebih dahulu.');
        }

        return view('member.dashboard.sertifikat', [
            'user' => $user,
            'hasil' => $hasil,
        ]);
    })->name('dashboard.sertifikasi.sertifikat');



    Route::get('/teori/{slug}', function ($slug) {
        // Panggil API backend
        $response = Http::get("http://127.0.0.1:8000/api/teori/{$slug}");

        if ($response->failed()) {
            abort(404, 'Teori tidak ditemukan di API.');
        }

        $data = $response->json('data');
        $materi = $data['materi'] ?? null;
        $teori = $data['teori'] ?? null;

        return view('member.dashboard.teori', [
            'slug' => $slug,
            'materi' => $materi,
            'teori' => $teori,
        ]);
    })->name('member.teori');


    Route::get('/kuis/{slug}', function ($slug) {
        return view('member.dashboard.kuis.show', ['slug' => $slug]);
    })->name('member.kuis.show');

    // VIDEO ROUTE
    Route::get('/video/{slug}', function ($slug) {
        $response = Http::get("http://127.0.0.1:8000/api/materi/{$slug}");
        $materi = $response->json('data');

        // debug:
        // dd($materi);

        return view('member.dashboard.video', [
            'slug' => $slug,
            'materi' => $materi,
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
            'name' => 'required|string|max:255',
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
Route::view('/admin/teori', 'Admin.Pages.teori')->name('admin.teori');

