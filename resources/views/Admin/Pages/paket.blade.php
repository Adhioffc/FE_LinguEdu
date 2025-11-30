@extends('layouts.admin')
@section('title', 'Manajemen Paket')

@section('content')
    <div class="p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Paket Belajar</h1>

            <button onclick="openAddModal()"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow">
                + Tambah Paket
            </button>
        </div>

        {{-- TABEL DATA --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left w-1/5">Nama Paket</th>
                        <th class="p-4 text-left w-2/5">Fitur / Desc</th>
                        <th class="p-4 text-center w-1/5">Harga</th>
                        <th class="p-4 text-center w-1/5">Aksi</th>
                    </tr>
                </thead>

                <tbody id="paketList">
                    {{-- diisi via JS --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===================== MODAL TAMBAH ===================== --}}
    <div id="addModal"
        class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

        <div class="bg-white w-11/12 md:w-1/2 rounded-xl p-6 shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Tambah Paket</h2>

            <div class="mb-4">
                <label class="font-semibold text-sm">Nama Paket</label>
                <input id="addNama" type="text" class="w-full border p-2 rounded-lg mt-1" placeholder="Contoh: Basic">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm">Harga (Rp)</label>
                <input id="addHarga" type="number" class="w-full border p-2 rounded-lg mt-1" min="0">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm">Fitur Paket (1 baris = 1 poin)</label>
                <textarea id="addFitur" class="w-full border p-2 rounded-lg mt-1" rows="4"
                    placeholder="Akses 1 bulan&#10;Materi dasar&#10;Grup diskusi"></textarea>
            </div>

            <div class="flex justify-end mt-6">
                <button onclick="closeAddModal()"
                    class="px-4 py-2 bg-gray-300 rounded mr-2 text-sm font-semibold">Batal</button>

                <button onclick="saveAdd()"
                    class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL EDIT ===================== --}}
    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

        <div class="bg-white w-11/12 md:w-1/2 rounded-xl p-6 shadow-lg">

            <h2 class="text-2xl font-semibold mb-4">Edit Paket</h2>

            <input type="hidden" id="editIndex">

            <div class="mb-4">
                <label class="font-semibold text-sm">Nama Paket</label>
                <input id="editNama" type="text" class="w-full border p-2 rounded-lg mt-1">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm">Harga (Rp)</label>
                <input id="editHarga" type="number" class="w-full border p-2 rounded-lg mt-1" min="0">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm">Fitur Paket (1 baris = 1 poin)</label>
                <textarea id="editFitur" class="w-full border p-2 rounded-lg mt-1" rows="4"></textarea>
            </div>

            <div class="flex justify-end mt-6">
                <button onclick="closeEditModal()"
                    class="px-4 py-2 bg-gray-300 rounded mr-2 text-sm font-semibold">Batal</button>

                <button onclick="saveEdit()"
                    class="px-4 py-2 bg-yellow-500 text-white rounded text-sm font-semibold">Update</button>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL DELETE ===================== --}}
    <div id="deleteModal"
        class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

        <div class="bg-white w-96 rounded-xl p-6 shadow-lg text-center">

            <h2 class="text-xl font-semibold mb-2">Hapus Paket Ini?</h2>
            <p class="text-sm text-gray-600 mb-4">Data tidak bisa dikembalikan setelah dihapus.</p>

            <input type="hidden" id="deleteIndex">

            <div class="flex justify-center mt-2">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 rounded mr-2 text-sm font-semibold">Batal</button>

                <button onclick="confirmDelete()"
                    class="px-4 py-2 bg-red-600 text-white rounded text-sm font-semibold">Hapus</button>
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
                Accept: 'application/json',
            },
        });

        let pakets = []; // diisi dari API

        // =========================
        //  LOAD & RENDER TABEL
        // =========================
        async function loadPaket() {
            try {
                const res = await api.get('/admin/paket');
                pakets = Array.isArray(res.data.data) ? res.data.data : res.data;
                renderTable();
            } catch (error) {
                console.error(error);
                alert('Gagal memuat data paket');
            }
        }

        function renderTable() {
            const list = document.getElementById('paketList');
            list.innerHTML = '';

            if (!pakets.length) {
                list.innerHTML = `
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400 text-sm">
                            Belum ada data paket.
                        </td>
                    </tr>`;
                return;
            }

            pakets.forEach((p, i) => {
                const fitur = (p.desc || '').split('\n').filter(f => f.trim() !== '');

                list.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold text-gray-800">${p.nama_paket}</td>

                        <td class="p-4 align-top">
                            ${fitur.length
                                ? `<ul class="list-disc ml-4 text-gray-600 text-sm">
                                        ${fitur.map(f => `<li>${f}</li>`).join('')}
                                   </ul>`
                                : '<span class="text-xs text-gray-400">Tidak ada Desc</span>'}
                        </td>

                        <td class="p-4 text-center font-bold text-sm">
                            Rp ${Number(p.harga).toLocaleString('id-ID')}
                        </td>

                        <td class="p-4 text-center space-x-2">
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

        // =========================
        //  MODAL ADD
        // =========================
        function openAddModal() {
            document.getElementById('addNama').value = '';
            document.getElementById('addHarga').value = '';
            document.getElementById('addFitur').value = '';

            const m = document.getElementById('addModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        async function saveAdd() {
            const nama = document.getElementById('addNama').value.trim();
            const harga = document.getElementById('addHarga').value;
            const fiturText = document.getElementById('addFitur').value;

            if (!nama || !harga) {
                alert('Nama paket dan harga wajib diisi');
                return;
            }

            try {
                await api.post('/admin/paket', {
                    nama_paket: nama,
                    harga: harga,
                    desc: fiturText,
                });

                closeAddModal();
                await loadPaket();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal menyimpan paket');
            }
        }

        // =========================
        //  MODAL EDIT
        // =========================
        function openEditModal(index) {
            const p = pakets[index];

            document.getElementById('editIndex').value = index;
            document.getElementById('editNama').value = p.nama_paket;
            document.getElementById('editHarga').value = p.harga;
            document.getElementById('editFitur').value = p.desc || '';

            const m = document.getElementById('editModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        async function saveEdit() {
            const index = document.getElementById('editIndex').value;
            const paket = pakets[index];

            const nama = document.getElementById('editNama').value.trim();
            const harga = document.getElementById('editHarga').value;
            const fiturText = document.getElementById('editFitur').value;

            if (!nama || !harga) {
                alert('Nama paket dan harga wajib diisi');
                return;
            }

            try {
                await api.put(`/admin/paket/${paket.id}`, {
                    nama_paket: nama,
                    harga: harga,
                    desc: fiturText,
                });

                closeEditModal();
                await loadPaket();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal mengupdate paket');
            }
        }

        // =========================
        //  MODAL DELETE
        // =========================
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
            const paket = pakets[index];

            if (!paket) {
                closeDeleteModal();
                return;
            }

            try {
                await api.delete(`/admin/paket/${paket.id}`);
                closeDeleteModal();
                await loadPaket();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal menghapus paket');
            }
        }

        // =========================
        //  INIT
        // =========================
        document.addEventListener('DOMContentLoaded', loadPaket);
    </script>
@endpush
