@extends('layouts.admin')

@section('title', 'Manajemen Teori Materi')

@section('content')
    <div class="container py-5">
        <a href="/admin/dashboard" class="text-primary d-block mb-3">
            ← Kembali ke Dashboard
        </a>

        <h2 class="fw-bold mb-2">📘 Manajemen Teori Materi</h2>
        <p class="text-muted mb-4">
            Kelola konten teori (overview, kenapa penting, konsep dasar, contoh, ringkasan) per materi.
        </p>

        {{-- FILTER ATAS: PAKET / BAHASA / LEVEL / MATERI --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="fw-semibold">Paket</label>
                        <select id="selectPaket" class="form-select">
                            <option value="">-- Pilih Paket --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold">Bahasa</label>
                        <select id="selectBahasa" class="form-select">
                            <option value="">-- Pilih Bahasa --</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="fw-semibold">Level</label>
                        <select id="selectLevel" class="form-select">
                            <option value="">Semua</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                            <option value="3">Level 3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-semibold">Materi</label>
                        <select id="selectMateri" class="form-select">
                            <option value="">-- Pilih Paket + Bahasa (+ Level) dulu --</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        Sistem akan mencari materi berdasarkan kombinasi Paket + Bahasa + Level.
                    </small>
                </div>
            </div>
        </div>

        {{-- FORM TEORI --}}
        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Konten Teori</h5>
                <p class="text-muted small mb-4">
                    Pilih materi terlebih dahulu, lalu isi konten di bawah. Data akan tampil di halaman teori member.
                </p>

                <input type="hidden" id="currentTeoriId">

                <div class="mb-3">
                    <label class="fw-semibold">Overview</label>
                    <textarea id="teoriOverview" class="form-control" rows="3" placeholder="Gambaran umum materi..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Kenapa Penting?</label>
                    <textarea id="teoriKenapa" class="form-control" rows="3"
                        placeholder="Kenapa materi ini penting dipelajari? (boleh pakai bullet: satu poin per baris)"></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Konsep Dasar</label>
                    <textarea id="teoriKonsep" class="form-control" rows="3"
                        placeholder="Konsep dasar utama (satu poin per baris, misal: 1. Variabel ... )"></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Contoh Praktik (Kode / Kasus)</label>
                    <textarea id="teoriContoh" class="form-control" rows="3" placeholder="Contoh praktis, bisa berupa potongan kode."></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Ringkasan</label>
                    <textarea id="teoriRingkasan" class="form-control" rows="3" placeholder="Ringkasan poin-poin penting."></textarea>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-outline-danger" id="btnDeleteTeori" disabled>
                        Hapus Teori
                    </button>
                    <button class="btn btn-primary" id="btnSaveTeori" disabled>
                        Simpan Teori
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const api = axios.create({
            baseURL: 'http://127.0.0.1:8000/api',
            headers: {
                Accept: 'application/json'
            },
        });

        let pakets = [];
        let bahasas = [];
        let materis = [];
        let currentTeori = null; // {id_teori, ...}

        const selectPaket = document.getElementById('selectPaket');
        const selectBahasa = document.getElementById('selectBahasa');
        const selectLevel = document.getElementById('selectLevel');
        const selectMateri = document.getElementById('selectMateri');

        const teoriOverview = document.getElementById('teoriOverview');
        const teoriKenapa = document.getElementById('teoriKenapa');
        const teoriKonsep = document.getElementById('teoriKonsep');
        const teoriContoh = document.getElementById('teoriContoh');
        const teoriRingkasan = document.getElementById('teoriRingkasan');

        const btnSaveTeori = document.getElementById('btnSaveTeori');
        const btnDeleteTeori = document.getElementById('btnDeleteTeori');

        /* ========== LOAD MASTER DATA ========== */

        async function loadPakets() {
            try {
                const res = await api.get('/paket');
                pakets = Array.isArray(res.data.data) ? res.data.data : res.data;
                selectPaket.innerHTML = '<option value="">-- Pilih Paket --</option>';
                pakets.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.nama_paket || ('Paket #' + p.id);
                    selectPaket.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
                alert('Gagal memuat paket');
            }
        }

        async function loadBahasas() {
            try {
                const res = await api.get('/bahasa');
                bahasas = Array.isArray(res.data.data) ? res.data.data : res.data;
                selectBahasa.innerHTML = '<option value="">-- Pilih Bahasa --</option>';
                bahasas.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.textContent = b.nama_bahasa || ('Bahasa #' + b.id);
                    selectBahasa.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
                alert('Gagal memuat bahasa');
            }
        }

        async function loadMateris() {
            const idPaket = selectPaket.value;
            const idBahasa = selectBahasa.value;
            const level = selectLevel.value;

            resetForm();
            selectMateri.innerHTML = '<option value="">-- Pilih Paket + Bahasa (+ Level) dulu --</option>';

            if (!idPaket || !idBahasa) {
                btnSaveTeori.disabled = true;
                btnDeleteTeori.disabled = true;
                return;
            }

            try {
                const params = {
                    paket: idPaket,
                    bahasa: idBahasa
                };
                if (level) params.level = level;

                const res = await api.get('/admin/materi/filter', {
                    params
                });
                materis = Array.isArray(res.data.data) ? res.data.data : res.data;

                if (!materis.length) {
                    selectMateri.innerHTML = '<option value="">-- Tidak ada materi untuk filter ini --</option>';
                    return;
                }

                selectMateri.innerHTML = '<option value="">-- Pilih Materi --</option>';
                materis.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m.id_materi;
                    opt.textContent = `Level ${m.level} - ${m.judul}`;
                    selectMateri.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
                alert('Gagal memuat materi');
            }
        }

        /* ========== LOAD TEORI PER MATERI ========== */

        async function loadTeoriForMateri() {
            resetForm();

            const idMateri = selectMateri.value;
            if (!idMateri) {
                btnSaveTeori.disabled = true;
                btnDeleteTeori.disabled = true;
                return;
            }

            btnSaveTeori.disabled = false;

            try {
                const res = await api.get(`/admin/teori/by-materi/${idMateri}`);
                currentTeori = res.data.data;

                teoriOverview.value = currentTeori.overview ?? '';
                teoriKenapa.value = currentTeori.kenapa_penting ?? '';
                teoriKonsep.value = currentTeori.konsep_dasar ?? '';
                teoriContoh.value = currentTeori.contoh_praktik ?? '';
                teoriRingkasan.value = currentTeori.ringkasan ?? '';

                btnDeleteTeori.disabled = false;
            } catch (e) {
                currentTeori = null;
                // 404 = belum ada teori → form dibiarkan kosong untuk create
                btnDeleteTeori.disabled = true;
                if (e.response && e.response.status !== 404) {
                    console.error(e);
                    alert('Gagal memuat teori');
                }
            }
        }

        function resetForm() {
            currentTeori = null;
            teoriOverview.value = '';
            teoriKenapa.value = '';
            teoriKonsep.value = '';
            teoriContoh.value = '';
            teoriRingkasan.value = '';
            btnSaveTeori.disabled = true;
            btnDeleteTeori.disabled = true;
        }

        /* ========== SIMPAN (CREATE/UPDATE) ========== */

        btnSaveTeori.addEventListener('click', async () => {
            const idMateri = selectMateri.value;
            if (!idMateri) {
                alert('Pilih materi dulu.');
                return;
            }

            const payload = {
                overview: teoriOverview.value,
                kenapa_penting: teoriKenapa.value,
                konsep_dasar: teoriKonsep.value,
                contoh_praktik: teoriContoh.value,
                ringkasan: teoriRingkasan.value,
            };

            try {
                if (!currentTeori) {
                    // CREATE
                    await api.post('/admin/teori', {
                        id_materi: idMateri,
                        ...payload,
                    });
                } else {
                    // UPDATE
                    await api.put(`/admin/teori/${currentTeori.id_teori}`, payload);
                }

                alert('Teori berhasil disimpan.');
                await loadTeoriForMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menyimpan teori');
            }
        });

        /* ========== HAPUS ========== */

        btnDeleteTeori.addEventListener('click', async () => {
            if (!currentTeori) return;
            if (!confirm('Yakin ingin menghapus teori untuk materi ini?')) return;

            try {
                await api.delete(`/admin/teori/${currentTeori.id_teori}`);
                alert('Teori berhasil dihapus.');
                resetForm();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus teori');
            }
        });

        /* ========== EVENT LISTENER FILTER ========== */

        selectPaket.addEventListener('change', loadMateris);
        selectBahasa.addEventListener('change', loadMateris);
        selectLevel.addEventListener('change', loadMateris);
        selectMateri.addEventListener('change', loadTeoriForMateri);

        /* ========== INIT ========== */
        document.addEventListener('DOMContentLoaded', async () => {
            await loadPakets();
            await loadBahasas();
        });
    </script>
@endpush
