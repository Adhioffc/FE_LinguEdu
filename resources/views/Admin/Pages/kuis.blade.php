@extends('layouts.admin')

@section('title', 'Manajemen Kuis & Evaluasi')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-2">📝 Manajemen Kuis & Evaluasi</h2>
        <p class="text-muted mb-4">Kelola soal kuis berdasarkan Paket, Bahasa, Level, dan Materi.</p>

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

        {{-- TOMBOL TAMBAH SOAL --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-semibold">Daftar Soal Kuis</h5>
            <button class="btn btn-primary" id="btnAddSoal" disabled>
                + Tambah Soal
            </button>
        </div>

        {{-- TABEL SOAL --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 30%">Pertanyaan</th>
                        <th style="width: 45%">Pilihan Jawaban</th>
                        <th style="width: 10%">Jawaban Benar</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="soalTable">
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Pilih Paket, Bahasa, Level, dan Materi terlebih dahulu.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH SOAL --}}
    <div class="modal fade" id="modalAdd" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="fw-semibold">Pertanyaan</label>
                        <textarea id="addPertanyaan" class="form-control" rows="3" placeholder="Tulis pertanyaan di sini..."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan A</label>
                            <input type="text" id="addA" class="form-control" placeholder="Teks jawaban A">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan B</label>
                            <input type="text" id="addB" class="form-control" placeholder="Teks jawaban B">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan C</label>
                            <input type="text" id="addC" class="form-control" placeholder="Teks jawaban C">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan D</label>
                            <input type="text" id="addD" class="form-control" placeholder="Teks jawaban D">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="fw-semibold">Jawaban Benar</label>
                        <select id="addCorrect" class="form-select w-auto">
                            <option value="A">Pilihan A</option>
                            <option value="B">Pilihan B</option>
                            <option value="C">Pilihan C</option>
                            <option value="D">Pilihan D</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btnSaveAdd">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT SOAL --}}
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editIndex">

                    <div class="mb-3">
                        <label class="fw-semibold">Pertanyaan</label>
                        <textarea id="editPertanyaan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan A</label>
                            <input type="text" id="editA" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan B</label>
                            <input type="text" id="editB" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan C</label>
                            <input type="text" id="editC" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Pilihan D</label>
                            <input type="text" id="editD" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="fw-semibold">Jawaban Benar</label>
                        <select id="editCorrect" class="form-select w-auto">
                            <option value="A">Pilihan A</option>
                            <option value="B">Pilihan B</option>
                            <option value="C">Pilihan C</option>
                            <option value="D">Pilihan D</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btnSaveEdit">Simpan Perubahan</button>
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
        let currentKuis = null; // { id_kuis, soals: [] }

        const selectPaket = document.getElementById('selectPaket');
        const selectBahasa = document.getElementById('selectBahasa');
        const selectLevel = document.getElementById('selectLevel');
        const selectMateri = document.getElementById('selectMateri');
        const soalTable = document.getElementById('soalTable');
        const btnAddSoal = document.getElementById('btnAddSoal');

        /* =============================
           LOAD MASTER DATA
        ============================== */

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

            selectMateri.innerHTML = '<option value="">-- Pilih Paket + Bahasa (+ Level) dulu --</option>';
            currentKuis = null;
            renderSoal();

            if (!idPaket || !idBahasa) {
                btnAddSoal.disabled = true;
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
                    btnAddSoal.disabled = true;
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

        /* =============================
           LOAD KUIS UNTUK MATERI TERPILIH
        ============================== */

        async function loadKuisForMateri() {
            const idMateri = selectMateri.value;

            currentKuis = null;
            btnAddSoal.disabled = !idMateri;
            soalTable.innerHTML = '';

            if (!idMateri) {
                soalTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Pilih materi terlebih dahulu.
                    </td>
                </tr>`;
                return;
            }

            try {
                const res = await api.get('/admin/kuis');
                const list = Array.isArray(res.data.data) ? res.data.data : res.data;

                const found = list.find(k => String(k.id_materi) === String(idMateri));
                currentKuis = found || null;

                renderSoal();
            } catch (e) {
                console.error(e);
                alert('Gagal memuat kuis');
            }
        }

        /* =============================
           RENDER TABEL SOAL
        ============================== */

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>"']/g, function(m) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                })[m];
            });
        }

        function renderSoal() {
            soalTable.innerHTML = '';

            if (!selectMateri.value) {
                soalTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Pilih materi terlebih dahulu.
                    </td>
                </tr>`;
                return;
            }

            if (!currentKuis || !currentKuis.soals || !currentKuis.soals.length) {
                soalTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada soal untuk materi ini.
                    </td>
                </tr>`;
                return;
            }

            currentKuis.soals.forEach((s, index) => {
                soalTable.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(s.pertanyaan)}</td>
                    <td>
                        <div>A. ${escapeHtml(s.opsi_a || '')}</div>
                        <div>B. ${escapeHtml(s.opsi_b || '')}</div>
                        <div>C. ${escapeHtml(s.opsi_c || '')}</div>
                        <div>D. ${escapeHtml(s.opsi_d || '')}</div>
                    </td>
                    <td class="fw-bold">${escapeHtml(s.jawaban_bnr)}</td>
                    <td>
                        <button class="btn btn-warning btn-sm mb-1" onclick="openEdit(${index})">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteSoal(${index})">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
            });
        }

        /* =============================
           TAMBAH SOAL
        ============================== */

        btnAddSoal.onclick = () => {
            if (!selectMateri.value) {
                alert('Pilih materi dulu.');
                return;
            }
            document.getElementById('addPertanyaan').value = '';
            document.getElementById('addA').value = '';
            document.getElementById('addB').value = '';
            document.getElementById('addC').value = '';
            document.getElementById('addD').value = '';
            document.getElementById('addCorrect').value = 'A';

            new bootstrap.Modal(document.getElementById('modalAdd')).show();
        };

        document.getElementById('btnSaveAdd').onclick = async () => {
            const pertanyaan = document.getElementById('addPertanyaan').value.trim();
            const opsiA = document.getElementById('addA').value.trim();
            const opsiB = document.getElementById('addB').value.trim();
            const opsiC = document.getElementById('addC').value.trim();
            const opsiD = document.getElementById('addD').value.trim();
            const correct = document.getElementById('addCorrect').value;
            const idMateri = selectMateri.value;

            if (!pertanyaan || !opsiA || !opsiB || !opsiC || !opsiD) {
                alert('Pertanyaan dan semua pilihan (A-D) wajib diisi.');
                return;
            }

            try {
                if (!currentKuis) {
                    // Belum ada kuis untuk materi ini → buat kuis baru + soal pertama
                    await api.post('/admin/kuis', {
                        id_materi: idMateri,
                        soal: [{
                            pertanyaan: pertanyaan,
                            opsi_a: opsiA,
                            opsi_b: opsiB,
                            opsi_c: opsiC,
                            opsi_d: opsiD,
                            jawaban_bnr: correct,
                        }],
                    });
                } else {
                    // Sudah ada kuis → tambah soal
                    await api.post(`/admin/kuis/${currentKuis.id_kuis}/soal`, {
                        pertanyaan: pertanyaan,
                        opsi_a: opsiA,
                        opsi_b: opsiB,
                        opsi_c: opsiC,
                        opsi_d: opsiD,
                        jawaban_bnr: correct,
                    });
                }

                bootstrap.Modal.getInstance(document.getElementById('modalAdd')).hide();
                await loadKuisForMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menyimpan soal');
            }
        };

        /* =============================
           EDIT SOAL
        ============================== */

        window.openEdit = function(index) {
            if (!currentKuis || !currentKuis.soals || !currentKuis.soals[index]) return;

            const s = currentKuis.soals[index];

            document.getElementById('editIndex').value = index;
            document.getElementById('editPertanyaan').value = s.pertanyaan || '';
            document.getElementById('editA').value = s.opsi_a || '';
            document.getElementById('editB').value = s.opsi_b || '';
            document.getElementById('editC').value = s.opsi_c || '';
            document.getElementById('editD').value = s.opsi_d || '';
            document.getElementById('editCorrect').value = s.jawaban_bnr || 'A';

            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        };

        document.getElementById('btnSaveEdit').onclick = async () => {
            if (!currentKuis) return;
            const idx = document.getElementById('editIndex').value;
            const s = currentKuis.soals[idx];
            if (!s) return;

            const pertanyaan = document.getElementById('editPertanyaan').value.trim();
            const opsiA = document.getElementById('editA').value.trim();
            const opsiB = document.getElementById('editB').value.trim();
            const opsiC = document.getElementById('editC').value.trim();
            const opsiD = document.getElementById('editD').value.trim();
            const correct = document.getElementById('editCorrect').value;

            if (!pertanyaan || !opsiA || !opsiB || !opsiC || !opsiD) {
                alert('Pertanyaan dan semua pilihan (A-D) wajib diisi.');
                return;
            }

            try {
                await api.put(`/admin/soal-kuis/${s.id_soal_kuis}`, {
                    pertanyaan: pertanyaan,
                    opsi_a: opsiA,
                    opsi_b: opsiB,
                    opsi_c: opsiC,
                    opsi_d: opsiD,
                    jawaban_bnr: correct,
                });

                bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                await loadKuisForMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal mengupdate soal');
            }
        };

        /* =============================
           HAPUS SOAL
        ============================== */

        window.deleteSoal = async function(index) {
            if (!currentKuis || !currentKuis.soals || !currentKuis.soals[index]) return;

            const s = currentKuis.soals[index];

            if (!confirm('Yakin ingin menghapus soal ini?')) return;

            try {
                await api.delete(`/admin/soal-kuis/${s.id_soal_kuis}`);
                await loadKuisForMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus soal');
            }
        };

        /* =============================
           EVENT HANDLER FILTER
        ============================== */

        selectPaket.addEventListener('change', loadMateris);
        selectBahasa.addEventListener('change', loadMateris);
        selectLevel.addEventListener('change', loadMateris);
        selectMateri.addEventListener('change', loadKuisForMateri);

        /* =============================
           INIT
        ============================== */

        document.addEventListener('DOMContentLoaded', async () => {
            await loadPakets();
            await loadBahasas();
        });
    </script>
@endpush
