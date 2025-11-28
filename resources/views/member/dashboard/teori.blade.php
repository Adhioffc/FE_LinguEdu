@extends('member.dashboard.main')

@section('title', 'Teori Pembelajaran')

{{-- Tambahan CSS khusus halaman Teori --}}
@section('style')
    <link rel="stylesheet" href="{{ asset('css/teori.css') }}">
@endsection

@section('content')
    @php
        $slug = request()->segment(3) ?? 'introduction-to-programming';
        $title = ucwords(str_replace('-', ' ', $slug));
        $quizUrl = url('/member/kuis/' . $slug);
    @endphp

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">📘 Teori: {{ $title }}</h2>
                <small class="text-muted">Durasi baca ~10 menit</small>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">← Kembali</a>
                <a href="{{ $quizUrl }}" class="btn btn-success">Mulai Kuis 🎯</a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main content -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 id="overview" class="card-title">1. Overview</h5>
                        <p>
                            Introduction to Programming membahas konsep dasar pemrograman: bagaimana menulis instruksi
                            untuk komputer, memahami alur logika, dan membangun solusi sederhana.
                            Materi ini cocok untuk pemula tanpa pengalaman sebelumnya.
                        </p>
                        <hr>

                        <h5 id="why" class="mt-3">2. Kenapa Belajar Pemrograman?</h5>
                        <ul>
                            <li>Meningkatkan kemampuan problem solving.</li>
                            <li>Membuka peluang karier di bidang teknologi.</li>
                            <li>Mampu mengotomatisasi tugas sehari-hari.</li>
                        </ul>
                        <hr>

                        <h5 id="concepts" class="mt-3">3. Konsep Dasar</h5>
                        <ol>
                            <li><strong>Variabel:</strong> tempat menyimpan data.</li>
                            <li><strong>Tipe data:</strong> angka, teks, boolean, dll.</li>
                            <li><strong>Kontrol alur:</strong> if/else, loop (for, while).</li>
                            <li><strong>Fungsi:</strong> blok kode yang dapat dipanggil ulang.</li>
                            <li><strong>Struktur data dasar:</strong> array, objek/associative array.</li>
                        </ol>
                        <hr>

                        <h5 id="examples" class="mt-3">4. Contoh Sederhana</h5>
                        <p>Contoh logika: hitung apakah sebuah angka ganjil atau genap.</p>

                        <pre class="bg-light p-3 rounded"><code>// JavaScript
function isEven(n) {
  return n % 2 === 0;
}
console.log(isEven(3)); // false
</code></pre>

                        <p class="mt-3">Contoh fungsi sederhana di PHP:</p>
                        <pre class="bg-light p-3 rounded"><code>&lt;?php
function greet($name) {
    return "Halo, " . $name . "!";
}
echo greet("Siswa");
?&gt;
</code></pre>

                        <hr>

                        <h5 id="tips" class="mt-3">5. Tips Belajar</h5>
                        <ul>
                            <li>Praktikkan langsung setiap konsep kecil.</li>
                            <li>Buat proyek mini untuk menerapkan ilmu.</li>
                            <li>Debugging adalah bagian penting — jangan takut salah.</li>
                        </ul>

                        <hr>

                        <h5 id="summary" class="mt-3">Ringkasan</h5>
                        <p>
                            Kuasai logika, variabel, kontrol alur, dan fungsi terlebih dahulu. Lanjutkan ke struktur data
                            dan paradigma pemrograman.
                        </p>

                        <div class="d-flex justify-content-between mt-4">
                            <div class="text-muted">Materi: {{ $slug }}</div>
                            <div>
                                <a href="#" class="btn btn-outline-primary btn-sm me-2">Simpan Catatan ✍️</a>
                                <a href="{{ $quizUrl }}" class="btn btn-success btn-sm">Mulai Kuis 🎯</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- References / further reading -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Referensi & Bacaan Lanjutan</h6>
                        <ul class="mb-0">
                            <li><a href="#" target="_blank">Belajar Pemrograman untuk Pemula</a></li>
                            <li><a href="#" target="_blank">Tutorial JavaScript Dasar</a></li>
                            <li><a href="#" target="_blank">PHP: Panduan Pemula</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-2">Daftar Isi</h6>
                            <nav class="nav flex-column small">
                                <a class="nav-link p-0 mb-1" href="#overview">Overview</a>
                                <a class="nav-link p-0 mb-1" href="#why">Kenapa Belajar</a>
                                <a class="nav-link p-0 mb-1" href="#concepts">Konsep Dasar</a>
                                <a class="nav-link p-0 mb-1" href="#examples">Contoh</a>
                                <a class="nav-link p-0" href="#summary">Ringkasan</a>
                            </nav>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <h6 class="mb-2">Progress</h6>
                            <div class="mb-2">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%;"></div>
                                </div>
                            </div>
                            <small class="text-muted">25% selesai</small>
                            <div class="mt-3">
                                <a href="#" class="btn btn-primary btn-sm">Tandai Selesai</a>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-2">Catatan Cepat</h6>
                            <p class="small text-muted mb-1">Butuh latihan cepat? Coba tantangan 5 menit:</p>
                            <ol class="small mb-0">
                                <li>Buat fungsi penjumlahan</li>
                                <li>Cetak hasil ke layar</li>
                                <li>Uji dengan beberapa input</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating button -->
    <a href="{{ $quizUrl }}" class="btn btn-success position-fixed d-none d-md-inline-flex align-items-center"
        style="right:20px; bottom:20px; z-index:1050; padding: .6rem 1rem; border-radius: 999px;">
        🎯 Mulai Kuis
    </a>
@endsection
