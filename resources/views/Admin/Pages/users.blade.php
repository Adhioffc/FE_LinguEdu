@extends('layouts.admin')

@section('content')
    {{-- CSS DataTables (kalau mau rapi, pindah ke <head> dan/atau pakai @push('styles') --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
        </div>

        {{-- Menu Navigasi Tab --}}
        <div class="flex space-x-4 mb-6">
            <button onclick="showPage('list')" class="tab-btn bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">
                Daftar User
            </button>

            <button onclick="showPage('create')" class="tab-btn bg-gray-300 px-4 py-2 rounded-lg font-semibold">
                Tambah User
            </button>
        </div>

        {{-- ========================== --}}
        {{-- PAGE: LIST USER --}}
        {{-- ========================== --}}
        <div id="page-list" class="page-section">

            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar User</h2>

                <div class="overflow-x-auto">
                    <table id="usersTable" class="min-w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-3 border">Nama</th>
                                <th class="py-2 px-3 border">Email</th>
                                <th class="py-2 px-3 border">Role</th>
                                <th class="py-2 px-3 border">Status</th>
                                <th class="py-2 px-3 border text-center">Bukti TF</th>

                                {{-- 4 kolom aksi terpisah --}}
                                <th class="py-2 px-3 border text-center">Detail</th>
                                <th class="py-2 px-3 border text-center">Edit</th>
                                <th class="py-2 px-3 border text-center">Hapus</th>
                                <th class="py-2 px-3 border text-center">Aktif/Nonaktif</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                @php
                                    $isActive = !empty($user['email_verified_at'] ?? null);

                                    // relasi dari API bisa muncul sebagai 'latestRegistrasi' atau 'latest_registrasi'
                                    $latest = $user['latest_registrasi'] ?? ($user['latestRegistrasi'] ?? null);
                                    $buktiPath = $latest['bukti_byr'] ?? null; // contoh: "foto_bukti/xxx.png"

                                    // kalau ada bukti → ambil dari backend: http://127.0.0.1:8000/storage/foto_bukti/xxx.png
                                    // kalau tidak ada → null (nanti ditampilkan teks "Tidak ada")
                                    $buktiUrl = $buktiPath
                                        ? 'http://127.0.0.1:8000/storage/' . ltrim($buktiPath, '/')
                                        : null;
                                @endphp

                                <tr>
                                    <td class="py-2 px-3 border">{{ $user['name'] }}</td>
                                    <td class="py-2 px-3 border">{{ $user['email'] }}</td>
                                    <td class="py-2 px-3 border">{{ $user['role'] ?? '-' }}</td>

                                    <td class="py-2 px-3 border">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $isActive ? 'Aktif' : 'Belum Aktif' }}
                                        </span>
                                    </td>

                                    {{-- BUKTI TRANSFER (ambil dari tabel registrasi_kursus lewat relasi latestRegistrasi) --}}
                                    <td class="py-2 px-3 border text-center">
                                        @if ($buktiUrl)
                                            <img src="{{ $buktiUrl }}" alt="Bukti Transfer"
                                                class="w-16 h-16 object-cover rounded-md mx-auto cursor-pointer"
                                                onclick="window.open('{{ $buktiUrl }}', '_blank')">
                                        @else
                                            <span class="text-xs text-gray-400">Tidak ada</span>
                                        @endif
                                    </td>

                                    {{-- DETAIL --}}
                                    <td class="py-2 px-3 border text-center">
                                        <button type="button" title="Detail"
                                            onclick="showUserDetail(
                        {{ json_encode($user['name']) }},
                        {{ json_encode($user['email']) }},
                        {{ json_encode($user['role'] ?? '-') }},
                        {{ json_encode($isActive ? 'Aktif' : 'Belum Aktif') }}
                    )"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-blue-200 text-blue-600 hover:bg-blue-50">
                                            👁
                                        </button>
                                    </td>

                                    {{-- EDIT --}}
                                    <td class="py-2 px-3 border text-center">
                                        <button type="button" title="Edit"
                                            onclick="showEditForm(
                        {{ $user['id'] }},
                        {{ json_encode($user['name']) }},
                        {{ json_encode($user['email']) }},
                        {{ json_encode($user['role'] ?? 'member') }},
                        {{ $isActive ? 'true' : 'false' }}
                    )"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-yellow-200 text-yellow-600 hover:bg-yellow-50">
                                            ✏️
                                        </button>
                                    </td>

                                    {{-- HAPUS --}}
                                    <td class="py-2 px-3 border text-center">
                                        <button type="button" title="Hapus"
                                            onclick="showDelete({{ $user['id'] }}, {{ json_encode($user['name']) }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-red-200 text-red-600 hover:bg-red-50">
                                            🗑
                                        </button>
                                    </td>

                                    {{-- AKTIF / NONAKTIF --}}
                                    <td class="py-2 px-3 border text-center">
                                        @if (($user['role'] ?? 'member') === 'member')
                                            <button type="button" title="{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="toggleActive({{ $user['id'] }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-green-200 text-green-600 hover:bg-green-50">
                                                {{ $isActive ? '⛔' : '✓' }}
                                            </button>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>

        {{-- ========================== --}}
        {{-- PAGE: CREATE USER --}}
        {{-- ========================== --}}
        <div id="page-create" class="page-section hidden">

            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah User Baru</h2>

                <form id="createForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input id="create-name" type="text" class="w-full border px-4 py-2 rounded-lg bg-gray-50"
                                placeholder="Masukkan nama user" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input id="create-email" type="email" class="w-full border px-4 py-2 rounded-lg bg-gray-50"
                                placeholder="email@contoh.com" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Password</label>
                            <input id="create-password" type="password"
                                class="w-full border px-4 py-2 rounded-lg bg-gray-50" placeholder="Minimal 6 karakter"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Role</label>
                            <select id="create-role" class="w-full border px-4 py-2 rounded-lg bg-gray-50">
                                <option value="admin">Admin</option>
                                <option value="member" selected>Member</option>
                            </select>
                        </div>

                    </div>

                    <button
                        class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Simpan User
                    </button>
                </form>
            </div>

        </div>

        {{-- ========================== --}}
        {{-- PAGE: DETAIL USER --}}
        {{-- ========================== --}}
        <div id="page-detail" class="page-section hidden bg-white shadow rounded-xl p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail User</h2>

            <p><strong>Nama:</strong> <span id="detail-name"></span></p>
            <p><strong>Email:</strong> <span id="detail-email"></span></p>
            <p><strong>Role:</strong> <span id="detail-role"></span></p>
            <p><strong>Status:</strong> <span id="detail-status"></span></p>

            <button onclick="showPage('list')"
                class="mt-4 bg-gray-400 px-4 py-2 text-white rounded-lg hover:bg-gray-500 transition">
                Kembali
            </button>
        </div>

        {{-- ========================== --}}
        {{-- PAGE: EDIT USER --}}
        {{-- ========================== --}}
        <div id="page-edit" class="page-section hidden bg-white shadow rounded-xl p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit User</h2>

            <form id="editForm">
                @csrf
                <input type="hidden" id="edit-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input id="edit-name" type="text" class="w-full border px-4 py-2 rounded-lg bg-gray-50">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input id="edit-email" type="email" class="w-full border px-4 py-2 rounded-lg bg-gray-50">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Role</label>
                        <select id="edit-role" class="w-full border px-4 py-2 rounded-lg bg-gray-50">
                            <option value="admin">Admin</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <select id="edit-status" class="w-full border px-4 py-2 rounded-lg bg-gray-50">
                            <option value="1">Aktif</option>
                            <option value="0">Belum Aktif</option>
                        </select>
                    </div>

                </div>

                <button
                    class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Update User
                </button>
            </form>

            <button onclick="showPage('list')"
                class="mt-4 bg-gray-400 px-4 py-2 text-white rounded-lg hover:bg-gray-500 transition">
                Kembali
            </button>
        </div>

        {{-- ========================== --}}
        {{-- PAGE: DELETE USER --}}
        {{-- ========================== --}}
        <div id="page-delete" class="page-section hidden bg-white shadow rounded-xl p-5 text-center">
            <h2 class="text-lg font-semibold text-red-600 mb-4">Hapus User</h2>

            <p class="mb-6">
                Apakah Anda yakin ingin menghapus user <strong id="delete-name"></strong>?
            </p>

            <input type="hidden" id="delete-id">

            <button id="delete-confirm"
                class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                Hapus
            </button>
            <button onclick="showPage('list')"
                class="ml-3 bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                Batal
            </button>
        </div>

    </div>

    {{-- Script Navigasi, DataTables & API --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const api = axios.create({
            baseURL: "http://127.0.0.1:8000/api",
            headers: {
                Accept: "application/json"
            },
        });

        function showPage(page) {
            document.querySelectorAll('.page-section').forEach(e => e.classList.add('hidden'));
            document.getElementById('page-' + page).classList.remove('hidden');
        }

        function showUserDetail(name, email, role, status) {
            document.getElementById('detail-name').textContent = name;
            document.getElementById('detail-email').textContent = email;
            document.getElementById('detail-role').textContent = role;
            document.getElementById('detail-status').textContent = status;
            showPage('detail');
        }

        function showEditForm(id, name, email, role, isActive) {
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-name").value = name;
            document.getElementById("edit-email").value = email;

            const roleSelect = document.getElementById("edit-role");
            roleSelect.value = role || 'member';

            const statusSelect = document.getElementById("edit-status");
            statusSelect.value = isActive ? '1' : '0';

            showPage('edit');
        }

        function showDelete(id, name) {
            document.getElementById('delete-id').value = id;
            document.getElementById('delete-name').textContent = name;
            showPage('delete');
        }

        // CREATE USER → POST /api/admin/users
        document.getElementById('createForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('create-name').value;
            const email = document.getElementById('create-email').value;
            const password = document.getElementById('create-password').value;
            const role = document.getElementById('create-role').value;

            try {
                await api.post('/admin/users', {
                    name,
                    email,
                    password,
                    role,
                    is_active: true,
                });

                alert('User berhasil dibuat');
                window.location.reload();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal membuat user');
            }
        });

        // EDIT USER → PUT /api/admin/users/{id}
        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const id = document.getElementById('edit-id').value;
            const name = document.getElementById('edit-name').value;
            const email = document.getElementById('edit-email').value;
            const role = document.getElementById('edit-role').value;
            const is_active = document.getElementById('edit-status').value === '1';

            try {
                await api.put(`/admin/users/${id}`, {
                    name,
                    email,
                    role,
                    is_active,
                });

                alert('User berhasil diupdate');
                window.location.reload();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal update user');
            }
        });

        // DELETE USER → DELETE /api/admin/users/{id}
        document.getElementById('delete-confirm').addEventListener('click', async () => {
            const id = document.getElementById('delete-id').value;

            try {
                await api.delete(`/admin/users/${id}`);
                alert('User berhasil dihapus');
                window.location.reload();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal menghapus user');
            }
        });

        // TOGGLE AKTIF / NONAKTIF
        async function toggleActive(id) {
            if (!confirm('Ubah status aktif user ini?')) return;

            try {
                await api.patch(`/admin/users/${id}/toggle-verify`);
                window.location.reload();
            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Gagal mengubah status user');
            }
        }

        // DataTables init
        $(document).ready(function() {
            $('#usersTable').DataTable({
                pageLength: 10,
                ordering: true,
                columnDefs: [{
                        orderable: false,
                        targets: 4
                    }, // kolom Aksi
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_–_END_ dari _TOTAL_ user",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Tidak ditemukan user yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "›",
                        previous: "‹"
                    }
                }
            });
        });

        // Jam kecil kanan atas
        function updateClock() {
            const el = document.getElementById('clock');
            if (!el) return;
            const now = new Date();
            el.textContent = now.toLocaleString('id-ID', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        }
        setInterval(updateClock, 1000);
        updateClock();

        showPage('list');
    </script>
@endsection
