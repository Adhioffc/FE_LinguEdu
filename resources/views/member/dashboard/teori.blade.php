@extends('member.dashboard.main')

@section('title', 'Teori Pembelajaran')

{{-- CSS khusus teori --}}
@section('style')
    @vite('resources/css/teori.css')
@endsection

@section('content')
    @php
        $slug = $slug ?? (request()->route('slug') ?? 'introduction-to-programming');

        // Judul ambil dari materi kalau ada
        $title = $materi['judul'] ?? ucwords(str_replace('-', ' ', $slug));

        // Field teori dari DB (kalau kosong, pakai text default kamu)
        $overview =
            $teori['overview'] ??
            'Introduction to Programming membahas fondasi pemrograman yang penting: cara menulis perintah kepada komputer, memahami alur logika program, dan membangun solusi dari masalah sederhana. Materi ini dirancang khusus untuk pemula yang belum memiliki pengalaman pemrograman sebelumnya.';

        $kenapaPenting =
            $teori['kenapa_penting'] ??
            "• Belajar berpikir logis dan sistematis.\n• Industri teknologi berkembang pesat.\n• Bisa mengotomatisasi tugas sehari-hari.";

        $konsepDasar =
            $teori['konsep_dasar'] ??
            "1. Variabel: wadah untuk menyimpan data.\n2. Tipe data: angka, teks, boolean.\n3. Kontrol alur: if/else, looping.";

        $contohPraktik = $teori['contoh_praktik'] ?? 'function isEven(n) { return n % 2 === 0; }';

        $ringkasan =
            $teori['ringkasan'] ?? 'Fokus pada logika dasar. Setelah paham, lanjut ke materi yang lebih kompleks.';

        $quizUrl = route('member.kuis.show', ['slug' => $slug]);
    @endphp

    <div class="container py-5">
        {{-- HEADER ISTIMEWA --}}
        <div class="teori-header mb-5">
            <div class="teori-header-content">
                <div>
                    <div class="teori-badge mb-3">📚 Pelajaran Baru</div>
                    <h1 class="teori-title mb-2">{{ $title }}</h1>
                    <p class="teori-subtitle mb-0">
                        Materi pembelajaran interaktif untuk memahami konsep dengan lebih mendalam dan praktik langsung
                    </p>
                </div>
                <div class="teori-header-buttons">
                    <a href="{{ url()->previous() }}" class="btn btn-pastel-secondary">
                        <span class="me-2">←</span> Kembali
                    </a>
                </div>
            </div>
            <div class="teori-header-decoration"></div>
        </div>

        <div class="row g-4">
            {{-- MAIN CONTENT (KIRI) --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 teori-card">
                    <div class="card-body">
                        {{-- Section 1 --}}
                        <section id="overview" class="mb-5">
                            <h5 class="teori-heading">✨ 1. Overview</h5>
                            <p class="teori-paragraph">
                                {!! nl2br(e($overview)) !!}
                            </p>
                        </section>
                        <hr>

                        {{-- Section 2 --}}
                        <section id="why" class="mb-5 pt-3">
                            <h5 class="teori-heading">🚀 2. Kenapa Penting?</h5>
                            <ul class="teori-list">
                                @foreach (preg_split('/\r\n|\r|\n/', $kenapaPenting) as $item)
                                    @if (trim($item) !== '')
                                        <li>{!! e(ltrim($item, '• ')) !!}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </section>
                        <hr>

                        {{-- Section 3 --}}
                        <section id="concepts" class="mb-5 pt-3">
                            <h5 class="teori-heading">💡 3. Konsep Dasar</h5>
                            <ol class="teori-list">
                                @foreach (preg_split('/\r\n|\r|\n/', $konsepDasar) as $item)
                                    @if (trim($item) !== '')
                                        <li>{!! e($item) !!}</li>
                                    @endif
                                @endforeach
                            </ol>
                        </section>
                        <hr>

                        {{-- Section 4 --}}
                        <section id="examples" class="mb-5 pt-3">
                            <h5 class="teori-heading">🔧 4. Contoh Praktik</h5>
                            <p class="teori-paragraph">Contoh kode:</p>
                            <div class="teori-code-block">
                                <pre class="teori-code"><code>{{ $contohPraktik }}</code></pre>
                            </div>
                        </section>
                        <hr>

                        {{-- Section 5 --}}
                        <section id="summary" class="pt-3">
                            <h5 class="teori-heading">✨ Ringkasan</h5>
                            <p class="teori-paragraph">
                                {!! nl2br(e($ringkasan)) !!}
                            </p>

                            {{-- CALL TO ACTION DI BAWAH --}}
                            <div class="mt-5 p-4 bg-light rounded-3 text-center border">
                                <h6 class="fw-bold mb-3">Sudah paham materinya?</h6>
                                <a href="{{ $quizUrl }}" class="btn btn-pastel-primary btn-lg px-5">
                                    Mulai Kuis Sekarang 🎯
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR (KANAN) - FLOATING --}}
            <div class="col-lg-4">
                <div class="sticky-sidebar">
                    {{-- CARD 1: DAFTAR ISI --}}
                    <div class="card mb-3 shadow-sm teori-toc-card border-0">
                        <div class="card-body p-4">
                            <h6 class="toc-title mb-3">📖 Daftar Isi</h6>
                            <nav class="nav flex-column toc-nav">
                                <a class="toc-link active" href="#overview">1. Overview</a>
                                <a class="toc-link" href="#why">2. Kenapa Penting?</a>
                                <a class="toc-link" href="#concepts">3. Konsep Dasar</a>
                                <a class="toc-link" href="#examples">4. Contoh Praktik</a>
                                <a class="toc-link" href="#summary">5. Ringkasan</a>
                            </nav>
                        </div>
                    </div>

                    {{-- CARD 2: PROGRESS BELAJAR --}}
                    <div class="card shadow-sm teori-progress-card border-0">
                        <div class="card-body p-4">
                            <h6 class="mb-3 fw-bold text-muted small text-uppercase" style="letter-spacing: 1px;">
                                Progress Belajar
                            </h6>

                            <div class="teori-progress-container mb-2">
                                <div class="teori-progress-text">
                                    <span class="teori-progress-value" id="read-percent">0%</span>
                                </div>
                                <svg class="teori-progress-circle" viewBox="0 0 36 36">
                                    <path class="teori-progress-bg"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                                    </path>
                                    <path class="teori-progress-fill"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                        style="stroke-dasharray: 0, 100;"></path>
                                </svg>
                            </div>

                            <div class="teori-progress-linear-track mb-3">
                                <div class="teori-progress-linear-fill" style="width: 0%"></div>
                            </div>

                            <p class="small text-muted mb-0">Scroll untuk membaca</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Scroll Spy & Progress (punyamu yang tadi, aku biarkan) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.toc-link');
            const circleFill = document.querySelector('.teori-progress-fill');
            const linearFill = document.querySelector('.teori-progress-linear-fill');
            const percentText = document.getElementById('read-percent');

            function onScroll() {
                let current = '';
                const scrollPos = window.scrollY + 150;

                sections.forEach(section => {
                    if (section.offsetTop <= scrollPos) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(current)) {
                        link.classList.add('active');
                    }
                });

                const scrollTop = window.scrollY;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrolled = (scrollTop / docHeight) * 100;
                const finalPercent = Math.min(100, Math.max(0, scrolled));

                if (circleFill) circleFill.style.strokeDasharray = `${finalPercent}, 100`;
                if (linearFill) linearFill.style.width = `${finalPercent}%`;
                if (percentText) percentText.innerText = Math.round(finalPercent) + '%';
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);
                    if (targetSection) {
                        window.scrollTo({
                            top: targetSection.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            window.addEventListener('scroll', onScroll);
        });
    </script>
@endsection
