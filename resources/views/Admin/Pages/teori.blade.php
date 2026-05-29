@extends('layouts.admin')

@section('title', 'Manajemen Teori')

@section('content')
    {{-- DataTables + Bootstrap Icons CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        /* Custom Styling for a Brighter/Interactive Look */
        .fw-semibold-title {
            font-weight: 700;
            color: #34495e; /* Darker blue/grey for titles */
        }
        .card-header-custom {
            background-color: #f8f9fa; /* Light background for header */
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }
        .form-label-styled {
            font-weight: 600;
            color: #2c3e50; /* Stronger label color */
        }
        /* Custom class to ensure the modal body scrolls correctly */
        .modal-body-scrollable-fix {
            overflow-y: auto;
            max-height: calc(100vh - 200px); /* Adjust based on modal header/footer height */
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
    </style>

    <div class="container py-5">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 fw-semibold-title">📘 Manajemen Teori</h2>
                <p class="text-muted mb-0">
                    Kelola konten teori untuk setiap materi pembelajaran.
                </p>
            </div>
            <button class="btn btn-primary px-4 shadow-sm" id="btnAddTeori">
                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Teori
            </button>
        </div>

        {{-- CARD TABEL --}}
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-columns-reverse me-2"></i> Daftar Teori</h5>
            </div>
            <div class="card-body p-4">
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
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg">
                <form id="formTeori">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title fw-bold" id="modalTeoriTitle"><i class="bi bi-journal-check me-2"></i> Tambah Teori</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {{-- FIX: Mengganti modal-body biasa dengan custom class untuk scroll yang lebih baik --}}
                    <div class="modal-body modal-body-scrollable-fix">

                        <input type="hidden" id="teoriId">

                        {{-- PILIH MATERI --}}
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <label class="form-label form-label-styled"><i class="bi bi-book me-1"></i> Materi</label>
                            <select id="selectMateri" class="form-select" required>
                                <option value="">-- Pilih Materi --</option>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                Teori akan terhubung ke materi ini.
                            </small>
                        </div>

                        <hr class="my-4">

                        {{-- OVERVIEW --}}
                        <div class="mb-4">
                            <label class="form-label form-label-styled"><i class="bi bi-lightbulb me-1"></i> Overview</label>
                            <textarea id="inputOverview" class="form-control" rows="3" placeholder="Ringkasan singkat tentang materi ini"></textarea>
                        </div>

                        {{-- KENAPA PENTING --}}
                        <div class="mb-4">
                            <label class="form-label form-label-styled"><i class="bi bi-stars me-1"></i> Kenapa Penting?</label>
                            <textarea id="inputKenapa" class="form-control" rows="3"
                                placeholder="Poin-poin kenapa topik ini penting (boleh pakai bullet dengan enter)"></textarea>
                        </div>

                        {{-- KONSEP DASAR --}}
                        <div class="mb-4">
                            <label class="form-label form-label-styled"><i class="bi bi-puzzle-fill me-1"></i> Konsep Dasar</label>
                            <textarea id="inputKonsep" class="form-control" rows="3" placeholder="Daftar konsep dasar. Misal: 1. ..., 2. ..."></textarea>
                        </div>

                        {{-- CONTOH PRAKTIK --}}
                        <div class="mb-4">
                            <label class="form-label form-label-styled"><i class="bi bi-code-slash me-1"></i> Contoh Praktik / Kode</label>
                            <textarea id="inputContoh" class="form-control font-monospace" rows="4"
                                placeholder="Contoh kode atau ilustrasi praktis"></textarea>
                        </div>

                        {{-- RINGKASAN --}}
                        <div class="mb-1">
                            <label class="form-label form-label-styled"><i class="bi bi-check-circle-fill me-1"></i> Ringkasan</label>
                            <textarea id="inputRingkasan" class="form-control" rows="3" placeholder="Ringkasan akhir untuk menutup materi"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success" id="btnSaveTeori">
                            <i class="bi bi-floppy-fill me-1"></i> Simpan
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
        // ... (Kode JS yang lain tidak berubah, hanya dimodifikasi sedikit agar lebih rapi) ...

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
            // Menghilangkan newline sebelum truncate
            const cleanStr = str.replace(/(\r\n|\n|\r)/gm, ' ');
            return cleanStr.length > max ? cleanStr.slice(0, max) + '…' : cleanStr;
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
                // alert('Gagal memuat daftar materi'); // Nonaktifkan alert agar tidak mengganggu
            }
        }

        async function loadTeori() {
            try {
                teoriTable.innerHTML = `
                    <tr>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div> Memuat data teori...
                        </td>
                    </tr>`;

                const res = await api.get('/admin/teori');
                listTeori = Array.isArray(res.data.data) ? res.data.data : (res.data || []);

                renderTable();
            } catch (e) {
                console.error(e);
                teoriTable.innerHTML = `
                    <tr>
                        <td class="text-center text-danger py-4">&nbsp;</td>
                        <td class="text-center text-danger py-4">&nbsp;</td>
                        <td class="text-center text-danger py-4">&nbsp;</td>
                        <td class="text-center text-danger py-4">&nbsp;</td>
                        <td class="text-center text-danger py-4">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal memuat data teori.
                        </td>
                    </tr>`;
            }
        }

        function renderTable() {
            // hancurkan DataTable lama kalau sudah ada
            if (dt) {
                dt.destroy();
                dt = null;
                $('#teoriDataTable').off('click', '.btn-edit'); // Hapus event listener lama
                $('#teoriDataTable').off('click', '.btn-delete'); // Hapus event listener lama
            }

            if (!listTeori.length) {
                teoriTable.innerHTML = `
                    <tr>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">&nbsp;</td>
                        <td class="text-center text-muted py-4">
                            <i class="bi bi-info-circle me-1"></i> Belum ada data teori. Klik <strong>Tambah Teori</strong> untuk membuat.
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
                        <td class="fw-semibold text-center">${escapeHtml(levelText)}</td>
                        <td class="text-muted small">
                            ${escapeHtml(truncate(t.overview || t.ringkasan || ''))}
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white me-1 btn-edit"
                                    data-id="${t.id}" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete"
                                    data-id="${t.id}" title="Hapus">
                                <i class="bi bi-trash-fill"></i>
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
                destroy: true, // Tambahkan destroy: true untuk re-initialization yang aman
                language: {
                    search: "Cari:",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Belum ada data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "<i class='bi bi-chevron-right'></i>",
                        previous: "<i class='bi bi-chevron-left'></i>"
                    }
                }
            });

            // Re-attach event listeners menggunakan delegasi event
            $('#teoriDataTable').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                editTeori(id);
            });

            $('#teoriDataTable').on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                deleteTeori(id);
            });
        }

        function openCreateModal() {
            modalTitle.innerHTML = '<i class="bi bi-journal-plus me-2"></i> Tambah Teori Baru';
            inputTeoriId.value = '';
            document.getElementById('formTeori').reset(); // Reset form
            teoriModal.show();
        }

        window.editTeori = function(id) {
            const t = listTeori.find(x => String(x.id) === String(id));
            if (!t) return;

            modalTitle.innerHTML = `<i class="bi bi-pencil-square me-2"></i> Edit Teori: ${t.materi?.judul || 'Teori'}`;
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
            if (!confirm('Yakin ingin menghapus teori ini? Aksi ini tidak bisa dibatalkan!')) return;

            try {
                await api.delete(`/admin/teori/${id}`);
                listTeori = listTeori.filter(t => String(t.id) !== String(id));
                renderTable();
                alert('Teori berhasil dihapus!');
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus teori');
            }
        };

        document.getElementById('formTeori').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btnSave = document.getElementById('btnSaveTeori');
            const originalText = btnSave.innerHTML;

            btnSave.disabled = true;
            btnSave.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;

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
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
                return;
            }

            try {
                const id = inputTeoriId.value;

                if (id) {
                    await api.put(`/admin/teori/${id}`, payload); // UPDATE
                    alert('Teori berhasil diperbarui!');
                } else {
                    await api.post('/admin/teori', payload); // CREATE
                    alert('Teori berhasil ditambahkan!');
                }

                teoriModal.hide();
                await loadTeori();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menyimpan teori');
            } finally {
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
            }
        });

        document.addEventListener('DOMContentLoaded', async () => {
            // Memastikan modal Bootstrap dimuat dari JS
            if (typeof bootstrap !== 'undefined') {
                teoriModal = new bootstrap.Modal(document.getElementById('modalTeori'));
            } else {
                console.error("Bootstrap JS not loaded!");
            }

            await loadMateriOptions();
            await loadTeori();

            btnAddTeori.addEventListener('click', openCreateModal);
        });
    </script>
@endpush
