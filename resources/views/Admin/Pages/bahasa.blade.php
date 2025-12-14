@extends('layouts.admin')
@section('title', 'Manajemen Bahasa')

@section('content')
    <div class="p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Bahasa</h1>

            <button onclick="openAddModal()"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow text-sm font-semibold">
                + Tambah Bahasa
            </button>
        </div>



        {{-- TABEL --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left w-1/4">Nama Bahasa</th>
                        <th class="p-4 text-left w-2/4">Deskripsi</th>
                        <th class="p-4 text-center w-1/4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bahasaList"></tbody>
            </table>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH ================= --}}
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div class="bg-white w-11/12 md:w-1/2 rounded-xl p-6 shadow-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Tambah Bahasa</h2>

            <div class="mb-4">
                <label class="font-semibold text-sm text-gray-700">Nama Bahasa</label>
                <input id="addNama" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm"
                    placeholder="Contoh: Inggris">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm text-gray-700">Deskripsi</label>
                <textarea id="addDesc" class="w-full border p-2 rounded-lg mt-1 text-sm" rows="3"
                    placeholder="Contoh: Bahasa Inggris untuk kebutuhan umum, bisnis, dst."></textarea>
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

    {{-- ================= MODAL EDIT ================= --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div class="bg-white w-11/12 md:w-1/2 rounded-xl p-6 shadow-lg">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Edit Bahasa</h2>

            <input type="hidden" id="editIndex">

            <div class="mb-4">
                <label class="font-semibold text-sm text-gray-700">Nama Bahasa</label>
                <input id="editNama" type="text" class="w-full border p-2 rounded-lg mt-1 text-sm">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm text-gray-700">Deskripsi</label>
                <textarea id="editDesc" class="w-full border p-2 rounded-lg mt-1 text-sm" rows="3"></textarea>
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

    {{-- ================= MODAL DELETE ================= --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div class="bg-white w-96 rounded-xl p-6 shadow-lg text-center">
            <h2 class="text-xl font-semibold mb-2 text-gray-800">Hapus Bahasa Ini?</h2>
            <p class="text-sm text-gray-600 mb-4">
                Jika bahasa masih dipakai di kursus, penghapusan bisa gagal.
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

        let bahasas = [];

        // ================= LOAD DATA =================
        async function loadBahasa() {
            try {
                const res = await api.get('/admin/bahasa');
                // backend kita kirim { data: [...] }
                bahasas = Array.isArray(res.data.data) ? res.data.data : res.data;
                renderBahasa();
            } catch (e) {
                console.error(e);
                alert('Gagal memuat bahasa');
            }
        }

        function truncate(text, length = 80) {
            if (!text) return '-';
            return text.length > length ? text.substring(0, length) + '…' : text;
        }

        function renderBahasa() {
            const list = document.getElementById('bahasaList');
            list.innerHTML = '';

            if (!bahasas.length) {
                list.innerHTML = `
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-400 text-sm">
                            Belum ada data bahasa.
                        </td>
                    </tr>`;
                return;
            }

            bahasas.forEach((b, i) => {
                list.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold text-gray-800 align-top">
                            ${b.nama_bahasa}
                        </td>
                        <td class="p-4 text-sm text-gray-600 align-top">
                            ${truncate(b.desc ?? '')}
                        </td>
                        <td class="p-4 text-center space-x-2 align-top">
                            <button onclick="openEditModal(${i})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-yellow-200 text-yellow-600 hover:bg-yellow-50"
                                title="Edit">
                                ✏️
                            </button>
                            <button onclick="openDeleteModal(${i})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-red-200 text-red-600 hover:bg-red-50"
                                title="Hapus">
                                🗑
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        // ================= ADD =================
        function openAddModal() {
            document.getElementById('addNama').value = '';
            document.getElementById('addDesc').value = '';
            const m = document.getElementById('addModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        async function saveAdd() {
            const nama = document.getElementById('addNama').value.trim();
            const desc = document.getElementById('addDesc').value.trim();

            if (!nama) {
                alert('Nama bahasa wajib diisi');
                return;
            }

            try {
                await api.post('/admin/bahasa', {
                    nama_bahasa: nama,
                    desc: desc || null,
                });
                closeAddModal();
                await loadBahasa();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menambah bahasa');
            }
        }

        // ================= EDIT =================
        function openEditModal(index) {
            const b = bahasas[index];
            if (!b) return;

            document.getElementById('editIndex').value = index;
            document.getElementById('editNama').value = b.nama_bahasa || '';
            document.getElementById('editDesc').value = b.desc || '';

            const m = document.getElementById('editModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        async function saveEdit() {
            const index = document.getElementById('editIndex').value;
            const b = bahasas[index];
            if (!b) return;

            const nama = document.getElementById('editNama').value.trim();
            const desc = document.getElementById('editDesc').value.trim();

            if (!nama) {
                alert('Nama bahasa wajib diisi');
                return;
            }

            try {
                await api.put(`/admin/bahasa/${b.id}`, {
                    nama_bahasa: nama,
                    desc: desc || null,
                });
                closeEditModal();
                await loadBahasa();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal mengupdate bahasa');
            }
        }

        // ================= DELETE =================
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
            const b = bahasas[index];
            if (!b) {
                closeDeleteModal();
                return;
            }

            try {
                await api.delete(`/admin/bahasa/${b.id}`);
                closeDeleteModal();
                await loadBahasa();
            } catch (e) {
                console.error(e);
                alert(e.response?.data?.message || 'Gagal menghapus bahasa (mungkin masih dipakai di kursus)');
            }
        }

        document.addEventListener('DOMContentLoaded', loadBahasa);
    </script>
@endpush
