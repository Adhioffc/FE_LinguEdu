@extends('layouts.admin')
@section('title', 'Manajemen Paket')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manajemen Paket Belajar</h1>

        <button onclick="openAddModal()" 
            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow">
            + Tambah Paket
        </button>
    </div>

    <!-- Container Data -->
    <div id="paketContainer" class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Nama Paket</th>
                    <th class="p-4 text-left">Deskripsi</th>
                    <th class="p-4 text-center">Harga</th>
                    <th class="p-4 text-center">Bahasa</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="paketList"></tbody>
        </table>
    </div>
</div>

<!-- =======================
    MODAL TAMBAH
======================= -->
<div id="addModal"
    class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

    <div class="bg-white w-11/12 md:w-1/2 rounded-xl p-6 shadow-lg">
        <h2 class="text-2xl font-semibold mb-4">Tambah Paket</h2>

        <div class="mb-4">
            <label class="font-semibold">Nama Paket</label>
            <input id="addNama" type="text" class="w-full border p-2 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Harga</label>
            <input id="addHarga" type="number" class="w-full border p-2 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Fitur Paket (pisahkan dengan enter)</label>
            <textarea id="addFitur" class="w-full border p-2 rounded-lg"></textarea>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Bahasa Tersedia</label>

            <div class="grid grid-cols-3 gap-3 mt-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" value="Inggris" class="add-bahasa">
                    <span>Inggris</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" value="Jepang" class="add-bahasa">
                    <span>Jepang</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" value="Korea" class="add-bahasa">
                    <span>Korea</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>

            <button onclick="saveAdd()" 
                class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </div>
</div>

<!-- =======================
    MODAL EDIT
======================= -->
<div id="editModal"
    class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

    <div class="bg-white w-11/12 md:w-1/2 rounded-xl p-6 shadow-lg">

        <h2 class="text-2xl font-semibold mb-4">Edit Paket</h2>

        <input type="hidden" id="editIndex">

        <div class="mb-4">
            <label class="font-semibold">Nama Paket</label>
            <input id="editNama" type="text" class="w-full border p-2 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Harga</label>
            <input id="editHarga" type="number" class="w-full border p-2 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Fitur Paket (enter per baris)</label>
            <textarea id="editFitur" class="w-full border p-2 rounded-lg"></textarea>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Bahasa Tersedia</label>

            <div class="grid grid-cols-3 gap-3 mt-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" value="Inggris" class="edit-bahasa">
                    <span>Inggris</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" value="Jepang" class="edit-bahasa">
                    <span>Jepang</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" value="Korea" class="edit-bahasa">
                    <span>Korea</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>

            <button onclick="saveEdit()" 
                class="px-4 py-2 bg-yellow-500 text-white rounded">Update</button>
        </div>
    </div>
</div>

<!-- =======================
    MODAL DELETE
======================= -->
<div id="deleteModal"
    class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

    <div class="bg-white w-96 rounded-xl p-6 shadow-lg text-center">

        <h2 class="text-xl font-semibold">Hapus Paket Ini?</h2>

        <input type="hidden" id="deleteIndex">

        <div class="flex justify-center mt-4">
            <button onclick="closeDeleteModal()" 
                class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>

            <button onclick="confirmDelete()" 
                class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==============================
    //  DATA TANPA DATABASE
    // ==============================
    let pakets = [
        {
            nama: "Basic",
            harga: 150000,
            fitur: ["Akses 1 Bulan", "Materi Dasar"],
            bahasa: ["Inggris"]
        },
        {
            nama: "Intermediate",
            harga: 250000,
            fitur: ["Akses 2 Bulan", "Materi Menengah", "Quiz"],
            bahasa: ["Inggris", "Jepang"]
        }
    ];

    // ==============================
    //  TAMPILKAN DATA
    // ==============================
    function renderTable() {
        const list = document.getElementById('paketList');
        list.innerHTML = "";

        pakets.forEach((p, i) => {
            list.innerHTML += `
                <tr class="border-b">
                    <td class="p-4 font-semibold">${p.nama}</td>

                    <td class="p-4">
                        <ul class="list-disc ml-4 text-gray-600">
                            ${p.fitur.map(f => `<li>${f}</li>`).join("")}
                        </ul>
                    </td>

                    <td class="p-4 text-center font-bold">Rp${p.harga.toLocaleString()}</td>

                    <td class="p-4 text-center">
                        ${p.bahasa.map(b => `
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs mr-1">
                                ${b}
                            </span>
                        `).join("")}
                    </td>

                    <td class="p-4 text-center">
                        <button onclick="openEditModal(${i})"
                            class="bg-yellow-400 px-3 py-1 rounded-lg mr-2">Edit</button>

                        <button onclick="openDeleteModal(${i})"
                            class="bg-red-500 text-white px-3 py-1 rounded-lg">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    renderTable();


    // ==============================
    //  ADD
    // ==============================
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function saveAdd() {
        let nama = document.getElementById('addNama').value;
        let harga = document.getElementById('addHarga').value;
        let fitur = document.getElementById('addFitur').value.split("\n");
        let bahasa = Array.from(document.querySelectorAll('.add-bahasa:checked')).map(cb => cb.value);

        pakets.push({ nama, harga, fitur, bahasa });

        renderTable();
        closeAddModal();
    }

    // ==============================
    //  EDIT
    // ==============================
    function openEditModal(index) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');

        document.getElementById('editIndex').value = index;

        let p = pakets[index];

        document.getElementById('editNama').value = p.nama;
        document.getElementById('editHarga').value = p.harga;
        document.getElementById('editFitur').value = p.fitur.join("\n");

        document.querySelectorAll('.edit-bahasa').forEach(cb => {
            cb.checked = p.bahasa.includes(cb.value);
        });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function saveEdit() {
        let index = document.getElementById('editIndex').value;

        pakets[index].nama = document.getElementById('editNama').value;
        pakets[index].harga = document.getElementById('editHarga').value;
        pakets[index].fitur = document.getElementById('editFitur').value.split("\n");
        pakets[index].bahasa = Array.from(document.querySelectorAll('.edit-bahasa:checked')).map(cb => cb.value);

        renderTable();
        closeEditModal();
    }

    // ==============================
    //  DELETE
    // ==============================
    function openDeleteModal(i) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
        document.getElementById('deleteIndex').value = i;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function confirmDelete() {
        let index = document.getElementById('deleteIndex').value;
        pakets.splice(index, 1);
        renderTable();
        closeDeleteModal();
    }
</script>
@endpush