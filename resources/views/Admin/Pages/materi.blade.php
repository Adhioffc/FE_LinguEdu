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
                        <th class="p-4 text-left w-2/5">Konten / URL</th>
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

            {{-- KURSUS --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Kursus (Bahasa + Paket)</label>
                <select id="addCourse" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50">
                    <option value="">-- Pilih Kursus --</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    Kursus adalah kombinasi bahasa + paket. Buat dulu kursusnya, baru bisa tambah materi.
                </p>
            </div>

            {{-- LEVEL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Level</label>
                <input id="addLevel" type="number" min="1" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="Contoh: 1" value="1">
                <p class="text-xs text-gray-500 mt-1">
                    Level 1 = materi awal, 2 = materi berikutnya, dan seterusnya.
                </p>
            </div>

            {{-- JUDUL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Judul Materi</label>
                <input id="addJudul" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="Contoh: Pengantar Grammar Dasar">
            </div>

            {{-- TIPE --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Tipe Materi</label>
                <select id="addTipe" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50"
                    onchange="toggleAddTipe()">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="video">Video</option>
                    <option value="teori">Teori (Teks)</option>
                </select>
            </div>

            {{-- VIDEO GROUP --}}
            <div class="mb-4 hidden" id="addVideoGroup">
                <label class="font-semibold text-sm">URL Video</label>
                <input id="addUrlVideo" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="https://... (YouTube / link video)">
                <p class="text-xs text-gray-500 mt-1">
                    Masukkan URL video (YouTube, Vimeo, atau link video lain).
                </p>
            </div>

            {{-- TEORI GROUP --}}
            <div class="mb-4 hidden" id="addTeoriGroup">
                <label class="font-semibold text-sm">Teks Teori</label>
                <textarea id="addTeksTeori" rows="5" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="Isi materi teori di sini..."></textarea>
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

            {{-- KURSUS --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Kursus (Bahasa + Paket)</label>
                <select id="editCourse" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50">
                    <option value="">-- Pilih Kursus --</option>
                </select>
            </div>

            {{-- LEVEL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Level</label>
                <input id="editLevel" type="number" min="1" class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            {{-- JUDUL --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Judul Materi</label>
                <input id="editJudul" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            {{-- TIPE --}}
            <div class="mb-4">
                <label class="font-semibold text-sm">Tipe Materi</label>
                <select id="editTipe" class="w-full border p-2 rounded-lg mt-1 text-sm bg-gray-50"
                    onchange="toggleEditTipe()">
                    <option value="video">Video</option>
                    <option value="teori">Teori (Teks)</option>
                </select>
            </div>

            {{-- VIDEO GROUP --}}
            <div class="mb-4" id="editVideoGroup">
                <label class="font-semibold text-sm">URL Video</label>
                <input id="editUrlVideo" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            {{-- TEORI GROUP --}}
            <div class="mb-4" id="editTeoriGroup">
                <label class="font-semibold text-sm">Teks Teori</label>
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
        let courses = [];

        // ===========================
        //  HELPER
        // ===========================
        function courseLabel(course) {
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

        function fillCourseSelect(selectEl, selectedId = null) {
            selectEl.innerHTML = '<option value="">-- Pilih Kursus --</option>';
            courses.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id_course;
                opt.textContent = courseLabel(c);
                if (selectedId && String(selectedId) === String(c.id_course)) {
                    opt.selected = true;
                }
                selectEl.appendChild(opt);
            });
        }

        // ===========================
        //  LOAD DATA
        // ===========================
        async function loadCourses() {
            try {
                const res = await api.get('/admin/kursus');
                courses = Array.isArray(res.data.data) ? res.data.data : res.data;
            } catch (e) {
                console.error(e);
                alert('Gagal memuat daftar kursus');
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

            // Optional: sort by kursus, lalu level
            const sorted = [...materis].sort((a, b) => {
                if (a.id_course === b.id_course) {
                    return (a.level ?? 1) - (b.level ?? 1);
                }
                return a.id_course - b.id_course;
            });

            sorted.forEach((m, i) => {
                const tipeBadge = m.tipe === 'video' ?
                    `<span class="inline-block px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700 font-semibold">Video</span>` :
                    `<span class="inline-block px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700 font-semibold">Teori</span>`;

                let kontenPreview = '-';
                if (m.tipe === 'video' && m.url_video) {
                    kontenPreview = `<a href="${m.url_video}" target="_blank"
                        class="text-blue-600 underline text-xs break-all">
                        ${m.url_video}
                    </a>`;
                } else if (m.tipe === 'teori' && m.teks_teori) {
                    kontenPreview = `<p class="text-xs text-gray-700 whitespace-pre-line">
                        ${previewText(m.teks_teori, 120)}
                    </p>`;
                }

                // Cari index asli di array materis (karena kita pakai sorted)
                const originalIndex = materis.findIndex(x => x.id_materi === m.id_materi);

                tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold text-gray-800 align-top">
                            ${m.judul}
                        </td>
                        <td class="p-4 text-sm text-gray-700 align-top">
                            ${courseLabel(m.course)}
                        </td>
                        <td class="p-4 text-center align-top text-xs font-semibold">
                            Level ${m.level ?? 1}
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
        //  MODAL TAMBAH
        // ===========================
        function openAddModal() {
            if (!courses.length) {
                alert('Belum ada kursus. Silakan buat kursus (bahasa + paket) dulu.');
                return;
            }

            document.getElementById('addJudul').value = '';
            document.getElementById('addTipe').value = '';
            document.getElementById('addUrlVideo').value = '';
            document.getElementById('addTeksTeori').value = '';
            document.getElementById('addLevel').value = 1;

            fillCourseSelect(document.getElementById('addCourse'));

            document.getElementById('addVideoGroup').classList.add('hidden');
            document.getElementById('addTeoriGroup').classList.add('hidden');

            const m = document.getElementById('addModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function toggleAddTipe() {
            const tipe = document.getElementById('addTipe').value;
            document.getElementById('addVideoGroup').classList.toggle('hidden', tipe !== 'video');
            document.getElementById('addTeoriGroup').classList.toggle('hidden', tipe !== 'teori');
        }

        async function saveAdd() {
            const id_course = document.getElementById('addCourse').value;
            const level = parseInt(document.getElementById('addLevel').value || '1', 10);
            const judul = document.getElementById('addJudul').value.trim();
            const tipe = document.getElementById('addTipe').value;
            const url = document.getElementById('addUrlVideo').value.trim();
            const teks = document.getElementById('addTeksTeori').value.trim();

            if (!id_course || !judul || !tipe) {
                alert('Kursus, level, judul, dan tipe wajib diisi');
                return;
            }

            if (tipe === 'video' && !url) {
                alert('URL video wajib diisi untuk tipe video');
                return;
            }
            if (tipe === 'teori' && !teks) {
                alert('Teks teori wajib diisi untuk tipe teori');
                return;
            }

            const payload = {
                id_course: id_course,
                level: level,
                judul: judul,
                tipe: tipe,
                url_video: tipe === 'video' ? url : null,
                teks_teori: tipe === 'teori' ? teks : null,
            };

            try {
                await api.post('/admin/materi', payload);
                closeAddModal();
                await loadMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menambah materi');
            }
        }

        // ===========================
        //  MODAL EDIT
        // ===========================
        function openEditModal(index) {
            const m = materis[index];
            if (!m) return;

            document.getElementById('editIndex').value = index;

            fillCourseSelect(document.getElementById('editCourse'), m.id_course);

            document.getElementById('editLevel').value = m.level ?? 1;
            document.getElementById('editJudul').value = m.judul;
            document.getElementById('editTipe').value = m.tipe;
            document.getElementById('editUrlVideo').value = m.url_video || '';
            document.getElementById('editTeksTeori').value = m.teks_teori || '';

            toggleEditTipe();

            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleEditTipe() {
            const tipe = document.getElementById('editTipe').value;
            document.getElementById('editVideoGroup').classList.toggle('hidden', tipe !== 'video');
            document.getElementById('editTeoriGroup').classList.toggle('hidden', tipe !== 'teori');
        }

        async function saveEdit() {
            const index = document.getElementById('editIndex').value;
            const m = materis[index];
            if (!m) return;

            const id_course = document.getElementById('editCourse').value;
            const level = parseInt(document.getElementById('editLevel').value || '1', 10);
            const judul = document.getElementById('editJudul').value.trim();
            const tipe = document.getElementById('editTipe').value;
            const url = document.getElementById('editUrlVideo').value.trim();
            const teks = document.getElementById('editTeksTeori').value.trim();

            if (!id_course || !judul || !tipe) {
                alert('Kursus, level, judul, dan tipe wajib diisi');
                return;
            }

            if (tipe === 'video' && !url) {
                alert('URL video wajib diisi untuk tipe video');
                return;
            }
            if (tipe === 'teori' && !teks) {
                alert('Teks teori wajib diisi untuk tipe teori');
                return;
            }

            const payload = {
                id_course: id_course,
                level: level,
                judul: judul,
                tipe: tipe,
                url_video: tipe === 'video' ? url : null,
                teks_teori: tipe === 'teori' ? teks : null,
            };

            try {
                await api.put(`/admin/materi/${m.id_materi}`, payload);
                closeEditModal();
                await loadMateri();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal mengupdate materi');
            }
        }

        // ===========================
        //  MODAL DELETE
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
        //  INIT
        // ===========================
        document.addEventListener('DOMContentLoaded', async () => {
            await loadCourses();
            await loadMateri();
        });
    </script>
@endpush
