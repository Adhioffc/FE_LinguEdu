@extends('member.dashboard.main')

@section('title', 'Video Pembelajaran')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/video.css') }}">

    <div
        class="container-fluid bg-dark text-light py-5 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <div class="col-12 col-lg-10 position-relative">
            <div class="ratio ratio-16x9 rounded shadow-lg overflow-hidden">
                <iframe id="videoFrame" src="https://www.youtube.com/embed/Kvb4gfoMprM?rel=0&modestbranding=1&controls=1"
                    title="Video Pembelajaran" allowfullscreen></iframe>
            </div>

            <!-- Tombol Lanjut -->
            <button id="nextBtn" class="btn btn-light btn-sm position-absolute end-0 bottom-0 m-3 px-4 py-2 shadow-lg">
                Lanjut ke Teori 📘
            </button>
        </div>

        <div class="col-12 col-lg-10 mt-4 px-3">
            <h2 class="fw-bold text-white">Introduction to Programming</h2>
            <p class="text-secondary mb-0">
                Pelajari dasar-dasar logika dan konsep pemrograman yang menjadi fondasi semua bahasa pemrograman.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            nextBtn = document.getElementById("nextBtn");

            // pastikan bisa diklik setelah muncul
            nextBtn.addEventListener("click", () => {
                window.location = "/member/teori";
                console.log("Tombol Lanjut diklik");
            });

            // muat API YouTube
            const tag = document.createElement("script");
            tag.src = "https://www.youtube.com/iframe_api";
            document.body.appendChild(tag);
        });
    </script>
@endsection
