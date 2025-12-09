@extends('member.dashboard.main')

@section('title', 'Sertifikasi Pembelajaran')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/materi.css') }}">

    <div class="container py-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">🏅 Ujian Sertifikasi</h2>
            <p class="text-muted mb-0">
                Ujian ini hanya terbuka kalau kamu sudah menyelesaikan semua materi sampai Level 3.
            </p>
        </div>

        @php $lvl = $userLevel ?? 1; @endphp

        @if ($lvl < 4)
            {{-- MASIH TERKUNCI --}}
            <div class="d-flex justify-content-center">
                <div class="bg-white rounded-4 p-5 shadow text-center" style="max-width: 480px;">
                    <i class="fas fa-lock fa-3x text-secondary mb-3"></i>
                    <h5 class="mb-2">Masih Terkunci</h5>
                    <p class="mb-0 text-muted">
                        Kamu baru mencapai Level {{ $lvl }}.
                        Selesaikan dulu semua materi & kuis sampai Level 3, lalu tekan tombol
                        "Tandai Level 3 Selesai & Buka Sertifikasi" di halaman Materi.
                    </p>
                </div>
            </div>
        @else
            {{-- SUDAH TERBUKA --}}
            <div class="d-flex justify-content-center">
                <div class="bg-white rounded-4 p-5 shadow text-center" style="max-width: 480px;">
                    <i class="fas fa-graduation-cap fa-3x text-success mb-3"></i>
                    <h5 class="mb-2">Selamat! Sertifikasi Terbuka 🎉</h5>
                    <p class="mb-4 text-muted">
                        Kamu sudah menyelesaikan semua level.
                        Klik tombol di bawah untuk memulai ujian sertifikasi.
                    </p>

                    {{-- TODO: ganti href ke route ujian sertifikasimu --}}
                    <a href="{{ route('dashboard.sertifikasi.ujian') }}" class="btn btn-primary px-4">
                        Mulai Ujian Sertifikasi
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
