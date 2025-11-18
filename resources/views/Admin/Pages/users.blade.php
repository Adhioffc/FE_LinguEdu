@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>

            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition font-semibold">
                    ⬅ Kembali ke Dashboard
                </a>
                <div class="text-sm text-gray-600 font-semibold" id="clock"></div>
            </div>
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

                {{-- Tabel User --}}
                <table class="w-full border text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 border">Nama</th>
                            <th class="py-2 px-3 border">Email</th>
                            <th class="py-2 px-3 border">Role</th>
                            <th class="py-2 px-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)

                            <tr>
                                <td class="py-2 px-3 border">{{ $user['name'] }}</td>
                                <td class="py-2 px-3 border">{{ $user['email']}}</td>
                                <td class="py-2 px-3 border">{{ $user['role'] }}</td>
                                <td class="py-2 px-3 border text-center space-x-2">
                                    <button onclick="showUserDetail($user['name'],$user['email'],$user['role'])"
                                        class="text-blue-600">Detail</button>
                                    <button
                                        onclick="showEditForm('{{ $user['name'] }}', '{{ $user['email'] }}', '{{ $user['role'] }}')"
                                        class="text-yellow-600">
                                        Edit
                                    </button>
                                    <button onclick="showDelete('Admin LinguEdu')" class="text-red-600">Hapus</button>
                                </td>
                            </tr>


                            {{-- modal --}}


                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
        {{-- ========================== --}}
        {{-- PAGE: CREATE USER --}}
        {{-- ========================== --}}
        <div id="page-create" class="page-section hidden">

            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah User Baru</h2>

                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" class="w-full border px-4 py-2 rounded-lg bg-gray-50"
                                placeholder="Masukkan nama user">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input type="email" class="w-full border px-4 py-2 rounded-lg bg-gray-50"
                                placeholder="email@contoh.com">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Password</label>
                            <input type="password" class="w-full border px-4 py-2 rounded-lg bg-gray-50"
                                placeholder="••••••••">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Role</label>
                            <select class="w-full border px-4 py-2 rounded-lg bg-gray-50">
                                <option>Admin</option>
                                <option>Instruktur</option>
                                <option>Siswa</option>
                            </select>
                        </div>

                    </div>

                    <button class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold">Simpan User</button>
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

            <button onclick="showPage('list')" class="mt-4 bg-gray-400 px-4 py-2 text-white rounded-lg">Kembali</button>
        </div>


        {{-- ========================== --}}
        {{-- PAGE: EDIT USER --}}
        {{-- ========================== --}}
        <div id="page-edit" class="page-section hidden bg-white shadow rounded-xl p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit User</h2>

            <form>
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

                </div>

                <button class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold">Update User</button>
            </form>

            <button onclick="showPage('list')" class="mt-4 bg-gray-400 px-4 py-2 text-white rounded-lg">Kembali</button>
        </div>


        {{-- ========================== --}}
        {{-- PAGE: DELETE USER --}}
        {{-- ========================== --}}
        <div id="page-delete" class="page-section hidden bg-white shadow rounded-xl p-5 text-center">
            <h2 class="text-lg font-semibold text-red-600 mb-4">Hapus User</h2>

            <p class="mb-6">Apakah Anda yakin ingin menghapus user <strong id="delete-name"></strong>?</p>

            <button class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold">Hapus</button>
            <button onclick="showPage('list')" class="ml-3 bg-gray-400 text-white px-6 py-2 rounded-lg">Batal</button>
        </div>

    </div>

    {{-- Script Navigasi & Preview --}}
    <script>
        function showPage(page) {
            document.querySelectorAll('.page-section').forEach(e => e.classList.add('hidden'));
            document.getElementById('page-' + page).classList.remove('hidden');
        }

        function showUserDetail(name, email, role) {
            document.getElementById('detail-name').textContent = name;
            document.getElementById('detail-email').textContent = email;
            document.getElementById('detail-role').textContent = role;
            showPage('detail');
        }

        function showEditForm(name, email, role) {
            document.getElementById("edit-name").value = name;
            document.getElementById("edit-email").value = email;

            const roleSelect = document.getElementById("edit-role");
            roleSelect.value = role;

            if (!roleSelect.value) {
                roleSelect.value = "member";
            }

            // Tampilkan modal
            showPage('edit');

        }


        function showDelete(name) {
            document.getElementById('delete-name').textContent = name;
            showPage('delete');
        }
    </script>

@endsection
