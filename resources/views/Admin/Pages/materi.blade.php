@extends('layouts.admin')
@section('title', 'Manajemen Materi')

@section('content')
    <div class="p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Materi</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola materi per kursus (bahasa + paket) dan atur level pembelajarannya.
                </p>
            </div>

            <button onclick="openAddModal()"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow text-sm font-semibold">
                + Tambah Materi
            </button>
        </div>

        {{-- TABLE LIST MATERI --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left w-1/5">Judul</th>
                        <th class="p-4 text-left w-1/5">Kursus</th>
                        <th class="p-4 text-center w-1/12">Level</th>
                        <th class="p-4 text-center w-1/12">Tipe</th>
                        <th class="p-4 text-left w-2/5">Konten</th>
                        <th class="p-4 text-center w-1/6">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materiList"></tbody>
            </table>
        </div>
    </div>

    {{-- ===========================
        MODAL TAMBAH MATERI
    ============================ --}}
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div class="bg-white w-11/12 md:w-2/3 lg:w-1/2 rounded-xl p-6 shadow-lg max-h-[90vh] overflow-y-auto">

            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Tambah Materi</h2>

            {{-- FILE VIDEO (opsional) --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Upload Video (opsional)</label>
                <input id="addVideoFile" type="file" accept="video/*" class="w-full border p-2 rounded-lg mt-1 text-sm">
                <p class="text-xs text-gray-500 mt-1">
                    Jika diisi, sistem akan menyimpan file ini sebagai video materi.
                </p>
            </div>

            {{-- PAKET --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Paket</label>
                <select id="addPaket" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50">
                    <option value="">-- Pilih Paket --</option>
                </select>
            </div>

            {{-- BAHASA --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Bahasa</label>
                <select id="addBahasa" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50">
                    <option value="">-- Pilih Bahasa --</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    Sistem akan otomatis membuat / mencari kursus dari kombinasi paket + bahasa ini.
                </p>
            </div>

            {{-- LEVEL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Level</label>
                <input id="addLevel" type="number" min="1" max="3"
                    class="w-full border p-2 rounded-lg mt-1 text-sm" placeholder="Contoh: 1" value="1">
                <p class="text-xs text-gray-500 mt-1">
                    Level 1 = materi awal, 2 = materi berikutnya, 3 = materi lanjutan.
                </p>
            </div>

            {{-- JUDUL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Judul Materi</label>
                <input id="addJudul" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="Contoh: Pengantar Grammar Dasar">
            </div>

            {{-- URL VIDEO (opsional) --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">URL Video (opsional)</label>
                <input id="addUrlVideo" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="https://... (YouTube / link video)">
                <p class="text-xs text-gray-500 mt-1">
                    Boleh diisi jika pakai YouTube / link lain. Jika hanya upload file, boleh dikosongkan.
                </p>
            </div>

            {{-- TEKS TEORI (opsional) --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Teks Teori (opsional)</label>
                <textarea id="addTeksTeori" rows="5" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="Isi materi teori di sini..."></textarea>
                <p class="text-xs text-gray-500 mt-1">
                    Isi jika materi ini punya teks teori. Kalau kosong, bagian teori akan dikosongkan.
                </p>
            </div>

            <div class="flex justify-end mt-6">
                <button onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded mr-2 text-sm font-semibold">
                    Batal
                </button>
                <button onclick="saveAdd()" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ===========================
        MODAL EDIT MATERI
    ============================ --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div class="bg-white w-11/12 md:w-2/3 lg:w-1/2 rounded-xl p-6 shadow-lg max-h-[90vh] overflow-y-auto">

            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Edit Materi</h2>

            <input type="hidden" id="editIndex">

            {{-- FILE VIDEO (opsional) --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Upload Video Baru (opsional)</label>
                <input id="editVideoFile" type="file" accept="video/*" class="w-full border p-2 rounded-lg mt-1 text-sm">
                <p class="text-xs text-gray-500 mt-1">
                    Jika diisi, akan mengganti file video yang sudah ada.
                </p>
                <p id="editVideoInfo" class="text-xs text-gray-500 mt-1"></p>
            </div>

            {{-- PAKET --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Paket</label>
                <select id="editPaket" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50">
                    <option value="">-- Pilih Paket --</option>
                </select>
            </div>

            {{-- BAHASA --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Bahasa</label>
                <select id="editBahasa" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50">
                    <option value="">-- Pilih Bahasa --</option>
                </select>
            </div>

            {{-- LEVEL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Level</label>
                <input id="editLevel" type="number" min="1" max="3"
                    class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            {{-- JUDUL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Judul Materi</label>
                <input id="editJudul" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            {{-- URL VIDEO (opsional) --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">URL Video (opsional)</label>
                <input id="editUrlVideo" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            {{-- TEKS TEORI (opsional) --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Teks Teori (opsional)</label>
                <textarea id="editTeksTeori" rows="5" class="w-full border p-2 rounded-lg mt-1 text-sm"></textarea>
            </div>

            <div class="flex justify-end mt-6">
                <button onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded mr-2 text-sm font-semibold">
                    Batal
                </button>
                <button onclick="saveEdit()" class="px-4 py-2 bg-yellow-500 text-white rounded text-sm font-semibold">
                    Update
                </button>
            </div>
        </div>
    </div>

    {{-- ===========================
        MODAL DELETE
    ============================ --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div class="bg-white w-96 rounded-xl p-6 shadow-lg text-center">
            <h2 class="text-xl font-semibold mb-2 text-gray-800">Hapus Materi Ini?</h2>
            <p class="text-sm text-gray-600 mb-4">
                Materi yang dihapus tidak bisa dikembalikan.
            </p>

            <input type="hidden" id="deleteIndex">

            <div class="flex justify-center mt-2">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded mr-2 text-sm font-semibold">
                    Batal
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded text-sm font-semibold">
                    Hapus
                </button>
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

        let materis = [];
        let pakets = [];
        let bahasas = [];

        // ===========================
        // Helper
        // ===========================
        function courseLabel(m) {
            const course = m.course;
            if (!course) return '-';
            const bahasa = course.bahasa ? course.bahasa.nama_bahasa : null;
            const paket = course.paket ? course.paket.nama_paket : null;
            if (bahasa && paket) return `${bahasa} - ${paket}`;
            return course.deskripsi || '-';
        }

        function previewText(text, limit = 100) {
            if (!text) return '-';
            if (text.length <= limit) return text;
            return text.slice(0, limit) + '...';
        }

        function fillPaketSelect(selectEl, selectedId = null) {
            selectEl.innerHTML = '<option value="">-- Pilih Paket --</option>';
            pakets.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.nama_paket || ('Paket #' + p.id);
                if (selectedId && String(selectedId) === String(p.id)) opt.selected = true;
                selectEl.appendChild(opt);
            });
        }

        function fillBahasaSelect(selectEl, selectedId = null) {
            selectEl.innerHTML = '<option value="">-- Pilih Bahasa --</option>';
            bahasas.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.id;
                opt.textContent = b.nama_bahasa || ('Bahasa #' + b.id);
                if (selectedId && String(selectedId) === String(b.id)) opt.selected = true;
                selectEl.appendChild(opt);
            });
        }

        // ===========================
        // Load data
        // ===========================
        async function loadPakets() {
            try {
                const res = await api.get('/paket');
                pakets = Array.isArray(res.data.data) ? res.data.data : res.data;
            } catch (e) {
                console.error(e);
                alert('Gagal memuat paket');
            }
        }

        async function loadBahasas() {
            try {
                const res = await api.get('/bahasa');
                bahasas = Array.isArray(res.data.data) ? res.data.data : res.data;
            } catch (e) {
                console.error(e);
                alert('Gagal memuat bahasa');
            }
        }

        async function loadMateri() {
            try {
                const res = await api.get('/admin/materi');
                materis = Array.isArray(res.data.data) ? res.data.data : res.data;
                renderMateri();
            } catch (e) {
                console.error(e);
                alert('Gagal memuat materi');
            }
        }

        function renderMateri() {
            const tbody = document.getElementById('materiList');
            tbody.innerHTML = '';

            if (!materis.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-400 text-sm">
                            Belum ada materi.
                        </td>
                    </tr>`;
                return;
            }

            const sorted = [...materis].sort((a, b) => {
                if (a.id_course === b.id_course) {
                    return (a.level || 1) - (b.level || 1);
                }
                return a.id_course - b.id_course;
            });

            sorted.forEach((m) => {
                let tipeBadge = '';
                switch (m.tipe) {
                    case 'video':
                        tipeBadge =
                            `<span class="inline-block px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700 font-semibold">Video</span>`;
                        break;
                    case 'teori':
                        tipeBadge =
                            `<span class="inline-block px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700 font-semibold">Teori</span>`;
                        break;
                    case 'campuran':
                        tipeBadge =
                            `<span class="inline-block px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-700 font-semibold">Campuran</span>`;
                        break;
                    default:
                        tipeBadge =
                            `<span class="inline-block px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-500 font-semibold">-</span>`;
                }

                const kontenParts = [];

                // File video upload
                if (m.video_url) {
                    kontenParts.push(`
                        <div class="flex items-center gap-2 text-xs text-emerald-700 mb-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-50">
                                📁 Video terupload
                            </span>
                        </div>
                    `);
                }

                // URL video (YouTube / dll)
                if (m.url_video) {
                    kontenParts.push(`
                        <a href="${m.url_video}" target="_blank"
                           class="text-blue-600 underline text-xs break-all">
                            ${m.url_video}
                        </a>
                    `);
                }

                // Teks teori
                if (m.teks_teori) {
                    kontenParts.push(`
                        <p class="text-xs text-gray-700 whitespace-pre-line">
                            ${previewText(m.teks_teori, 120)}
                        </p>
                    `);
                }

                const kontenPreview = kontenParts.length ?
                    kontenParts.join('<div class="h-2"></div>') :
                    '-';

                const originalIndex = materis.findIndex(x => x.id_materi === m.id_materi);

                tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold text-gray-800 align-top">
                            ${m.judul}
                        </td>
                        <td class="p-4 text-sm text-gray-700 align-top">
                            ${courseLabel(m)}
                        </td>
                        <td class="p-4 text-center align-top text-xs font-semibold">
                            Level ${m.level || 1}
                        </td>
                        <td class="p-4 text-center align-top">
                            ${tipeBadge}
                        </td>
                        <td class="p-4 align-top">
                            ${kontenPreview}
                        </td>
                        <td class="p-4 text-center align-top space-x-2">
                            <button onclick="openEditModal(${originalIndex})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-yellow-200 text-yellow-600 hover:bg-yellow-50"
                                title="Edit">✏️</button>
                            <button onclick="openDeleteModal(${originalIndex})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-red-200 text-red-600 hover:bg-red-50"
                                title="Hapus">🗑</button>
                        </td>
                    </tr>
                `;
            });
        }

        // ===========================
        // Modal Tambah
        // ===========================
        function openAddModal() {
            if (!pakets.length || !bahasas.length) {
                alert('Paket / bahasa belum dimuat.');
                return;
            }

            document.getElementById('addJudul').value = '';
            document.getElementById('addUrlVideo').value = '';
            document.getElementById('addTeksTeori').value = '';
            document.getElementById('addLevel').value = 1;
            document.getElementById('addVideoFile').value = '';

            fillPaketSelect(document.getElementById('addPaket'));
            fillBahasaSelect(document.getElementById('addBahasa'));

            const m = document.getElementById('addModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        async function saveAdd() {
            const id_paket = document.getElementById('addPaket').value;
            const id_bahasa = document.getElementById('addBahasa').value;
            let level = parseInt(document.getElementById('addLevel').value || '1', 10);
            const judul = document.getElementById('addJudul').value.trim();
            const url = document.getElementById('addUrlVideo').value.trim();
            const teks = document.getElementById('addTeksTeori').value.trim();
            const videoFile = document.getElementById('addVideoFile').files[0];

            if (!id_paket || !id_bahasa || !judul) {
                alert('Paket, bahasa, level, dan judul wajib diisi');
                return;
            }

            if (Number.isNaN(level)) level = 1;
            if (level < 1 || level > 3) {
                alert('Level harus antara 1 sampai 3');
                return;
            }

            if (!videoFile && !url && !teks) {
                alert('Isi minimal upload video, URL video, atau teks teori.');
                return;
            }

            const formData = new FormData();
            formData.append('id_paket', id_paket);
            formData.append('id_bahasa', id_bahasa);
            formData.append('level', level);
            formData.append('judul', judul);
            if (url) formData.append('url_video', url);
            if (teks) formData.append('teks_teori', teks);
            if (videoFile) formData.append('video_file', videoFile);

            try {
                await api.post('/admin/materi', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                closeAddModal();
                await loadMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menambah materi');
            }
        }

        // ===========================
        // Modal Edit
        // ===========================
        function openEditModal(index) {
            const m = materis[index];
            if (!m) return;

            document.getElementById('editIndex').value = index;

            const paketId = m.course && m.course.paket ? m.course.paket.id : null;
            const bahasaId = m.course && m.course.bahasa ? m.course.bahasa.id : null;

            fillPaketSelect(document.getElementById('editPaket'), paketId);
            fillBahasaSelect(document.getElementById('editBahasa'), bahasaId);

            document.getElementById('editLevel').value = m.level || 1;
            document.getElementById('editJudul').value = m.judul;
            document.getElementById('editUrlVideo').value = m.url_video || '';
            document.getElementById('editTeksTeori').value = m.teks_teori || '';
            document.getElementById('editVideoFile').value = '';

            let info = '';
            if (m.video_url) {
                info += 'Saat ini sudah ada file video terupload.';
            }
            if (m.url_video) {
                info += (info ? ' ' : '') + 'Link video sekarang: ' + m.url_video;
            }
            document.getElementById('editVideoInfo').textContent = info;

            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        async function saveEdit() {
            const index = document.getElementById('editIndex').value;
            const m = materis[index];
            if (!m) return;

            const id_paket = document.getElementById('editPaket').value;
            const id_bahasa = document.getElementById('editBahasa').value;
            let level = parseInt(document.getElementById('editLevel').value || '1', 10);
            const judul = document.getElementById('editJudul').value.trim();
            const url = document.getElementById('editUrlVideo').value.trim();
            const teks = document.getElementById('editTeksTeori').value.trim();
            const videoFile = document.getElementById('editVideoFile').files[0];

            if (!id_paket || !id_bahasa || !judul) {
                alert('Paket, bahasa, level, dan judul wajib diisi');
                return;
            }

            if (Number.isNaN(level)) level = 1;
            if (level < 1 || level > 3) {
                alert('Level harus antara 1 sampai 3');
                return;
            }

            if (!videoFile && !url && !teks && !m.video_url && !m.url_video && !m.teks_teori) {
                alert('Isi minimal upload video, URL video, atau teks teori.');
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('id_paket', id_paket);
            formData.append('id_bahasa', id_bahasa);
            formData.append('level', level);
            formData.append('judul', judul);
            formData.append('url_video', url); // boleh kosong string
            formData.append('teks_teori', teks); // boleh kosong string
            if (videoFile) formData.append('video_file', videoFile);

            try {
                await api.post(`/admin/materi/${m.id_materi}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                closeEditModal();
                await loadMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal mengupdate materi');
            }
        }

        // ===========================
        // Modal Delete
        // ===========================
        function openDeleteModal(index) {
            document.getElementById('deleteIndex').value = index;
            const m = document.getElementById('deleteModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        async function confirmDelete() {
            const index = document.getElementById('deleteIndex').value;
            const m = materis[index];
            if (!m) {
                closeDeleteModal();
                return;
            }

            if (!confirm(`Yakin ingin menghapus materi: "${m.judul}"?`)) {
                return;
            }

            try {
                await api.delete(`/admin/materi/${m.id_materi}`);
                closeDeleteModal();
                await loadMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus materi');
            }
        }

        // ===========================
        // Init
        // ===========================
        document.addEventListener('DOMContentLoaded', async () => {
            await loadPakets();
            await loadBahasas();
            await loadMateri();
        });
    </script>
@endpush
