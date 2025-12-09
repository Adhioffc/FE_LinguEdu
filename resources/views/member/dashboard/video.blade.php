@extends('member.dashboard.main')

@section('title', $materi['judul'] ?? 'Video Pembelajaran')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .video-page-wrapper {
            background: #111827;
            min-height: 100vh;
            padding: 3rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100vw;
            margin-left: calc(50% - 50vw);
        }
        .video-shell {
            width: 100%;
            max-width: 1100px;
        }
        .video-shell .ratio {
            background: #000;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }
        .video-shell .ratio > iframe,
        .video-shell .ratio > video {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }
        .video-next-btn {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
        }
        .video-meta {
            width: 100%;
            max-width: 1100px;
            margin-top: 1.5rem;
        }
    </style>

    @php
        use Illuminate\Support\Str;

        $judul      = $materi['judul'] ?? 'Video Pembelajaran';
        $rawUrl     = $materi['url_video'] ?? null;   // apa adanya dari DB
        $fileVideo  = $materi['video_url'] ?? null;   // dari accessor (file upload)
        $teks       = $materi['teks_teori'] ?? 'Belum ada deskripsi untuk materi ini.';

        // --- Tentukan mana yang dipakai ---
        $directVideoUrl = null;   // untuk <video>
        $embedUrl       = null;   // untuk <iframe> (YouTube)

        // 1️⃣ Prioritas: file upload
        if ($fileVideo) {
            $directVideoUrl = $fileVideo;
        }
        // 2️⃣ Kalau tidak ada file, cek apakah url_video itu direct file (.mp4, .webm, dll)
        elseif ($rawUrl && Str::endsWith($rawUrl, ['.mp4', '.webm', '.ogg', '.mov', '.avi'])) {
            $directVideoUrl = $rawUrl;
        }
        // 3️⃣ Kalau bukan file, anggap YouTube → ubah ke embed
        elseif ($rawUrl) {
            $matches = [];
            if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/))([^\s&?/]+)~', $rawUrl, $matches)) {
                $videoId = $matches[1];
                $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
            } else {
                // fallback terakhir: pakai apa adanya (bisa saja tetap ditolak, tapi jarang)
                $embedUrl = $rawUrl;
            }
        }
    @endphp

    <div class="video-page-wrapper">
        <div class="video-shell position-relative">
            <div class="ratio ratio-16x9">

                @if ($directVideoUrl)
                    {{-- 🎥 Pakai file mp4 / direct video --}}
                    <video controls>
                        <source src="{{ $directVideoUrl }}" type="video/mp4">
                        Browser kamu tidak mendukung video tag.
                    </video>

                @elseif ($embedUrl)
                    {{-- ▶️ Pakai YouTube embed --}}
                    <iframe src="{{ $embedUrl }}" title="{{ $judul }}" allowfullscreen></iframe>

                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                        Video belum tersedia.
                    </div>
                @endif

            </div>

            <button id="nextBtn" class="btn btn-light btn-sm video-next-btn px-4 py-2 shadow-lg">
                Lanjut ke Teori 📘
            </button>
        </div>

        <div class="video-meta text-light">
            <h2 class="fw-bold">{{ $judul }}</h2>
            <p class="text-secondary mb-0">{!! $teks !!}</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("nextBtn").addEventListener("click", () => {
                window.location.href = "{{ route('member.teori', ['slug' => $slug]) }}";
            });
        });
    </script>
@endsection
