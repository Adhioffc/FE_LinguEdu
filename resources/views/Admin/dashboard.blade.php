@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard LinguEdu</h1>
            <div class="text-sm text-gray-600 font-semibold" id="clock"></div>
        </div>

        {{-- Notifikasi Verifikasi Akun --}}
        @if (($pendingVerifications ?? 0) > 0)
            <div
                class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 shadow-sm rounded-lg flex items-center justify-between">
                <div>
                    ⚠️ <strong>{{ $pendingVerifications }}</strong> akun member menunggu verifikasi!
                    <span class="ml-1 text-sm">
                        (email_verified_at masih kosong)
                    </span>
                </div>
                <a href="{{ route('admin.users') }}" class="underline text-yellow-700 hover:text-yellow-900 text-sm">
                    Lihat sekarang
                </a>
            </div>
        @endif

        {{-- Statistik Member & Penghasilan --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Statistik Member --}}
            <div class="bg-white shadow rounded-xl p-5 relative">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Statistik Member</h2>
                <div class="flex flex-col lg:flex-row justify-between items-center">
                    <div class="mb-4 lg:mb-0">
                        <p class="text-gray-600">Total Member:</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ number_format($totalMembers ?? 0) }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            +{{ $newMembersThisWeek ?? 0 }} selama 7 hari terakhir
                        </p>
                        <p class="text-gray-400 text-xs mt-1">
                            Diagram donat di samping menunjukkan sebaran member per bahasa.
                        </p>
                    </div>
                    <div class="w-full lg:w-1/2 h-48">
                        <canvas id="memberChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Penghasilan --}}
            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Grafik Penghasilan</h2>
                <p class="text-gray-500 text-sm mb-2">
                    Total pembayaran registrasi per bulan (maks. 6 bulan terakhir).
                </p>
                <div class="w-full h-48">
                    <canvas id="incomeChart" class="w-full h-full"></canvas>
                </div>
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

                {{-- Setting Bahasa --}}
                <a href="{{ route('admin.bahasa') }}"
                    class="p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition shadow-sm block">
                    <h3 class="text-green-700 font-semibold mb-1">📦 Setting Bahasa</h3>
                    <p class="text-sm text-gray-600">Atur bahasa.</p>
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

                {{-- Setting Uji Sertifikasi --}}
                <a href="{{ route('admin.sertifikasi') }}"
                    class="p-4 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition shadow-sm block">
                    <h3 class="text-red-700 font-semibold mb-1">🎓 Setting Sertifikasi</h3>
                    <p class="text-sm text-gray-600">Konfigurasi uji sertifikasi & soal.</p>
                </a>

                {{-- Setting Template Sertifikat --}}
                <a href="{{ route('admin.sertifikat') }}"
                    class="p-4 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition shadow-sm block">
                    <h3 class="text-red-700 font-semibold mb-1">🎓 Setting Sertifikat</h3>
                    <p class="text-sm text-gray-600">Konfigurasi template & format sertifikat.</p>
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
            document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Data dari backend (Blade -> JS)
        const memberLabels = @json($memberChartLabels ?? []);
        const memberData = @json($memberChartData ?? []);

        const incomeLabels = @json($incomeLabels ?? []);
        const incomeData = @json($incomeData ?? []);

        // Warna untuk doughnut (lebih banyak dari jumlah label biar aman)
        const donutColors = ['#3b82f6', '#10b981', '#facc15', '#f97316', '#a855f7', '#ec4899'];

        // Grafik Member (per bahasa)
        const ctx1 = document.getElementById('memberChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: memberLabels.length ? memberLabels : ['Belum ada data'],
                datasets: [{
                    data: memberData.length ? memberData : [1],
                    backgroundColor: donutColors.slice(0, Math.max(memberData.length, 1)),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Grafik Penghasilan
        const ctx2 = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: incomeLabels.length ? incomeLabels : ['Belum ada data'],
                datasets: [{
                    label: 'Penghasilan (total_byr)',
                    data: incomeData.length ? incomeData : [0],
                    borderColor: '#16a34a',
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
