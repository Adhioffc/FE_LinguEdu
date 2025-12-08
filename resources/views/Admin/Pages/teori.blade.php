@extends('layouts.admin')

@section('title', 'Manajemen Teori')

@section('content')
    {{-- DataTables + Bootstrap Icons CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container py-5">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">📘 Manajemen Teori</h2>
                <p class="text-muted mb-0">
                    Kelola konten teori untuk setiap materi pembelajaran.
                </p>
            </div>
            <button class="btn btn-primary px-4" id="btnAddTeori">
                + Tambah Teori
            </button>
        </div>

        {{-- CARD TABEL --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <table class="table align-middle mb-0" id="teoriDataTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 28%">Judul Materi</th>
                            <th style="width: 25%">Kursus</th>
                            <th style="width: 7%">Level</th>
                            <th style="width: 30%">Overview</th>
                            <th style="width: 10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="teoriTable">
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Memuat data teori...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- MODAL CREATE / EDIT TEORI --}}
    <div class="modal fade" id="modalTeori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formTeori">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTeoriTitle">Tambah Teori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="teoriId">

                        {{-- PILIH MATERI --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Materi</label>
                            <select id="selectMateri" class="form-select" required>
                                <option value="">-- Pilih Materi --</option>
                            </select>
                            <small class="text-muted">
                                Teori akan terhubung ke materi ini.
                            </small>
                        </div>

                        <hr>

                        {{-- OVERVIEW --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Overview</label>
                            <textarea id="inputOverview" class="form-control" rows="3" placeholder="Ringkasan singkat tentang materi ini"></textarea>
                        </div>

                        {{-- KENAPA PENTING --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kenapa Penting?</label>
                            <textarea id="inputKenapa" class="form-control" rows="3"
                                placeholder="Poin-poin kenapa topik ini penting (boleh pakai bullet dengan enter)"></textarea>
                        </div>

                        {{-- KONSEP DASAR --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konsep Dasar</label>
                            <textarea id="inputKonsep" class="form-control" rows="3" placeholder="Daftar konsep dasar. Misal: 1. ..., 2. ..."></textarea>
                        </div>

                        {{-- CONTOH PRAKTIK --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contoh Praktik / Kode</label>
                            <textarea id="inputContoh" class="form-control font-monospace" rows="3"
                                placeholder="Contoh kode atau ilustrasi praktis"></textarea>
                        </div>

                        {{-- RINGKASAN --}}
                        <div class="mb-1">
                            <label class="form-label fw-semibold">Ringkasan</label>
                            <textarea id="inputRingkasan" class="form-control" rows="3" placeholder="Ringkasan akhir untuk menutup materi"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSaveTeori">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Axios + jQuery + DataTables --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        const api = axios.create({
            baseURL: 'http://127.0.0.1:8000/api',
            headers: {
                Accept: 'application/json'
            }
        });

        let listTeori = [];
        let listMateri = [];
        let teoriModal = null;
        let dt = null; // instance DataTable

        const teoriTable = document.getElementById('teoriTable');
        const btnAddTeori = document.getElementById('btnAddTeori');

        const selectMateri = document.getElementById('selectMateri');
        const inputOverview = document.getElementById('inputOverview');
        const inputKenapa = document.getElementById('inputKenapa');
        const inputKonsep = document.getElementById('inputKonsep');
        const inputContoh = document.getElementById('inputContoh');
        const inputRingkasan = document.getElementById('inputRingkasan');
        const inputTeoriId = document.getElementById('teoriId');
        const modalTitle = document.getElementById('modalTeoriTitle');

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

        function truncate(str, max = 80) {
            if (!str) return '';
            return str.length > max ? str.slice(0, max) + '…' : str;
        }

        async function loadMateriOptions() {
            try {
                const res = await api.get('/admin/materi');
                listMateri = Array.isArray(res.data.data) ? res.data.data : (res.data || []);

                selectMateri.innerHTML = '<option value="">-- Pilih Materi --</option>';

                listMateri.forEach(m => {
                    const bahasa = m.course?.bahasa?.nama_bahasa || 'Bahasa ?';
                    const paket = m.course?.paket?.nama_paket || 'Paket ?';
                    const text = `${m.judul || 'Materi'} — Level ${m.level ?? '-'} (${bahasa} - ${paket})`;

                    const opt = document.createElement('option');
                    opt.value = m.id_materi;
                    opt.textContent = text;
                    selectMateri.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
                alert('Gagal memuat daftar materi');
            }
        }

        async function loadTeori() {
            try {
                teoriTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Memuat data teori...
                        </td>
                    </tr>`;

                const res = await api.get('/admin/teori');
                listTeori = Array.isArray(res.data.data) ? res.data.data : (res.data || []);

                renderTable();
            } catch (e) {
                console.error(e);
                teoriTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger py-4">
                            Gagal memuat data teori.
                        </td>
                    </tr>`;
            }
        }

        function renderTable() {
            // hancurkan DataTable lama kalau sudah ada
            if (dt) {
                dt.destroy();
                dt = null;
            }

            if (!listTeori.length) {
                teoriTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada data teori. Klik <strong>Tambah Teori</strong> untuk membuat.
                        </td>
                    </tr>`;
            } else {
                teoriTable.innerHTML = '';

                listTeori.forEach(t => {
                    const materi = t.materi || {};
                    const course = materi.course || {};
                    const bahasa = course.bahasa?.nama_bahasa || '-';
                    const paket = course.paket?.nama_paket || '-';

                    const kursusText = `${bahasa} - ${paket}`;
                    const levelText = materi.level ? `Level ${materi.level}` : '-';

                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td class="fw-semibold">${escapeHtml(materi.judul || '-')}</td>
                        <td>${escapeHtml(kursusText)}</td>
                        <td class="fw-semibold">${escapeHtml(levelText)}</td>
                        <td class="text-muted small">
                            ${escapeHtml(truncate(t.overview || t.ringkasan || ''))}
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1"
                                    onclick="editTeori(${t.id})">
                                <i class="bi bi-pencil-fill me-1"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="deleteTeori(${t.id})">
                                <i class="bi bi-trash-fill me-1"></i>
                            </button>
                        </td>
                    `;

                    teoriTable.appendChild(row);
                });
            }

            // inisialisasi / re-inisialisasi DataTable
            dt = $('#teoriDataTable').DataTable({
                pageLength: 10,
                lengthChange: false,
                language: {
                    search: "Cari:",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Belum ada data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "›",
                        previous: "‹"
                    }
                }
            });
        }

        function openCreateModal() {
            modalTitle.textContent = 'Tambah Teori';
            inputTeoriId.value = '';
            selectMateri.value = '';
            inputOverview.value = '';
            inputKenapa.value = '';
            inputKonsep.value = '';
            inputContoh.value = '';
            inputRingkasan.value = '';

            teoriModal.show();
        }

        window.editTeori = function(id) {
            const t = listTeori.find(x => String(x.id) === String(id));
            if (!t) return;

            modalTitle.textContent = 'Edit Teori';
            inputTeoriId.value = t.id;
            selectMateri.value = t.id_materi ?? '';

            inputOverview.value = t.overview || '';
            inputKenapa.value = t.kenapa_penting || '';
            inputKonsep.value = t.konsep_dasar || '';
            inputContoh.value = t.contoh_praktik || '';
            inputRingkasan.value = t.ringkasan || '';

            teoriModal.show();
        };

        window.deleteTeori = async function(id) {
            if (!confirm('Yakin ingin menghapus teori ini?')) return;

            try {
                await api.delete(`/admin/teori/${id}`);
                listTeori = listTeori.filter(t => String(t.id) !== String(id));
                renderTable();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus teori');
            }
        };

        document.getElementById('formTeori').addEventListener('submit', async function(e) {
            e.preventDefault();

            const payload = {
                id_materi: selectMateri.value,
                overview: inputOverview.value || null,
                kenapa_penting: inputKenapa.value || null,
                konsep_dasar: inputKonsep.value || null,
                contoh_praktik: inputContoh.value || null,
                ringkasan: inputRingkasan.value || null,
            };

            if (!payload.id_materi) {
                alert('Pilih materi terlebih dahulu.');
                return;
            }

            try {
                const id = inputTeoriId.value;

                if (id) {
                    await api.put(`/admin/teori/${id}`, payload); // UPDATE
                } else {
                    await api.post('/admin/teori', payload); // CREATE
                }

                teoriModal.hide();
                await loadTeori();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menyimpan teori');
            }
        });

        document.addEventListener('DOMContentLoaded', async () => {
            teoriModal = new bootstrap.Modal(document.getElementById('modalTeori'));

            await loadMateriOptions();
            await loadTeori();

            btnAddTeori.addEventListener('click', openCreateModal);
        });
    </script>
@endpush
