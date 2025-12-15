@extends('member.dashboard.main')

@section('title', 'Ujian Sertifikasi')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* --- 1. LATAR BELAKANG MODERN (Dark Premium Theme) --- */
        body {
            /* Gradasi latar belakang dari Abu-abu gelap ke Biru malam */
            background: radial-gradient(circle at top left, #334155 0%, #0f172a 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* --- 2. KOMPONEN UI --- */

        /* Gradien Utama untuk Elemen Aktif */
        .gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        /* Kartu Soal - Glassmorphism effect */
        .quiz-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        /* State Aktif: Saat soal sudah dijawab */
        .quiz-card.active {
            border-color: #60a5fa; /* Border biru muda */
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); /* Ring focus effect */
        }

        /* Indikator visual di kiri kartu saat aktif */
        .quiz-card.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
            background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
        }

        .question-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        /* --- 3. OPSI JAWABAN --- */
        .options-group {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .option-label {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
            color: #475569;
            font-weight: 500;
            background: #f8fafc;
        }

        .option-label:hover {
            border-color: #93c5fd;
            background-color: #eff6ff;
            transform: translateX(4px);
        }

        .option-input { display: none; }

        /* State Checked */
        .option-input:checked + .option-label {
            background-color: #eff6ff;
            border-color: #2563eb;
            color: #1e40af;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1);
        }

        /* --- 4. TOMBOL RESET (Hapus Jawaban) --- */
        .btn-reset-answer {
            font-size: 0.85rem;
            color: #94a3b8;
            background: none;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
            padding: 0.5rem 0;
        }
        .btn-reset-answer:hover {
            color: #ef4444;
            text-decoration: underline;
        }

        /* --- 5. PROGRESS & STATUS --- */
        .status-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .progress-text-big {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #2563eb, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .custom-progress-track {
            height: 0.85rem;
            background-color: #f1f5f9;
            border-radius: 99px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .custom-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 99px;
        }

        /* --- 6. TOMBOL SUBMIT GLOWING --- */
        .btn-submit-container {
            position: relative;
            z-index: 10;
        }

        .btn-submit-main {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 1rem;
            font-size: 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.5), 0 8px 10px -6px rgba(37, 99, 235, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 35px -5px rgba(37, 99, 235, 0.6);
            filter: brightness(110%);
        }

        .btn-submit-main::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-submit-main:hover::after {
            left: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-24">

        {{-- HEADER --}}
        <div class="mb-12 text-center relative">
            <div class="inline-block bg-white/10 backdrop-blur-md border border-white/20 text-white shadow-xl px-5 py-2 rounded-full mb-5 text-sm font-bold tracking-wide">
                <i class="bi bi-patch-check-fill text-cyan-400 me-2"></i> UJIAN SERTIFIKASI
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight drop-shadow-md">
                Ujian Kompetensi
            </h2>
            <p class="text-slate-300 text-xl max-w-2xl mx-auto font-light">
                Buktikan kemampuanmu. Jawab dengan teliti untuk mencapai nilai kelulusan <strong class="text-cyan-400 font-bold border-b-2 border-cyan-400">70</strong>.
            </p>
        </div>

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="max-w-4xl mx-auto p-4 mb-8 text-sm text-red-200 bg-red-900/50 border border-red-500/30 rounded-xl backdrop-blur-sm shadow-lg flex items-center gap-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form id="examForm">
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- KOLOM KIRI: SOAL (Main Content) --}}
                <div class="w-full lg:w-2/3 order-2 lg:order-1">

                    {{-- INFO COURSE --}}
                    <div id="course-info-box" class="bg-white/95 rounded-2xl p-6 mb-8 hidden shadow-xl border-l-4 border-indigo-500">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
                                <i class="bi bi-journal-bookmark-fill text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-gray-800" id="course-title"></h5>
                                <p class="text-gray-500 text-sm mt-1" id="course-sub"></p>
                            </div>
                        </div>
                    </div>

                    {{-- CONTAINER SOAL --}}
                    <div class="space-y-8" id="daftar-soal-container">
                        {{-- Loading State --}}
                        <div class="text-center py-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                            <div class="spinner-border text-cyan-400 w-10 h-10 mb-4" role="status"></div>
                            <p class="text-slate-200 font-medium text-lg">Menyiapkan lembar ujian...</p>
                        </div>
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="pt-10 mt-6 hidden btn-submit-container" id="tombol-submit-container">
                        <button type="button" onclick="submitExam()"
                            class="w-full btn-submit-main py-5 flex items-center justify-center gap-3 font-bold tracking-wider">
                            <i class="bi bi-rocket-takeoff-fill text-2xl"></i>
                            <span>KIRIM JAWABAN SEKARANG</span>
                        </button>
                    </div>
                </div>

                {{-- KOLOM KANAN: PROGRESS (Sticky Sidebar) --}}
                <div class="w-full lg:w-1/3 order-1 lg:order-2">
                    <div class="sticky top-8 space-y-6">
                        <div class="status-box">
                            <div class="text-center mb-6">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Progress Pengerjaan</p>
                                <div class="flex items-baseline justify-center">
                                    <span id="progress-text" class="progress-text-big">0%</span>
                                </div>
                            </div>

                            <div class="custom-progress-track mb-6 w-full">
                                <div class="custom-progress-fill" style="width: 0%"></div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl text-sm text-blue-700">
                                <i class="bi bi-info-circle-fill text-lg shrink-0 mt-0.5"></i>
                                <span id="status-message" class="font-medium">
                                    Selesaikan seluruh soal untuk mengaktifkan tombol kirim.
                                </span>
                            </div>
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

        // 1. Render Option
        function renderOption(idSoal, abjad, text) {
            if (!text) return '';
            return `
                <input class="option-input" type="radio" name="q${idSoal}" id="opt-${idSoal}-${abjad}" value="${abjad}">
                <label class="option-label" for="opt-${idSoal}-${abjad}">
                    <span class="w-8 h-8 me-4 flex items-center justify-center text-sm font-bold rounded-full border border-gray-300 transition-all duration-300 group-hover:border-blue-400 bg-white">
                        ${abjad}
                    </span>
                    <span class="text-gray-700 text-lg">${text}</span>
                </label>
            `;
        }

        // 2. Logic Interaction
        function attachInputListeners() {
            const inputs = document.querySelectorAll('input.option-input');
            const progressBar = document.querySelector('.custom-progress-fill');
            const progressText = document.getElementById('progress-text');

            function updateProgress() {
                let answered = new Set();
                document.querySelectorAll('input.option-input').forEach(input => {
                    if (input.checked) answered.add(input.name);
                });

                let percent = totalSoal === 0 ? 0 : (answered.size / totalSoal) * 100;
                const roundedPercent = Math.round(percent);

                progressBar.style.width = roundedPercent + '%';
                progressText.innerText = roundedPercent + '%';

                document.querySelectorAll('.quiz-card').forEach(card => {
                    const idSoal = card.id.replace('card-', '');
                    if (document.querySelector(`input[name="q${idSoal}"]:checked`)) {
                        card.classList.add('active');
                    } else {
                        card.classList.remove('active');
                    }
                });

                document.querySelectorAll('input.option-input').forEach(input => {
                    const label = document.querySelector(`label[for="${input.id}"]`);
                    const abjadSpan = label ? label.querySelector('span') : null;
                    if (!abjadSpan) return;

                    if (input.checked) {
                        abjadSpan.className = 'w-8 h-8 me-4 flex items-center justify-center text-sm font-bold rounded-full transition-all duration-300 bg-blue-600 text-white shadow-lg border-transparent scale-110';
                    } else {
                        abjadSpan.className = 'w-8 h-8 me-4 flex items-center justify-center text-sm font-bold rounded-full border border-gray-300 transition-all duration-300 bg-white text-gray-500';
                    }
                });
            }

            updateProgress();
            inputs.forEach(input => input.addEventListener('change', updateProgress));
            window.examUpdateProgress = updateProgress;
        }

        function clearAnswer(idSoal) {
            const radios = document.getElementsByName('q' + idSoal);
            radios.forEach(radio => radio.checked = false);
            if(window.examUpdateProgress) window.examUpdateProgress();
        }

        // 3. Load Data
        async function loadSoalSertifikasi() {
            // FIX: Mendefinisikan semua variabel DOM di awal agar tidak error
            const container = document.getElementById('daftar-soal-container');
            const submitBox = document.getElementById('tombol-submit-container');
            const courseBox = document.getElementById('course-info-box');
            const courseTitle = document.getElementById('course-title');
            const courseSub = document.getElementById('course-sub');
            const statusMessage = document.getElementById('status-message');

            // Reset UI
            statusMessage.textContent = 'Memuat status ujian...';
            document.getElementById('progress-text').innerText = '0%';
            document.querySelector('.custom-progress-fill').style.width = '0%';

            try {
                const res = await fetch(`${apiBase}/sertifikasi/soal?id_member=${currentMemberId}`);
                const json = await res.json();

                if (!res.ok) {
                    container.innerHTML = `
                        <div class="p-6 bg-red-100 text-red-700 rounded-xl border border-red-300 shadow-md">
                            <i class="bi bi-x-octagon-fill me-2"></i> <strong>Gagal memuat data.</strong><br>
                            ${json.message || 'Silakan hubungi admin.'}
                        </div>`;
                    return;
                }

                const data = json.data;
                currentTes = data;
                currentSoal = data.soal || [];
                totalSoal = currentSoal.length;

                // Info Course
                if (data.course) {
                    courseBox.classList.remove('hidden');
                    courseTitle.textContent = `${data.course.bahasa?.nama_bahasa || '-'} — ${data.course.paket?.nama_paket || '-'}`;
                    courseSub.innerHTML = `<span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-bold me-2">KKM: ${data.kkm || 70}</span> Total: ${totalSoal} Soal`;
                }

                // Cek already taken
                if (data.already_taken && data.hasil) {
                    renderHasil(container, data.hasil);
                    return;
                }

                if (!totalSoal) {
                    container.innerHTML = `<div class="p-6 bg-yellow-50 text-yellow-700 rounded-xl">Belum ada soal tersedia.</div>`;
                    return;
                }

                // Render Soal
                container.innerHTML = '';
                currentSoal.forEach((soal, index) => {
                    const nomor = index + 1;
                    const htmlSoal = `
                        <div class="quiz-card" id="card-${soal.id_soal}">
                            <div class="flex justify-between items-start mb-4">
                                <h5 class="question-text flex-1">
                                    <span class="text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg me-2 border border-blue-100 text-base">#${nomor}</span>
                                    ${soal.pertanyaan}
                                </h5>
                            </div>

                            <div class="options-group ps-2">
                                ${renderOption(soal.id_soal, 'A', soal.opsi_a)}
                                ${renderOption(soal.id_soal, 'B', soal.opsi_b)}
                                ${renderOption(soal.id_soal, 'C', soal.opsi_c)}
                                ${renderOption(soal.id_soal, 'D', soal.opsi_d)}
                            </div>

                            <button type="button" class="btn-reset-answer" onclick="clearAnswer(${soal.id_soal})">
                                <i class="bi bi-eraser-fill"></i> Hapus Jawaban
                            </button>
                        </div>
                    `;
                    container.innerHTML += htmlSoal;
                });

                submitBox.classList.remove('hidden');
                attachInputListeners();
                statusMessage.textContent = 'Jawab semua soal. Ujian tidak bisa diulang.';

            } catch (err) {
                console.error(err);
                container.innerHTML = `<div class="p-4 bg-red-500 text-white rounded">Koneksi Error: ${err.message}</div>`;
            }
        }

        function renderHasil(container, h) {
            const isLulus = h.status === 'Lulus';
            const colorClass = isLulus ? 'text-green-600 bg-green-50 border-green-200' : 'text-red-600 bg-red-50 border-red-200';

            container.innerHTML = `
                <div class="bg-white rounded-2xl p-10 text-center shadow-2xl border border-gray-100">
                    <div class="w-24 h-24 mx-auto ${isLulus ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'} rounded-full flex items-center justify-center mb-6">
                        <i class="bi bi-${isLulus ? 'trophy-fill' : 'emoji-frown-fill'} text-4xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mb-2">Kamu sudah menyelesaikan ujian ini</h3>
                    <p class="text-slate-500 mb-8">Hasil ujian terakhir pada ${h.tanggal}</p>

                    <div class="inline-block p-6 rounded-2xl border-2 ${colorClass} mb-8 min-w-[200px]">
                        <div class="text-sm font-bold uppercase tracking-widest opacity-70">SKOR AKHIR</div>
                        <div class="text-6xl font-black my-2">${h.skor}</div>
                        <div class="font-bold text-lg px-3 py-1 rounded-full inline-block ${isLulus ? 'bg-green-200' : 'bg-red-200'}">${h.status}</div>
                    </div>

                    <div>
                        <a href="{{ route('dashboard.sertifikasi.sertifikat') }}" class="btn-submit-main px-8 py-3 inline-flex items-center gap-2 rounded-xl text-lg font-bold">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Lihat Sertifikat
                        </a>
                    </div>
                </div>
            `;
            // Sembunyikan sidebar progress
            const sidebar = document.querySelector('.w-full.lg\\:w-1\\/3');
            if(sidebar) sidebar.style.display = 'none';

            const mainCol = document.querySelector('.w-full.lg\\:w-2\\/3');
            if(mainCol) mainCol.classList.remove('lg:w-2/3');
        }

        // 4. Submit
        function submitExam() {
            const checked = document.querySelectorAll('input.option-input:checked');

            if (checked.length < totalSoal) {
                const sisa = totalSoal - checked.length;
                Swal.fire({
                    title: 'Belum Selesai!',
                    text: `Masih ada ${sisa} soal yang belum kamu jawab.`,
                    icon: 'warning',
                    confirmButtonText: 'Lanjutkan',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            if (!currentTes) return;

            const answers = [];
            checked.forEach(input => {
                answers.push({
                    id_soal: parseInt(input.name.replace('q', '')),
                    jawaban: input.value
                });
            });

            Swal.fire({
                title: 'Kirim Jawaban?',
                html: "Pastikan semua jawaban sudah benar.<br><strong>Ujian hanya bisa dilakukan 1 kali.</strong>",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim Sekarang',
                cancelButtonText: 'Periksa Lagi',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    processSubmission(answers);
                }
            });
        }

        function processSubmission(answers) {
            Swal.fire({
                title: 'Memeriksa Jawaban...',
                text: 'Sistem sedang menghitung skor kamu',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
                background: '#fff',
                customClass: { popup: 'rounded-xl' }
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
            .then(res => res.json().then(data => ({ ok: res.ok, body: data })))
            .then(({ ok, body }) => {
                if (!ok) throw new Error(body.message || 'Gagal mengirim.');

                const d = body.data;
                const isPass = d.status === 'Lulus';

                Swal.fire({
                    title: isPass ? 'LUAR BIASA! 🎉' : 'JANGAN MENYERAH',
                    html: `
                        <div class="mt-2">
                            <div class="text-6xl font-black ${isPass ? 'text-green-500' : 'text-red-500'} mb-2">${d.skor}</div>
                            <div class="text-lg font-bold text-gray-700 mb-4">${d.status}</div>
                            <p class="text-sm text-gray-500">
                                Kamu menjawab benar <strong>${d.benar}</strong> dari ${d.total} soal.<br>
                                (Nilai KKM: ${d.kkm})
                            </p>
                        </div>
                    `,
                    icon: isPass ? 'success' : 'error',
                    confirmButtonText: isPass ? 'Ambil Sertifikat' : 'Kembali',
                    confirmButtonColor: isPass ? '#10b981' : '#334155',
                    allowOutsideClick: false
                }).then(() => {
                    window.location.href = isPass
                        ? "{{ route('dashboard.sertifikasi.sertifikat') }}"
                        : "{{ route('dashboard.sertifikasi') }}";
                });
            })
            .catch(err => {
                Swal.fire('Terjadi Kesalahan', err.message, 'error');
            });
        }
    </script>
@endsection
