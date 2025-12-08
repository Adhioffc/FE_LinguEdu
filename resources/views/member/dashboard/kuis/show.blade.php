@extends('member.dashboard.main')

@section('title', 'Kuis Pembelajaran')

@section('style')
    @vite('resources/css/kuis.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Tombol Submit Keren */
        .btn-submit-main {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
        }
        .btn-submit-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.6);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20">

        {{-- HEADER --}}
        <div class="mb-8">
            <div class="inline-block bg-white text-indigo-600 shadow-sm px-4 py-2 rounded-full mb-4 text-sm font-bold border border-indigo-100">
                <i class="bi bi-patch-check-fill me-1"></i> Mode Ujian
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2" id="judul-kuis">Sedang memuat soal...</h2>
            <p class="text-gray-500 text-lg">Jawab pertanyaan berikut untuk menguji pemahamanmu.</p>
        </div>

        <form id="quizForm">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KOLOM KIRI --}}
                <div class="w-full lg:w-2/3 order-2 lg:order-1">
                    <div class="space-y-8" id="daftar-soal-container">
                        <div class="text-center py-10">
                            <div class="spinner-border text-indigo-600" role="status"></div>
                            <p class="mt-2 text-gray-400">Menghubungi server...</p>
                        </div>
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="pt-8 border-t border-gray-100 hidden" id="tombol-submit-container">
                        <button type="button" onclick="submitQuiz()" class="w-full btn-submit-main font-bold py-4 rounded-xl flex items-center justify-center gap-2 text-lg">
                            <i class="bi bi-send-check-fill text-2xl"></i>
                            <span>Kirim Jawaban Final</span>
                        </button>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="w-full lg:w-1/3 order-1 lg:order-2">
                    <div class="sticky top-8 space-y-4">
                        <div class="floating-card p-6 bg-white">
                            <div class="text-center mb-4">
                                <small class="uppercase tracking-widest text-gray-400 font-bold text-xs">Status</small>
                                <div class="mt-2 flex items-baseline justify-center">
                                    <span id="progress-text" class="progress-text-big">0%</span>
                                </div>
                            </div>
                            <div class="custom-progress-track mb-6 w-full bg-gray-100 rounded-full h-4">
                                <div class="custom-progress-fill" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let totalSoal = 0;
        let idKuisSaatIni = null;

        const currentUserId = {{ auth()->id() ?? 'null' }};

        document.addEventListener('DOMContentLoaded', function() {
            const slug = "{{ $slug }}";
            const apiUrl = `http://127.0.0.1:8000/api/kuis/${slug}`;

            fetch(apiUrl)
                .then(res => {
                    if(!res.ok) throw new Error("Kuis belum tersedia");
                    return res.json();
                })
                .then(response => {
                    const data = response.data;
                    const listSoal = data.soal;

                    idKuisSaatIni = data.kuis_info.id_kuis;

                    document.getElementById('judul-kuis').innerText = "Kuis: " + data.materi.judul;
                    const container = document.getElementById('daftar-soal-container');
                    container.innerHTML = '';

                    totalSoal = listSoal.length;

                    if (totalSoal === 0) {
                        container.innerHTML = `<div class="p-4 bg-yellow-100">Soal kosong.</div>`;
                        return;
                    }

                    listSoal.forEach((soal, index) => {
                        const nomor = index + 1;
                        const htmlSoal = `
                            <div class="quiz-card" id="card-${soal.id_soal_kuis}">
                                <h5 class="question-text">${nomor}. ${soal.pertanyaan}</h5>
                                <div class="options-group">
                                    ${renderOption(soal.id_soal_kuis, 'A', soal.opsi_a)}
                                    ${renderOption(soal.id_soal_kuis, 'B', soal.opsi_b)}
                                    ${renderOption(soal.id_soal_kuis, 'C', soal.opsi_c)}
                                    ${renderOption(soal.id_soal_kuis, 'D', soal.opsi_d)}
                                </div>
                            </div>
                        `;
                        container.innerHTML += htmlSoal;
                    });

                    document.getElementById('tombol-submit-container').classList.remove('hidden');
                    attachInputListeners();
                })
                .catch(err => {
                    document.getElementById('daftar-soal-container').innerHTML = `<div class="text-red-500">${err.message}</div>`;
                });
        });

        function renderOption(idSoal, abjad, text) {
            if(!text) return '';
            return `
                <input class="option-input" type="radio" name="q${idSoal}" id="opt-${idSoal}-${abjad}" value="${abjad}">
                <label class="option-label" for="opt-${idSoal}-${abjad}">${text}</label>
            `;
        }

        function attachInputListeners() {
            const inputs = document.querySelectorAll('input.option-input');
            const progressBar = document.querySelector('.custom-progress-fill');
            const progressText = document.getElementById('progress-text');

            function updateProgress() {
                let answered = new Set();
                inputs.forEach(input => { if (input.checked) answered.add(input.name); });
                let percent = (answered.size / totalSoal) * 100;
                progressBar.style.width = percent + '%';
                progressText.innerText = Math.round(percent) + '%';

                document.querySelectorAll('.quiz-card').forEach(card => {
                    if(card.querySelector('input:checked')) card.classList.add('active');
                    else card.classList.remove('active');
                });
            }
            inputs.forEach(input => input.addEventListener('change', updateProgress));
        }

        // --- LOGIKA SUBMIT KE DATABASE ---
        function submitQuiz() {
            const inputs = document.querySelectorAll('input.option-input:checked');

            if(inputs.length < totalSoal) {
                Swal.fire('Ups!', 'Jawab semua soal dulu ya!', 'warning');
                return;
            }

            if (!currentUserId) {
                Swal.fire({
                    title: 'Akses Ditolak',
                    text: 'Sistem tidak mengenali Anda. Silakan login ulang.',
                    icon: 'warning',
                    confirmButtonText: 'Login Dulu',
                    confirmButtonColor: '#4f46e5'
                }).then(() => {
                    window.location.href = "{{ route('login') }}";
                });
                return;
            }

            const answers = [];
            inputs.forEach(input => {
                const idSoal = input.name.replace('q', '');
                answers.push({
                    id_soal_kuis: parseInt(idSoal),
                    jawaban: input.value
                });
            });

            const payload = {
                id_member: currentUserId,
                answers: answers
            };

            Swal.fire({
                title: 'Sedang Mengirim...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`http://127.0.0.1:8000/api/kuis/${idKuisSaatIni}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(response => {
                if(response.error) throw new Error(response.error);

                const data = response.data;
                const skor = data.skor;
                const status = data.status;

                let icon = skor >= 60 ? 'success' : 'error';
                let pesan = skor >= 60 ? 'Selamat! Kamu Lulus 🎉' : 'Jangan menyerah, coba lagi! 💪';

                Swal.fire({
                    title: `Nilai Kamu: ${skor}`,
                    html: `
                        <h3 class="text-2xl font-bold mb-2">${status}</h3>
                        <p>${pesan}</p>
                        <p class="text-sm text-gray-500 mt-2">Benar ${data.benar} dari ${data.total} soal</p>
                    `,
                    icon: icon,
                    confirmButtonText: 'Kembali ke Materi', // Teks tombol sudah benar
                    confirmButtonColor: '#4f46e5'
                }).then(() => {
                    // ✅ REVISI DISINI: Balik ke Dashboard Materi
                    window.location.href = "{{ route('dashboard.materi') }}";
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Gagal menyimpan nilai: ' + err.message, 'error');
            });
        }
    </script>
@endsection
