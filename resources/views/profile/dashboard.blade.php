@extends('layouts.main') {{-- Pastikan ini sesuai layout kamu --}}

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-sm text-gray-500">Kelola informasi akun Anda</p>
        </div>

        <a href="{{ route('profile.edit') }}"
           class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition text-sm font-semibold">
            ✏️ Edit Profil
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">

        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 px-8 py-6 flex items-center gap-6">
            {{-- ✅ REVISI 1: Pakai Auth::user() dan urlencode biar aman --}}
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ffffff&color=6366f1"
                 class="w-20 h-20 rounded-full border-4 border-white shadow">

            <div class="text-white">
                {{-- ✅ REVISI 2: Ganti session('user') jadi Auth::user() --}}
                <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-sm opacity-90">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">

            <div>
                <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                <p class="font-semibold text-gray-800">
                    {{-- ✅ REVISI 3 --}}
                    {{ Auth::user()->name }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="font-semibold text-gray-800">
                    {{-- ✅ REVISI 4 --}}
                    {{ Auth::user()->email }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Role</p>
                <p class="font-semibold text-gray-800 capitalize">
                    {{-- ✅ REVISI 5 --}}
                    {{ Auth::user()->role ?? 'member' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Status Akun</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                    ✅ Aktif
                </span>
            </div>

        </div>

        <div class="px-8 py-5 bg-gray-50 border-t flex justify-between items-center">

            <a href="{{ route('dashboard.index') }}"
               class="text-sm text-gray-600 hover:text-indigo-600 font-medium">
                ⬅️ Kembali ke Dashboard
            </a>

            {{-- Pastikan ini route logout yang benar --}}
            <a href="{{ route('logout.simulasi') }}"
               class="text-sm font-semibold text-red-600 hover:text-red-700 transition">
                🚪 Logout
            </a>

        </div>

    </div>

</div>
@endsection
