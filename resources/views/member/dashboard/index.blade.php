@extends('member.dashboard.main')
@section('title', 'Dashboard - LinguEdu')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* --- 1. GLOBAL ATMOSPHERE (Aurora Background) --- */
    body {
        /* Warna dasar adem (Off-White/Soft Grey) */
        background-color: #f3f4f6;
        /* Efek Aurora Halus di background */
        background-image:
            radial-gradient(at 0% 0%, hsla(253,16%,90%,1) 0, transparent 50%),
            radial-gradient(at 50% 0%, hsla(225,39%,90%,1) 0, transparent 50%),
            radial-gradient(at 100% 0%, hsla(339,49%,90%,1) 0, transparent 50%);
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        overflow-x: hidden; /* Mencegah scroll samping */
    }

    /* --- 2. SIDEBAR FIX --- */
    .modern-sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(255, 255, 255, 0.85); /* Lebih transparan dikit */
        backdrop-filter: blur(20px); /* Efek kaca buram */
        border-right: 1px solid rgba(255, 255, 255, 0.5);
        display: flex;
        flex-direction: column;
        padding: 2rem;
        z-index: 50; /* Pastikan di atas konten utama */
        box-shadow: 4px 0 24px rgba(0,0,0,0.02);
    }

    /* --- 3. MAIN CONTENT (Grid 2x2) --- */
    .main-content {
        margin-left: 280px;
        padding: 2rem;
        min-height: 100vh;
        display: flex;
        align-items: center; /* Biar konten ada di tengah vertikal (opsional) */
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr; /* Kolom Kiri agak lebar dikit */
        grid-template-rows: auto auto;    /* 2 Baris otomatis */
        gap: 1.5rem;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* --- 4. CARD STYLES --- */
    .modern-card {
        background: rgba(255, 255, 255, 0.7); /* Glass effect tipis */
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%; /* Biar tinggi sama rata */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }

    .modern-card:hover {
        transform: translateY(-5px) scale(1.01);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        border-color: #fff;
    }

    /* WELCOME CARD (Spesial) */
    .card-welcome {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
    }

    /* Hiasan bulat-bulat di Welcome Card */
    .card-welcome::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .card-welcome::after {
        content: '';
        position: absolute;
        bottom: -30px; left: -30px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    /* Icons */
    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .modern-card:hover .icon-box {
        transform: rotate(10deg) scale(1.1);
    }

    /* Tombol Aksi di dalam Card */
    .btn-card-action {
        margin-top: 1.5rem;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* Variasi Warna Card */
    .theme-blue .icon-box { background: #eff6ff; color: #3b82f6; }
    .theme-blue .btn-card-action { background: #eff6ff; color: #3b82f6; }
    .theme-blue:hover .btn-card-action { background: #3b82f6; color: white; }

    .theme-green .icon-box { background: #f0fdf4; color: #22c55e; }
    .theme-green .btn-card-action { background: #f0fdf4; color: #22c55e; }
    .theme-green:hover .btn-card-action { background: #22c55e; color: white; }

    .theme-orange .icon-box { background: #fff7ed; color: #f97316; }
    .theme-orange .btn-card-action { background: #fff7ed; color: #f97316; }
    .theme-orange:hover .btn-card-action { background: #f97316; color: white; }

    /* Nav Link Styles */
    .nav-link-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #64748b;
        border-radius: 12px;
        transition: all 0.2s;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 4px;
    }
    .nav-link-modern:hover { background: #f1f5f9; color: #6366f1; transform: translateX(4px); }
    .nav-link-modern.active { background: #e0e7ff; color: #4f46e5; }

    /* Sticky Logout */
    .logout-box {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .modern-sidebar { display: none; } /* Nanti diurus mobile-nya */
        .main-content { margin-left: 0; padding: 1rem; }
        .dashboard-grid { grid-template-columns: 1fr; } /* Jadi 1 kolom di HP */
    }
</style>

<aside class="modern-sidebar">
    <div class="d-flex align-items-center gap-3 mb-5 px-2">
        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #a855f7); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4);">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <h4 class="m-0 fw-bold" style="color: #1e293b; font-size: 1.2rem;">LinguEdu</h4>
            <small style="color: #94a3b8; font-size: 0.75rem; letter-spacing: 0.5px;">LEARNING PLATFORM</small>
        </div>
    </div>

    <nav class="d-flex flex-column">
        <a href="{{ route('dashboard.index') }}" class="nav-link-modern active">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('dashboard.materi') }}" class="nav-link-modern">
            <i class="bi bi-book-half"></i> Materi Belajar
        </a>
        <a href="{{ route('dashboard.laporan') }}" class="nav-link-modern">
            <i class="bi bi-bar-chart-line-fill"></i> Laporan Progress
        </a>
        <a href="{{ route('dashboard.sertifikasi') }}" class="nav-link-modern">
            <i class="bi bi-patch-check-fill"></i> Ujian Sertifikasi
        </a>
        <a href="{{ route('dashboard.profile') }}" class="nav-link-modern">
            <i class="bi bi-person-circle"></i> Profil Saya
        </a>
    </nav>

    <div class="logout-box">
        <a href="{{ route('logout.simulasi') }}" class="nav-link-modern text-danger" style="color: #ef4444;">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>
</aside>

<div class="main-content">
    <div class="dashboard-grid">

        <div class="modern-card card-welcome">
            <div style="position: relative; z-index: 1;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="fw-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                        <p class="opacity-90 mb-4">Siap melanjutkan petualangan belajarmu hari ini?</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">
                        <i class="bi bi-star-fill text-warning me-1"></i> Member
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <p class="small mb-1 opacity-75">Status Level</p>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span class="fw-bold fs-5">Level 1: Beginner</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card theme-blue">
            <div>
                <div class="icon-box">
                    <i class="bi bi-journal-richtext"></i>
                </div>
                <h4 class="fw-bold text-dark">Materi Belajar</h4>
                <p class="text-secondary small">Akses modul interaktif & video pembelajaran.</p>
            </div>
            <a href="{{ route('dashboard.materi') }}" class="btn-card-action">
                Mulai Belajar <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="modern-card theme-green">
            <div>
                <div class="icon-box">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h4 class="fw-bold text-dark">Laporan Progress</h4>
                <p class="text-secondary small">Pantau statistik & perkembangan hasil kuis.</p>
            </div>
            <a href="{{ route('dashboard.laporan') }}" class="btn-card-action">
                Lihat Statistik <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="modern-card theme-orange">
            <div>
                <div class="icon-box">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <h4 class="fw-bold text-dark">Ujian Sertifikasi</h4>
                <p class="text-secondary small">Tantang dirimu dan raih sertifikat resmi.</p>
            </div>
            <a href="{{ route('dashboard.sertifikasi') }}" class="btn-card-action">
                Ambil Ujian <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>
</div>
@endsection
