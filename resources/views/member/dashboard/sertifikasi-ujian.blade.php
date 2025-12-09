@extends('member.dashboard.main')

@section('title', 'Ujian Sertifikasi')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-submit-main {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
        }

        .btn-submit-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.6);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20">

        {{-- HEADER --}}
        <div class="mb-8 text-center">
            <div
                class="inline-block bg-white text-indigo-600 shadow-sm px-4 py-2 rounded-full mb-4 text-sm font-bold border border-indigo-100">
                <i class="bi bi-patch-check-fill me-1"></i> Ujian Sertifikasi
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Ujian Sertifikasi</h2>
            <p class="text-gray-500 text-lg">
                Jawab semua pertanyaan dengan teliti. Nilai minimal kelulusan <strong>70</strong>.
            </p>
        </div>

        {{-- ALERT FLASH DARI REDIRECT --}}
        @if (session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form id="examForm">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KOLOM KIRI --}}
                <div class="w-full lg:w-2/3 order-2 lg:order-1">
                    {{-- INFO COURSE --}}
                    <div id="course-info-box" class="floating-card p-4 mb-4 hidden bg-white">
                        <h5 class="font-semibold text-gray-900 mb-1" id="course-title"></h5>
                        <p class="text-gray-500 text-sm mb-0" id="course-sub"></p>
                    </div>

                    <div class="space-y-8" id="daftar-soal-container">
                        <div class="text-center py-10">
                            <div class="spinner-border text-indigo-600" role="status"></div>
                            <p class="mt-2 text-gray-400">Memuat soal sertifikasi...</p>
                        </div>
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="pt-8 border-t border-gray-100 hidden" id="tombol-submit-container">
                        <button type="button" onclick="submitExam()"
                            class="w-full btn-submit-main font-bold py-4 rounded-xl flex items-center justify-center gap-2 text-lg">
                            <i class="bi bi-send-check-fill text-2xl"></i>
                            <span>Kirim Jawaban Ujian</span>
                        </button>
                    </div>
                </div>

                {{-- KOLOM KANAN: PROGRESS & STATUS --}}
                <div class="w-full lg:w-1/3 order-1 lg:order-2">
                    <div class="sticky top-8 space-y-4">
                        <div class="floating-card p-6 bg-white">
                            <div class="text-center mb-4">
                                <small class="uppercase tracking-widest text-gray-400 font-bold text-xs">Status</small>
                                <div class="mt-2 flex items-baseline justify-center">
                                    <span id="progress-text" class="progress-text-big">0%</span>
                                </div>
                            </div>
                            <div class="custom-progress-track mb-4 w-full bg-gray-100 rounded-full h-4">
                                <div class="custom-progress-fill" style="width: 0%"></div>
                            </div>
                            <div id="status-message" class="text-sm text-gray-500 text-center"></div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        const apiBase = 'http://127.0.0.1:8000/api';
        const currentMemberId = {{ (int) auth()->id() }};

        let totalSoal = 0;
        let currentTes = null;
        let currentSoal = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadSoalSertifikasi();
        });

        function renderOption(idSoal, abjad, text) {
            if (!text) return '';
            return `
                <input class="option-input" type="radio" name="q${idSoal}" id="opt-${idSoal}-${abjad}" value="${abjad}">
                <label class="option-label" for="opt-${idSoal}-${abjad}">
                    <strong>${abjad}.</strong> ${text}
                </label>
            `;
        }

        function attachInputListeners() {
            const inputs = document.querySelectorAll('input.option-input');
            const progressBar = document.querySelector('.custom-progress-fill');
            const progressText = document.getElementById('progress-text');

            function updateProgress() {
                let answered = new Set();
                inputs.forEach(input => {
                    if (input.checked) answered.add(input.name);
                });
                let percent = totalSoal === 0 ? 0 : (answered.size / totalSoal) * 100;
                progressBar.style.width = percent + '%';
                progressText.innerText = Math.round(percent) + '%';

                document.querySelectorAll('.quiz-card').forEach(card => {
                    if (card.querySelector('input:checked')) card.classList.add('active');
                    else card.classList.remove('active');
                });
            }

            inputs.forEach(input => input.addEventListener('change', updateProgress));
        }

        async function loadSoalSertifikasi() {
            const container = document.getElementById('daftar-soal-container');
            const submitBox = document.getElementById('tombol-submit-container');
            const courseBox = document.getElementById('course-info-box');
            const courseTitle = document.getElementById('course-title');
            const courseSub = document.getElementById('course-sub');
            const statusMessage = document.getElementById('status-message');

            container.innerHTML = `
                <div class="text-center py-10">
                    <div class="spinner-border text-indigo-600" role="status"></div>
                    <p class="mt-2 text-gray-400">Memuat soal sertifikasi...</p>
                </div>
            `;
            submitBox.classList.add('hidden');
            statusMessage.textContent = '';

            try {
                const res = await fetch(`${apiBase}/sertifikasi/soal?id_member=${currentMemberId}`);
                const json = await res.json();

                if (!res.ok) {
                    container.innerHTML = `
                        <div class="p-4 bg-red-50 text-red-600 rounded-lg">
                            <strong>Gagal memuat ujian sertifikasi.</strong><br>
                            ${json.message || 'Silakan hubungi admin.'}
                        </div>
                    `;
                    return;
                }

                const data = json.data;
                currentTes = data;
                currentSoal = data.soal || [];
                totalSoal = currentSoal.length;

                // Info course (paket + bahasa)
                if (data.course) {
                    courseBox.classList.remove('hidden');
                    const namaBahasa = data.course.bahasa?.nama_bahasa || 'Bahasa';
                    const namaPaket = data.course.paket?.nama_paket || 'Paket';
                    courseTitle.textContent = `${namaBahasa} - ${namaPaket}`;
                    courseSub.textContent = `KKM: ${data.kkm || 70} • Total Soal: ${totalSoal}`;
                }

                // ✅ Kalau sudah pernah ikut: tampilkan info + tombol download sertifikat
                if (data.already_taken && data.hasil) {
                    const h = data.hasil;
                    container.innerHTML = `
                        <div class="p-6 bg-blue-50 border border-blue-100 rounded-xl text-center">
                            <h3 class="text-xl font-bold text-blue-700 mb-2">Kamu sudah pernah ikut ujian ini.</h3>
                            <p class="mb-1">Skor terakhir: <strong>${h.skor}</strong> (${h.status})</p>
                            <p class="text-sm text-gray-500 mb-4">Tanggal: ${h.tanggal}</p>
                            <p class="text-gray-600 mb-4">Ujian sertifikasi hanya bisa diikuti <strong>sekali</strong>.</p>
                            <a href="{{ route('dashboard.sertifikasi.sertifikat') }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg border border-blue-500 text-blue-600 text-sm font-semibold bg-white hover:bg-blue-50">
                               <i class="bi bi-download me-1"></i> Lihat / Download Sertifikat
                            </a>
                        </div>
                    `;
                    statusMessage.textContent =
                        'Ujian sudah pernah diikuti. Kamu bisa men-download sertifikat dari tombol di atas atau dari halaman Sertifikasi.';
                    return;
                }

                // Belum pernah ikut, tapi soal kosong
                if (!totalSoal) {
                    container.innerHTML = `
                        <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg">
                            Belum ada soal untuk ujian sertifikasi ini. Silakan hubungi admin.
                        </div>
                    `;
                    return;
                }

                // Render soal
                container.innerHTML = '';
                currentSoal.forEach((soal, index) => {
                    const nomor = index + 1;
                    const htmlSoal = `
                        <div class="quiz-card" id="card-${soal.id_soal}">
                            <h5 class="question-text">${nomor}. ${soal.pertanyaan}</h5>
                            <div class="options-group">
                                ${renderOption(soal.id_soal, 'A', soal.opsi_a)}
                                ${renderOption(soal.id_soal, 'B', soal.opsi_b)}
                                ${renderOption(soal.id_soal, 'C', soal.opsi_c)}
                                ${renderOption(soal.id_soal, 'D', soal.opsi_d)}
                            </div>
                        </div>
                    `;
                    container.innerHTML += htmlSoal;
                });

                submitBox.classList.remove('hidden');
                attachInputListeners();
                statusMessage.textContent = 'Jawab semua soal lalu kirim jawaban. Ujian tidak bisa diulang.';

            } catch (err) {
                console.error(err);
                container.innerHTML = `
                    <div class="p-4 bg-red-50 text-red-600 rounded-lg">
                        Terjadi kesalahan koneksi saat memuat ujian sertifikasi.
                    </div>
                `;
            }
        }

        function submitExam() {
            const checked = document.querySelectorAll('input.option-input:checked');

            if (checked.length < totalSoal) {
                Swal.fire('Ups!', 'Jawab semua soal dulu ya!', 'warning');
                return;
            }

            if (!currentTes || !currentTes.kode_tes) {
                Swal.fire('Error', 'Data ujian tidak valid.', 'error');
                return;
            }

            const answers = [];
            checked.forEach(input => {
                const idSoal = input.name.replace('q', '');
                answers.push({
                    id_soal: parseInt(idSoal),
                    jawaban: input.value
                });
            });

            Swal.fire({
                title: 'Kirim Jawaban?',
                text: 'Ujian sertifikasi hanya bisa diikuti sekali.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb'
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Sedang Mengirim...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`${apiBase}/sertifikasi/${currentTes.kode_tes}/submit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            id_member: currentMemberId,
                            answers: answers
                        })
                    })
                    .then(res => res.json().then(j => ({
                        ok: res.ok,
                        body: j
                    })))
                    .then(({
                        ok,
                        body
                    }) => {
                        if (!ok) {
                            throw new Error(body.message || 'Gagal mengirim jawaban ujian.');
                        }

                        const data = body.data;
                        const skor = data.skor;
                        const status = data.status;
                        const kkm = data.kkm;

                        let icon = status === 'Lulus' ? 'success' : 'error';
                        let pesan = status === 'Lulus' ?
                            'Selamat! Kamu lulus ujian sertifikasi 🎉' :
                            'Sayang sekali kamu belum lulus. Ujian ini tidak bisa diulang.';

                        // ✅ kalau lulus, tombol SweetAlert jadi "Lihat Sertifikat"
                        const confirmText = status === 'Lulus' ?
                            'Lihat Sertifikat' :
                            'Kembali ke Sertifikasi';

                        Swal.fire({
                            title: `Nilai Kamu: ${skor}`,
                            html: `
                                <h3 class="text-2xl font-bold mb-2">${status}</h3>
                                <p>${pesan}</p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Benar ${data.benar} dari ${data.total} soal<br>
                                    KKM: ${kkm}
                                </p>
                            `,
                            icon: icon,
                            confirmButtonText: confirmText,
                            confirmButtonColor: '#2563eb'
                        }).then(() => {
                            if (status === 'Lulus') {
                                // 👉 arahkan ke halaman sertifikat
                                window.location.href =
                                "{{ route('dashboard.sertifikasi.sertifikat') }}";
                            } else {
                                window.location.href = "{{ route('dashboard.sertifikasi') }}";
                            }
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', err.message, 'error');
                    });
            });
        }
    </script>
@endsection
