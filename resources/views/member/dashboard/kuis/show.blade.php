@extends('member.dashboard.main')

@section('title', 'Kuis Pembelajaran')

@section('style')
    @vite('resources/css/kuis.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Progress Text Besar */
        .progress-text-big {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #9966cc, #6699cc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Bar Progress */
        .custom-progress-track {
            background-color: #f3f4f6;
            border-radius: 9999px;
            overflow: hidden;
            height: 16px;
        }
        .custom-progress-fill {
            background: linear-gradient(90deg, #d8bfd8, #9966cc);
            height: 100%;
            border-radius: 9999px;
            transition: width 0.5s ease-out;
        }

        /* Floating Card */
        .floating-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #f3f4f6;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('content')
    @php
        $slug = $slug ?? (request()->route('slug') ?? 'introduction-to-programming');
        $title = 'Kuis: ' . ucwords(str_replace('-', ' ', $slug));
    @endphp

    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20">

        {{-- HEADER SECTION (Tanpa Kembali ke Teori) --}}
        <div class="mb-8">
            <div class="inline-block bg-white text-indigo-600 shadow-sm px-4 py-2 rounded-full mb-4 text-sm font-bold border border-indigo-100">
                <i class="bi bi-patch-check-fill me-1"></i> Mode Ujian
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $title }}</h2>
            <p class="text-gray-500 text-lg">
                Jawab pertanyaan berikut untuk menguji pemahamanmu.
            </p>
        </div>

        <form id="quizForm">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KOLOM KIRI: DAFTAR SOAL --}}
                <div class="w-full lg:w-2/3 order-2 lg:order-1">
                    <div class="space-y-8">

                        {{-- Soal 1 --}}
                        <div class="quiz-card" id="card-1">
                            <h5 class="question-text">1. Apa itu variabel dalam pemrograman?</h5>
                            <div class="options-group">
                                <input class="option-input" type="radio" name="q1" id="q1a" value="a">
                                <label class="option-label" for="q1a">Tempat menyimpan data di memori.</label>

                                <input class="option-input" type="radio" name="q1" id="q1b" value="b">
                                <label class="option-label" for="q1b">Sebuah perulangan (looping).</label>

                                <input class="option-input" type="radio" name="q1" id="q1c" value="c">
                                <label class="option-label" for="q1c">Kesalahan program (bug).</label>
                            </div>
                        </div>

                        {{-- Soal 2 --}}
                        <div class="quiz-card" id="card-2">
                            <h5 class="question-text">2. Operator apa yang digunakan untuk membandingkan kesamaan?</h5>
                            <div class="options-group">
                                <input class="option-input" type="radio" name="q2" id="q2a" value="a">
                                <label class="option-label" for="q2a">= (Sama dengan tunggal)</label>

                                <input class="option-input" type="radio" name="q2" id="q2b" value="b">
                                <label class="option-label" for="q2b">== (Sama dengan ganda)</label>

                                <input class="option-input" type="radio" name="q2" id="q2c" value="c">
                                <label class="option-label" for="q2c">+ (Tambah)</label>
                            </div>
                        </div>

                        {{-- Soal 3 --}}
                        <div class="quiz-card checkbox-group" id="card-3">
                            <h5 class="question-text">3. Manakah yang termasuk struktur kontrol alur? (Pilih > 1)</h5>
                            <div class="options-group">
                                <input class="option-input" type="checkbox" name="q3[]" id="q3a" value="if">
                                <label class="option-label" for="q3a">if / else</label>

                                <input class="option-input" type="checkbox" name="q3[]" id="q3b" value="for">
                                <label class="option-label" for="q3b">for / while</label>

                                <input class="option-input" type="checkbox" name="q3[]" id="q3c" value="echo">
                                <label class="option-label" for="q3c">echo / print</label>
                            </div>
                        </div>

                        {{-- TOMBOL SUBMIT UTAMA (Di Bawah Soal) - FIX WARNA INDIGO --}}
                        <div class="pt-8 border-t border-gray-100">
                            {{-- Menggunakan style bg-indigo-50 dan text-indigo-600 (Sama persis dengan sidebar tapi lebih besar) --}}
                            <button type="button" onclick="submitQuiz()" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold py-4 rounded-xl shadow-sm border border-indigo-200 transform transition hover:scale-[1.01] flex items-center justify-center gap-2 text-lg">
                                <i class="bi bi-send-check-fill text-2xl"></i>
                                <span>Kirim Jawaban</span>
                            </button>
                            <p class="text-center text-gray-400 text-sm mt-3">Pastikan semua soal telah terjawab</p>
                        </div>

                    </div>
                </div>

                {{-- KOLOM KANAN: PROGRESS BAR (Sticky Sidebar) --}}
                <div class="w-full lg:w-1/3 order-1 lg:order-2">
                    <div class="sticky top-8 space-y-4">

                        {{-- Kartu Progress --}}
                        <div class="floating-card p-6 bg-white">
                            <div class="text-center mb-4">
                                <small class="uppercase tracking-widest text-gray-400 font-bold text-xs">Status Pengerjaan</small>
                                <div class="mt-2 flex items-baseline justify-center">
                                    <span id="progress-text" class="progress-text-big">0%</span>
                                </div>
                                <p class="text-gray-400 text-sm mt-1">Selesaikan semua soal</p>
                            </div>

                            <div class="custom-progress-track mb-6 w-full bg-gray-100 rounded-full h-4">
                                <div class="custom-progress-fill" style="width: 0%"></div>
                            </div>

                            <div class="flex items-center justify-center text-gray-400 text-sm gap-2">
                                <i class="bi bi-clock"></i> <span>Waktu tidak dibatasi</span>
                            </div>

                            {{-- TOMBOL SUBMIT PINTASAN (Sidebar) --}}
                            <div class="hidden lg:block mt-6 pt-4 border-t border-gray-50">
                                <button type="button" onclick="submitQuiz()" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold py-2 rounded-lg transition text-sm">
                                    Kirim Jawaban
                                </button>
                            </div>
                        </div>

                        {{-- Tombol Kembali ke Atas --}}
                        <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full bg-white hover:bg-gray-50 text-gray-500 font-bold py-3 rounded-xl border border-gray-200 transition flex items-center justify-center gap-2 shadow-sm">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                            <span>Kembali ke Atas</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('quizForm');
            const inputs = form.querySelectorAll('input');
            const progressBar = document.querySelector('.custom-progress-fill');
            const progressText = document.getElementById('progress-text');
            const totalQuestions = 3;

            function updateProgress() {
                let answered = new Set();
                inputs.forEach(input => {
                    if (input.checked) {
                        const questionName = input.name.replace('[]', '');
                        answered.add(questionName);
                    }
                });

                let percent = (answered.size / totalQuestions) * 100;

                progressBar.style.width = percent + '%';
                progressText.innerText = Math.round(percent) + '%';

                document.querySelectorAll('.quiz-card').forEach(card => {
                    if(card.querySelector('input:checked')) {
                        card.classList.add('active');
                    } else {
                        card.classList.remove('active');
                    }
                });
            }

            inputs.forEach(input => {
                input.addEventListener('change', updateProgress);
            });
        });

        function submitQuiz() {
            Swal.fire({
                title: 'Kirim Jawaban?',
                text: "Yakin sudah selesai?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#9966cc',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Cek Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terkirim!', 'Jawabanmu tersimpan.', 'success').then(() => {
                         window.location.href = "{{ route('dashboard.materi') }}";
                    });
                }
            })
        }
    </script>
@endsection
