@extends('member.dashboard.main')

@section('title', 'Teori Pembelajaran')

{{-- CSS khusus teori --}}
@section('style')
    @vite('resources/css/teori.css')
@endsection

@section('content')
    @php
        // slug dikirim dari route /member/teori/{slug?}
        $slug = $slug ?? (request()->route('slug') ?? 'introduction-to-programming');
        $title = ucwords(str_replace('-', ' ', $slug));

        // link ke kuis dummy
        $quizUrl = route('member.kuis.show', ['slug' => $slug]);
    @endphp

    <div class="container py-5">
        {{-- HEADER ISTIMEWA --}}
        <div class="teori-header mb-5">
            <div class="teori-header-content">
                <div>
                    <div class="teori-badge mb-3">📚 Pelajaran Baru</div>
                    <h1 class="teori-title mb-2">{{ $title }}</h1>
                    <p class="teori-subtitle mb-0">Materi pembelajaran interaktif untuk memahami konsep dengan lebih mendalam dan praktik langsung</p>
                </div>
                <div class="teori-header-buttons">
                    <a href="{{ url()->previous() }}" class="btn btn-pastel-secondary">
                        <span class="me-2">←</span> Kembali
                    </a>
                    <a href="{{ $quizUrl }}" class="btn btn-pastel-primary">
                        <span class="me-2">🎯</span> Mulai Kuis
                    </a>
                </div>
            </div>
            <div class="teori-header-decoration"></div>
        </div>

        <div class="row g-4">
            {{-- MAIN CONTENT --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 teori-card">
                    <div class="card-body">
                        <h5 id="overview" class="card-title">✨ 1. Overview</h5>
                        <p class="teori-paragraph">
                            Introduction to Programming membahas fondasi pemrograman yang penting: cara menulis perintah kepada komputer, memahami alur logika program, dan membangun solusi dari masalah sederhana. Materi ini dirancang khusus untuk pemula yang belum memiliki pengalaman pemrograman sebelumnya, sehingga mudah dipahami dan dinikmati.
                        </p>
                        <hr>

                        <h5 id="why" class="mt-3">🚀 2. Kenapa Penting Belajar Pemrograman?</h5>
                        <ul class="teori-list">
                            <li><strong>Tingkatkan Kemampuan Problem Solving:</strong> Belajar berpikir logis dan sistematis dalam menyelesaikan masalah.</li>
                            <li><strong>Buka Peluang Karir Menjanjikan:</strong> Industri teknologi berkembang pesat dengan permintaan tinggi untuk programmer.</li>
                            <li><strong>Otomatisasi Tugas Sehari-hari:</strong> Hemat waktu dengan membuat program sederhana untuk pekerjaan rutin.</li>
                        </ul>
                        <hr>

                        <h5 id="concepts" class="mt-3">💡 3. Konsep Dasar yang Harus Dipahami</h5>
                        <ol class="teori-list">
                            <li><strong>Variabel:</strong> Wadah penyimpanan untuk menyimpan data yang akan digunakan program Anda.</li>
                            <li><strong>Tipe Data:</strong> Kategori data seperti angka (integer), teks (string), dan nilai benar/salah (boolean).</li>
                            <li><strong>Kontrol Alur:</strong> Perintah untuk menentukan jalur eksekusi program menggunakan if/else dan perulangan (for, while).</li>
                            <li><strong>Fungsi:</strong> Blok kode yang dapat dipanggil berkali-kali, membuat kode lebih terorganisir dan mudah dirawat.</li>
                            <li><strong>Struktur Data:</strong> Cara menyimpan dan mengorganisir data seperti array (daftar) dan objek (struktur kompleks).</li>
                        </ol>
                        <hr>

                        <h5 id="examples" class="mt-3">🔧 4. Contoh Praktik Langsung</h5>
                        <p class="teori-paragraph"><strong>Contoh 1:</strong> Program untuk cek apakah angka itu ganjil atau genap menggunakan JavaScript:</p>

                        <div class="teori-code-block"><pre class="teori-code"><code>// JavaScript
function isEven(n) {
  return n % 2 === 0;
}
console.log(isEven(4)); // true
console.log(isEven(3)); // false
</code></pre></div>

                        <p class="teori-paragraph mt-3"><strong>Contoh 2:</strong> Fungsi sederhana untuk menyapa pengguna di PHP:</p>
                        <div class="teori-code-block"><pre class="teori-code"><code>&lt;?php
function greet($name) {
    return "Halo, " . $name . "! Selamat belajar.";
}
echo greet("Teman");
?&gt;
</code></pre></div>

                        <hr>

                        <h5 id="tips" class="mt-3">📌 5. Tips Sukses Belajar Pemrograman</h5>
                        <ul class="teori-list">
                            <li><strong>Praktik Setiap Hari:</strong> Luangkan waktu rutin untuk menulis kode, bahkan hanya 15-30 menit per hari akan sangat membantu.</li>
                            <li><strong>Bangun Proyek Mini:</strong> Jangan hanya membaca teori, langsung terapkan dengan membuat project sederhana.</li>
                            <li><strong>Embrace the Errors:</strong> Kesalahan adalah guru terbaik—jangan takut bereksperimen dan belajar dari debugging.</li>
                        </ul>

                        <hr>

                        <h5 id="summary" class="mt-3">✨ Ringkasan Materi</h5>
                        <p class="teori-paragraph">
                            Fokus pertama pada penguasaan <strong>logika dasar, variabel, dan kontrol alur</strong> karena ini adalah fondasi semua pemrograman. Setelah paham, tingkatkan ke <strong>fungsi, struktur data, dan paradigma pemrograman</strong> yang lebih kompleks. Ingat: setiap programmer dimulai dari sini, jadi ambil waktu Anda dan jangan terburu-buru!
                        </p>

                        <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
                            <div class="text-muted small">Materi: {{ $slug }}</div>
                            <div>
                                <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                    Simpan Catatan ✍️
                                </a>
                                <a href="{{ $quizUrl }}" class="btn btn-success btn-sm">
                                    Mulai Kuis 🎯
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REFERENSI --}}
                <div class="card shadow-sm teori-card">
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

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    <div class="card mb-3 shadow-sm teori-toc-card">
                        <div class="card-body">
                            <h6 class="mb-3">📖 Daftar Isi</h6>
                            <nav class="nav flex-column small toc">
                                <a class="nav-link p-0 mb-1" href="#overview">Overview</a>
                                <a class="nav-link p-0 mb-1" href="#why">Kenapa Belajar</a>
                                <a class="nav-link p-0 mb-1" href="#concepts">Konsep Dasar</a>
                                <a class="nav-link p-0 mb-1" href="#examples">Contoh</a>
                                <a class="nav-link p-0" href="#summary">Ringkasan</a>
                            </nav>
                        </div>
                    </div>

                    <div class="card shadow-sm teori-progress-card">
                        <div class="card-body">
                            <h6 class="mb-3">⚡ Progress Belajar</h6>
                            <div class="teori-progress-container">
                                <div class="teori-progress-text">
                                    <span class="teori-progress-value">25%</span>
                                    <span class="teori-progress-label">Selesai</span>
                                </div>
                                <svg class="teori-progress-circle" viewBox="0 0 36 36">
                                    <path class="teori-progress-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="teori-progress-fill" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" style="stroke-dasharray: 25, 100;"></path>
                                </svg>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-pastel-primary btn-sm w-100">Lanjutkan Pembelajaran</a>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm teori-card">
                        <div class="card-body">
                            <h6 class="mb-2">🎯 Tantangan Cepat</h6>
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

    {{-- Floating button ke kuis --}}
    <a href="{{ $quizUrl }}" class="btn btn-success position-fixed d-none d-md-inline-flex align-items-center"
        style="right:20px; bottom:20px; z-index:1050; padding:.6rem 1rem; border-radius:999px;">
        🎯 Mulai Kuis
    </a>

    <script>
        // Interactive helpers for teori page
        (function(){
            // collapse/expand sections
            function setupCollapsibles(){
                const headings = document.querySelectorAll('.card-body h5');
                headings.forEach((h)=>{
                    h.classList.add('teori-heading');
                    const toggle = document.createElement('button');
                    toggle.className = 'collapse-toggle';
                    toggle.type = 'button';
                    toggle.innerText = '▾';
                    h.appendChild(toggle);

                    // collect nodes until next H5 or end
                    let nodes = [];
                    let el = h.nextElementSibling;
                    while(el && el.tagName !== 'H5'){
                        nodes.push(el);
                        el = el.nextElementSibling;
                    }

                    toggle.addEventListener('click', ()=>{
                        nodes.forEach(n=> n.classList.toggle('collapsed'));
                        toggle.innerText = toggle.innerText === '▾' ? '▸' : '▾';
                    });
                });
            }

            // TOC smooth scroll + highlight
            function setupTOC(){
                const tocLinks = document.querySelectorAll('.nav-link[href^="#"]');
                const sections = Array.from(tocLinks).map(a=> document.querySelector(a.getAttribute('href')) ).filter(Boolean);

                // click smooth
                tocLinks.forEach(a=>{
                    a.addEventListener('click', function(e){
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if(!target) return;
                        target.scrollIntoView({behavior:'smooth', block:'start'});
                    });
                });

                // highlight on scroll
                function onScroll(){
                    const scrollPos = window.scrollY + 120;
                    let activeIndex = -1;
                    sections.forEach((sec, i)=>{
                        if(sec.offsetTop <= scrollPos) activeIndex = i;
                    });
                    tocLinks.forEach((a,i)=> a.classList.toggle('active', i===activeIndex));
                }
                window.addEventListener('scroll', onScroll, {passive:true});
                onScroll();
            }

            // animate progress bar from 0
            function animateProgress(){
                const pb = document.querySelector('.progress-animate .progress-bar');
                if(!pb) return;
                const target = pb.getAttribute('data-target') || pb.style.width || '25%';
                pb.style.width = '0%';
                setTimeout(()=> pb.style.width = target, 120);
            }

            document.addEventListener('DOMContentLoaded', ()=>{
                setupCollapsibles();
                setupTOC();
                animateProgress();
                // small enhancement: make Save button give feedback
                document.querySelectorAll('.btn-outline-primary').forEach(btn=>{
                    btn.addEventListener('click', (e)=>{
                        e.preventDefault();
                        const original = btn.innerHTML;
                        btn.innerHTML = 'Tersimpan ✓';
                        btn.classList.add('btn-animate');
                        setTimeout(()=>{ btn.innerHTML = original; btn.classList.remove('btn-animate'); }, 1400);
                    });
                });
            });
        })();
    </script>
@endsection
