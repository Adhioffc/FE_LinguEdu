@extends('layouts.member')

@section('title', 'Kuis')

@section('content')
<div class="container py-5">

    <a href="/member/dashboard" class="text-primary d-block mb-3">
        ← Kembali ke Dashboard
    </a>

    <h2 class="fw-bold mb-2">🎓 Kuis Materi</h2>
    <p class="text-muted mb-4" id="quizInfo">
        Memuat informasi kuis...
    </p>

    {{-- ALERT HASIL --}}
    <div id="resultAlert" class="alert d-none" role="alert"></div>

    {{-- FORM KUIS --}}
    <form id="quizForm" class="card">
        <div class="card-body">

            <div id="questionList">
                <p class="text-muted mb-0">
                    Memuat soal...
                </p>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" id="questionCount"></small>
                <button type="submit" class="btn btn-success" id="btnSubmitQuiz" disabled>
                    Kirim Jawaban
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const api = axios.create({
        baseURL: 'http://127.0.0.1:8000/api',
        headers: { Accept: 'application/json' },
    });

    // dari route: /member/kuis/{id_kuis}
    const kuisId = {{ $id_kuis }};
    // user login (kalau pakai auth web biasa)
    const currentMemberId = {{ auth()->id() ?? 'null' }};

    const quizInfoEl     = document.getElementById('quizInfo');
    const questionListEl = document.getElementById('questionList');
    const questionCountEl= document.getElementById('questionCount');
    const resultAlertEl  = document.getElementById('resultAlert');
    const quizForm       = document.getElementById('quizForm');
    const btnSubmitQuiz  = document.getElementById('btnSubmitQuiz');

    let kuisData = null;

    /* =============================
       LOAD QUIZ DATA
    ============================== */

    async function loadQuiz() {
        try {
            const res = await api.get(`/admin/kuis/${kuisId}`);
            kuisData = res.data.data || res.data;

            renderQuizInfo();
            renderQuestions();
        } catch (e) {
            console.error(e);
            quizInfoEl.textContent = 'Gagal memuat kuis.';
            questionListEl.innerHTML = `
                <p class="text-danger mb-0">
                    Terjadi kesalahan saat memuat kuis.
                </p>`;
        }
    }

    function renderQuizInfo() {
        if (!kuisData) return;

        const materi = kuisData.materi || {};
        const course = materi.course || {};
        const bahasa = course.bahasa || {};
        const paket  = course.paket || {};

        const judulMateri = materi.judul || 'Materi tanpa judul';
        const level       = materi.level ? `Level ${materi.level}` : '-';
        const namaBahasa  = bahasa.nama_bahasa || 'Bahasa';
        const namaPaket   = paket.nama_paket || 'Paket';

        quizInfoEl.innerHTML = `
            <strong>${judulMateri}</strong><br>
            ${namaBahasa} - ${namaPaket} | ${level}
        `;
    }

    function renderQuestions() {
        questionListEl.innerHTML = '';
        resultAlertEl.classList.add('d-none');
        resultAlertEl.textContent = '';

        if (!kuisData || !Array.isArray(kuisData.soals) || kuisData.soals.length === 0) {
            questionListEl.innerHTML = `
                <p class="text-muted mb-0">
                    Belum ada soal untuk kuis ini.
                </p>`;
            questionCountEl.textContent = '';
            btnSubmitQuiz.disabled = true;
            return;
        }

        kuisData.soals.forEach((s, idx) => {
            const nomor = idx + 1;
            const wrap = document.createElement('div');
            wrap.className = 'mb-4';

            wrap.innerHTML = `
                <div class="fw-semibold mb-1">
                    Soal ${nomor}
                </div>
                <div class="mb-2">
                    ${escapeHtml(s.pertanyaan)}
                </div>
                <div>
                    <label class="form-label small text-muted">
                        Jawaban Anda
                    </label>
                    <input type="text"
                           class="form-control"
                           name="jawaban_${s.id_soal_kuis}"
                           placeholder="Tulis jawaban di sini...">
                </div>
            `;

            questionListEl.appendChild(wrap);
        });

        questionCountEl.textContent = `${kuisData.soals.length} soal`;
        btnSubmitQuiz.disabled = false;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>"']/g, function (m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            })[m];
        });
    }

    /* =============================
       SUBMIT KUIS
    ============================== */

    quizForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!kuisData || !Array.isArray(kuisData.soals) || kuisData.soals.length === 0) {
            alert('Belum ada soal untuk kuis ini.');
            return;
        }

        if (!currentMemberId) {
            alert('Anda belum login sebagai member.');
            return;
        }

        const answers = [];

        kuisData.soals.forEach((s) => {
            const name = `jawaban_${s.id_soal_kuis}`;
            const input = quizForm.querySelector(`[name="${name}"]`);
            const val = (input?.value || '').trim();

            answers.push({
                id_soal_kuis: s.id_soal_kuis,
                jawaban: val,
            });
        });

        // cek minimal ada jawaban isi
        const adaIsi = answers.some(a => a.jawaban.length > 0);
        if (!adaIsi) {
            alert('Isi minimal satu jawaban dulu.');
            return;
        }

        btnSubmitQuiz.disabled = true;
        btnSubmitQuiz.textContent = 'Mengirim...';

        try {
            const res = await api.post(`/admin/kuis/${kuisId}/submit`, {
                id_member: currentMemberId,
                answers: answers,
            });

            const data = res.data.data || res.data;

            const skor   = data.skor ?? data.hasil?.skor ?? 0;
            const status = data.status ?? data.hasil?.desc ?? '';

            resultAlertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
            resultAlertEl.classList.add(skor >= 60 ? 'alert-success' : 'alert-danger');

            resultAlertEl.innerHTML = `
                <strong>Hasil Kuis:</strong><br>
                Skor: <strong>${skor}</strong><br>
                Status: <strong>${status}</strong>
            `;

            // optional: disable form setelah submit
            Array.from(quizForm.querySelectorAll('input[type="text"]')).forEach(inp => inp.disabled = true);
            btnSubmitQuiz.disabled = true;
            btnSubmitQuiz.textContent = 'Jawaban Terkirim';

        } catch (e) {
            console.error(e);
            resultAlertEl.classList.remove('d-none');
            resultAlertEl.classList.add('alert-danger');
            resultAlertEl.textContent = e.response?.data?.message || 'Gagal mengirim jawaban.';

            btnSubmitQuiz.disabled = false;
            btnSubmitQuiz.textContent = 'Kirim Jawaban';
        }
    });

    /* =============================
       INIT
    ============================== */

    document.addEventListener('DOMContentLoaded', () => {
        loadQuiz();
    });
</script>
@endpush
