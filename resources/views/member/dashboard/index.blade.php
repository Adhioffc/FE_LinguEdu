@extends('member.dashboard.main')
@section('title', 'Dashboard - LinguEdu')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #eef2ff, #ffffff, #dbeafe);
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .sidebar a {
        transition: all 0.25s ease;
        border-radius: 12px;
        font-weight: 500;
    }

    .sidebar a.active,
    .sidebar a:hover {
        background: linear-gradient(to right, #6366f1, #3b82f6);
        color: white !important;
        transform: translateX(3px);
    }

    /* Cards */
    .feature-card {
        background: white;
        border-radius: 18px;
        padding: 30px 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(99, 102, 241, 0.2);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .icon-blue {
        background: linear-gradient(135deg, #6366f1, #3b82f6);
    }

    .icon-green {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .icon-yellow {
        background: linear-gradient(135deg, #facc15, #eab308);
    }

    .btn-primary-custom {
        background: linear-gradient(to right, #6366f1, #3b82f6);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        opacity: 0.9;
        transform: scale(1.04);
    }
</style>

<div class="d-flex flex-column flex-md-row min-vh-100">

    <!-- Sidebar -->
    <aside class="sidebar text-dark p-4 d-flex flex-column justify-content-between" style="width: 260px;">
        <div>
            <div class="d-flex align-items-center mb-4">
                <div class="bg-gradient-to-r from-indigo-500 to-blue-400 p-2 rounded-3 me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white" width="24" height="24" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1.5m0 15V21m9-9h-1.5m-15 0H3m15.364-6.364l-1.06 1.06m-9.607 9.607l-1.06 1.06m0-12.727l1.06 1.06m9.607 9.607l1.06 1.06M8.25 12a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0z" />
                    </svg>
                </div>
                <h4 class="fw-bold text-primary mb-0">LinguEdu</h4>
            </div>

            <nav class="nav flex-column gap-2">
                <a href="{{ route('dashboard.index') }}" class="nav-link px-3 py-2 text-dark active">🏠 Dashboard</a>
                <a href="{{ route('dashboard.materi') }}" class="nav-link px-3 py-2 text-dark">📘 Materi</a>
                <a href="{{ route('dashboard.laporan') }}" class="nav-link px-3 py-2 text-dark">📊 Progress</a>
                <a href="{{ route('dashboard.sertifikasi') }}" class="nav-link px-3 py-2 text-dark">🎓 Sertifikasi</a>
            </nav>
        </div>

        <div class="border-top pt-3">
            <a href="{{ route('login.simulasi') }}" class="text-danger fw-semibold text-decoration-none">
                🚪 Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 p-5">
        <div class="mb-5">
            <h2 class="fw-bold text-dark mb-1">Selamat Datang di LinguEdu! 👋</h2>
            <p class="text-secondary">Kamu sedang berada di <span class="fw-semibold text-indigo-600">Level 1 - Beginner</span></p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon icon-blue">📘</div>
                    <h5 class="fw-bold text-dark">Materi Pembelajaran</h5>
                    <p class="text-muted small mt-2">Akses video dan teori interaktif sesuai levelmu.</p>
                    <a href="{{ route('dashboard.materi') }}" class="btn btn-primary-custom mt-3">Lihat Materi</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon icon-green">📊</div>
                    <h5 class="fw-bold text-dark">Laporan Progres</h5>
                    <p class="text-muted small mt-2">Pantau perkembangan hasil belajar dan kuis kamu.</p>
                    <a href="{{ route('dashboard.laporan') }}" class="btn btn-success mt-3">Lihat Laporan</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon icon-yellow">🎓</div>
                    <h5 class="fw-bold text-dark">Ujian Sertifikasi</h5>
                    <p class="text-muted small mt-2">Selesaikan semua level dan raih sertifikat kompetensi.</p>
                    <a href="{{ route('dashboard.sertifikasi') }}" class="btn btn-warning mt-3 text-white">Mulai Ujian</a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
