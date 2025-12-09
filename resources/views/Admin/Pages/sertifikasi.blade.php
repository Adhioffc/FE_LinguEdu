@extends('layouts.admin')

@section('title', 'Manajemen Uji Sertifikasi')

@section('content')
    <div class="container py-5">

        {{-- NAVIGASI --}}
        <a href="/admin/dashboard" class="text-primary d-block mb-3">
            ← Kembali ke Dashboard
        </a>

        <h2 class="fw-bold mb-2">🏅 Manajemen Uji Sertifikasi</h2>
        <p class="text-muted mb-4">
            Kelola soal uji sertifikasi per <strong>Paket + Bahasa</strong>.
        </p>

        {{-- FILTER ATAS: PAKET / BAHASA --}}
        <div class="card mb-4">
            <div class="card-body">

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="fw-semibold">Paket</label>
                        <select id="selectPaket" class="form-select">
                            <option value="">-- Pilih Paket --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="fw-semibold">Bahasa</label>
                        <select id="selectBahasa" class="form-select">
                            <option value="">-- Pilih Bahasa --</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Uji sertifikasi dibuat per kombinasi Paket + Bahasa (per kursus).
                    </small>
                </div>
            </div>
        </div>

        {{-- INFO UJI SERTIF & TOMBOL TAMBAH SOAL --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0 fw-semibold">Daftar Soal Uji Sertifikasi</h5>
                <small id="infoUjiText" class="text-muted d-block">
                    Pilih Paket dan Bahasa terlebih dahulu.
                </small>
            </div>
            <button class="btn btn-primary" id="btnAddSoal" disabled>
                + Tambah Soal Sertifikasi
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
                            Pilih Paket dan Bahasa terlebih dahulu.
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
                    <h5 class="modal-title">Tambah Soal Sertifikasi</h5>
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
                    <h5 class="modal-title">Edit Soal Sertifikasi</h5>
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
        let currentTes = null;   // { kode_tes, id_course, kkm, ... }
        let currentSoal = [];    // array soal sertifikasi

        const selectPaket = document.getElementById('selectPaket');
        const selectBahasa = document.getElementById('selectBahasa');
        const soalTable   = document.getElementById('soalTable');
        const btnAddSoal  = document.getElementById('btnAddSoal');
        const infoUjiText = document.getElementById('infoUjiText');

        /* =============================
           LOAD MASTER DATA (PAKET & BAHASA)
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
                alert(e.response?.data?.message || 'Gagal memuat paket');
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
                alert(e.response?.data?.message || 'Gagal memuat bahasa');
            }
        }

        /* =============================
           LOAD TES + SOAL UNTUK KOMBINASI PAKET+BAHASA
        ============================== */

        async function loadTesForCourse() {
            const idPaket  = selectPaket.value;
            const idBahasa = selectBahasa.value;

            currentTes  = null;
            currentSoal = [];
            renderSoal();

            btnAddSoal.disabled = !(idPaket && idBahasa);
            infoUjiText.textContent = 'Pilih Paket dan Bahasa terlebih dahulu.';

            if (!idPaket || !idBahasa) {
                return;
            }

            try {
                const res  = await api.get('/admin/sertifikasi/tes');
                const list = Array.isArray(res.data.data) ? res.data.data : res.data;

                const found = list.find(t =>
                    String(t.course?.id_paket)  === String(idPaket) &&
                    String(t.course?.id_bahasa) === String(idBahasa)
                );

                currentTes = found || null;

                if (!currentTes) {
                    infoUjiText.textContent =
                        'Belum ada uji sertifikasi untuk kombinasi Paket + Bahasa ini. ' +
                        'Tambah soal pertama akan otomatis membuat uji sertifikasi.';
                    renderSoal();
                    return;
                }

                infoUjiText.textContent =
                    `Uji Sertifikasi terdaftar (Kode Tes: ${currentTes.kode_tes}). ` +
                    `Total soal: ${currentTes.jumlah_soal ?? (currentTes.soal_sertifikasi?.length || '-')}.`;

                await loadSoalForTes(currentTes.kode_tes);

            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal memuat uji sertifikasi');
            }
        }

        async function loadSoalForTes(kodeTes) {
            currentSoal = [];
            soalTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Memuat soal...
                    </td>
                </tr>`;

            try {
                const res = await api.get(`/admin/sertifikasi/soal/${kodeTes}`);
                currentSoal = Array.isArray(res.data.data) ? res.data.data : res.data;
                renderSoal();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal memuat soal sertifikasi');
            }
        }

        /* =============================
           RENDER TABEL SOAL
        ============================== */

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

        function renderSoal() {
            soalTable.innerHTML = '';

            if (!selectPaket.value || !selectBahasa.value) {
                soalTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Pilih Paket dan Bahasa terlebih dahulu.
                        </td>
                    </tr>`;
                return;
            }

            if (!currentSoal.length) {
                soalTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada soal untuk uji sertifikasi ini.
                        </td>
                    </tr>`;
                return;
            }

            currentSoal.forEach((s, index) => {
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
                        <td class="fw-bold">${escapeHtml(s.jawaban_benar)}</td>
                        <td>
                            <button class="btn btn-warning btn-sm mb-1" onclick="openEdit(${index})">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteSoal(${index})">
                                Hapus
                            </button>
                        </td>
                    </tr>`;
            });
        }

        /* =============================
           TAMBAH SOAL
        ============================== */

        btnAddSoal.onclick = () => {
            if (!selectPaket.value || !selectBahasa.value) {
                alert('Pilih Paket dan Bahasa dulu.');
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
            const idPaket = selectPaket.value;
            const idBahasa = selectBahasa.value;

            if (!pertanyaan || !opsiA || !opsiB || !opsiC || !opsiD) {
                alert('Pertanyaan dan semua pilihan (A-D) wajib diisi.');
                return;
            }

            try {
                // Kalau belum ada uji sertifikasi untuk kombinasi ini, buat dulu
                if (!currentTes) {
                    const resTes = await api.post('/admin/sertifikasi/tes', {
                        id_paket: idPaket,
                        id_bahasa: idBahasa,
                        // tidak kirim kkm, backend otomatis set 70
                    });
                    currentTes = resTes.data.data || resTes.data;
                    infoUjiText.textContent =
                        `Uji Sertifikasi terdaftar (Kode Tes: ${currentTes.kode_tes}).`;
                }

                await api.post('/admin/sertifikasi/soal/add', {
                    kode_tes: currentTes.kode_tes,
                    pertanyaan: pertanyaan,
                    opsi_a: opsiA,
                    opsi_b: opsiB,
                    opsi_c: opsiC,
                    opsi_d: opsiD,
                    jawaban_benar: correct,
                });

                bootstrap.Modal.getInstance(document.getElementById('modalAdd')).hide();
                await loadSoalForTes(currentTes.kode_tes);
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menyimpan soal sertifikasi');
            }
        };

        /* =============================
           EDIT SOAL
        ============================== */

        window.openEdit = function (index) {
            const s = currentSoal[index];
            if (!s) return;

            document.getElementById('editIndex').value = index;
            document.getElementById('editPertanyaan').value = s.pertanyaan || '';
            document.getElementById('editA').value = s.opsi_a || '';
            document.getElementById('editB').value = s.opsi_b || '';
            document.getElementById('editC').value = s.opsi_c || '';
            document.getElementById('editD').value = s.opsi_d || '';
            document.getElementById('editCorrect').value = s.jawaban_benar || 'A';

            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        };

        document.getElementById('btnSaveEdit').onclick = async () => {
            const idx = document.getElementById('editIndex').value;
            const s = currentSoal[idx];
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
                await api.put(`/admin/sertifikasi/soal/${s.id_soal}`, {
                    pertanyaan: pertanyaan,
                    opsi_a: opsiA,
                    opsi_b: opsiB,
                    opsi_c: opsiC,
                    opsi_d: opsiD,
                    jawaban_benar: correct,
                });

                bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                await loadSoalForTes(currentTes.kode_tes);
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal mengupdate soal sertifikasi');
            }
        };

        /* =============================
           HAPUS SOAL
        ============================== */

        window.deleteSoal = async function (index) {
            const s = currentSoal[index];
            if (!s) return;

            if (!confirm('Yakin ingin menghapus soal sertifikasi ini?')) return;

            try {
                await api.delete(`/admin/sertifikasi/soal/${s.id_soal}`);
                await loadSoalForTes(currentTes.kode_tes);
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus soal sertifikasi');
            }
        };

        /* =============================
           EVENT HANDLER FILTER
        ============================== */

        selectPaket.addEventListener('change', loadTesForCourse);
        selectBahasa.addEventListener('change', loadTesForCourse);

        /* =============================
           INIT
        ============================== */

        document.addEventListener('DOMContentLoaded', async () => {
            await loadPakets();
            await loadBahasas();
        });
    </script>
@endpush
