@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard LinguEdu</h1>
            <div class="text-sm text-gray-600 font-semibold" id="clock"></div>
        </div>

        {{-- Notifikasi Verifikasi Akun --}}
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 shadow-sm rounded-lg animate-pulse">
            ⚠️ <strong>{{ $pendingVerifications ?? 12 }}</strong> akun menunggu verifikasi!
            <a href="#" class="underline text-yellow-700 hover:text-yellow-900">Lihat sekarang</a>
        </div>

        {{-- Statistik Member & Penghasilan --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Statistik Member --}}
            <div class="bg-white shadow rounded-xl p-5 relative">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Statistik Member</h2>
                <div class="flex flex-col lg:flex-row justify-between items-center">
                    <div>
                        <p class="text-gray-600">Total Member:</p>
                        <p class="text-2xl font-bold text-blue-600">1,245</p>
                        <p class="text-gray-500 text-sm">+23 minggu ini</p>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <canvas id="memberChart" class="max-h-48"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Penghasilan --}}
            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Grafik Penghasilan</h2>
                <canvas id="incomeChart" class="max-h-48"></canvas>
            </div>
        </div>

        {{-- MENU PENGATURAN --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Menu Pengaturan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Manajemen User --}}
                <a href="{{ route('admin.users') }}"
                    class="p-4 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition shadow-sm block">
                    <h3 class="text-blue-700 font-semibold mb-1">👥 Manajemen User</h3>
                    <p class="text-sm text-gray-600">Kelola data member & admin.</p>
                </a>

                {{-- Setting Paket --}}
                <a href="{{ route('admin.paket') }}"
                    class="p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition shadow-sm block">
                    <h3 class="text-green-700 font-semibold mb-1">📦 Setting Paket</h3>
                    <p class="text-sm text-gray-600">Atur paket langganan & harga.</p>
                </a>

                {{-- Setting Materi --}}
                <a href="{{ route('admin.materi') }}"
                    class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl hover:bg-yellow-100 transition shadow-sm block">
                    <h3 class="text-yellow-700 font-semibold mb-1">📘 Setting Materi</h3>
                    <p class="text-sm text-gray-600">Kelola materi pembelajaran.</p>
                </a>

                {{-- Setting Kuis --}}
                <a href="{{ route('admin.kuis') }}"
                    class="p-4 bg-purple-50 border border-purple-200 rounded-xl hover:bg-purple-100 transition shadow-sm block">
                    <h3 class="text-purple-700 font-semibold mb-1">❓ Setting Kuis</h3>
                    <p class="text-sm text-gray-600">Atur soal kuis & evaluasi.</p>
                </a>

                {{-- Setting Sertifikasi --}}
                <a href="{{ route('admin.sertifikasi') }}"
                    class="p-4 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition shadow-sm block">
                    <h3 class="text-red-700 font-semibold mb-1">🎓 Setting Sertifikasi</h3>
                    <p class="text-sm text-gray-600">Konfigurasi sertifikat & tes.</p>
                </a>


            </div>
        </div>

    </div>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Jam realtime
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Grafik Member
        const ctx1 = document.getElementById('memberChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Jepang', 'Korea', 'Inggris'],
                datasets: [{
                    data: [45, 30, 25],
                    backgroundColor: ['#3b82f6', '#10b981', '#facc15']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Grafik Penghasilan
        const ctx2 = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Penghasilan (juta)',
                    data: [5, 6, 7.5, 6.8, 9, 10],
                    borderColor: '#16a34a',
                    fill: false,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

    </script>
@endsection