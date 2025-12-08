@extends('member.dashboard.main')

@section('title', $materi['judul'] ?? 'Video Pembelajaran')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/video.css') }}">

    <div class="container-fluid bg-dark text-light py-5 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <div class="col-12 col-lg-10 position-relative">
            <div class="ratio ratio-16x9 rounded shadow-lg overflow-hidden">
                {{-- 👇 BAGIAN DINAMIS: URL Video diambil dari Database --}}
                <iframe id="videoFrame"
                    src="{{ $materi['url_video'] }}"
                    title="{{ $materi['judul'] }}"
                    allowfullscreen>
                </iframe>
            </div>

            <button id="nextBtn" class="btn btn-light btn-sm position-absolute end-0 bottom-0 m-3 px-4 py-2 shadow-lg">
                Lanjut ke Teori 📘
            </button>
        </div>

        <div class="col-12 col-lg-10 mt-4 px-3">
            {{-- 👇 BAGIAN DINAMIS: Judul & Deskripsi dari Database --}}
            <h2 class="fw-bold text-white">{{ $materi['judul'] }}</h2>
            <p class="text-secondary mb-0">
                {{-- Tampilkan teks teori (atau deskripsi singkat) --}}
                {!! $materi['teks_teori'] ?? 'Belum ada deskripsi untuk materi ini.' !!}
            </p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const nextBtn = document.getElementById("nextBtn");

            // Logic Tombol Lanjut
            nextBtn.addEventListener("click", () => {
                // 👇 BAGIAN DINAMIS: Link tombol lanjut pakai Route Laravel
                window.location.href = "{{ route('member.teori', ['slug' => $slug]) }}";
                console.log("Tombol Lanjut diklik");
            });

            // muat API YouTube (Opsional, dibiarkan saja biar aman)
            const tag = document.createElement("script");
            tag.src = "https://www.youtube.com/iframe_api";
            document.body.appendChild(tag);
        });
    </script>
@endsection
