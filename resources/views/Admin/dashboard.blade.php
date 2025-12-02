@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard LinguEdu</h1>
            <div class="text-sm md:text-base text-gray-600 font-semibold" id="clock"></div>
        </div>

        {{-- NOTIFIKASI VERIFIKASI AKUN (diisi via API) --}}
        <div id="pendingBox"
            class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 shadow-sm rounded-lg flex justify-between items-center">
            <div>
                ⚠️ <strong id="pendingCount">0</strong> akun member menunggu verifikasi!
            </div>
            <a href="{{ route('admin.users') }}"
                class="underline text-yellow-700 hover:text-yellow-900 text-sm font-semibold">
                Lihat daftar user
            </a>
        </div>

        {{-- STAT KECIL DI ATAS (TOTAL MEMBER + MEMBER BARU) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">

            <div class="bg-white shadow rounded-xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    👥
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Member</p>
                    <p id="statTotalMember" class="text-2xl font-bold text-gray-800">0</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-4 flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    ➕
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Member Baru (7 Hari Terakhir)</p>
                    <p id="statNewMember" class="text-2xl font-bold text-gray-800">0</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-xl p-4 hidden xl:flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                    📊
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Info Singkat</p>
                    <p id="statInfo" class="text-base font-semibold text-gray-800">
                        Memuat statistik...
                    </p>
                </div>
            </div>
        </div>

        {{-- GRAFIK STATISTIK --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

            {{-- CHART PAKET --}}
            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Distribusi Member per Paket</h2>
                <p class="text-xs text-gray-500 mb-3">
                    Menunjukkan berapa banyak registrasi per paket (Basic / Intermediate / Advance, dll).
                </p>
                <div class="h-56">
                    <canvas id="chartPaket"></canvas>
                </div>
            </div>

            {{-- CHART BAHASA --}}
            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Distribusi Member per Bahasa</h2>
                <p class="text-xs text-gray-500 mb-3">
                    Berapa banyak member yang mengambil tiap bahasa (Inggris, Jepang, Korea, dll).
                </p>
                <div class="h-56">
                    <canvas id="chartBahasa"></canvas>
                </div>
            </div>

            {{-- CHART KURSUS (BAHASA + PAKET) --}}
            <div class="bg-white shadow rounded-xl p-5">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Kombinasi Kursus (Bahasa + Paket)</h2>
                <p class="text-xs text-gray-500 mb-3">
                    Contoh label: "Inggris - Basic", "Jepang - Intensive", dll.
                </p>
                <div class="h-56">
                    <canvas id="chartKursus"></canvas>
                </div>
            </div>

        </div>

        {{-- MENU PENGATURAN (TETAP, HANYA DATA DI ATAS YANG DINAMIS) --}}
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
                    class="p-4 bg-teal-50 border border-teal-200 rounded-xl hover:bg-teal-100 transition shadow-sm block">
                    <h3 class="text-teal-700 font-semibold mb-1">🌐 Setting Bahasa</h3>
                    <p class="text-sm text-gray-600">Atur bahasa yang tersedia.</p>
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
                    <p class="text-sm text-gray-600">Atur soal kuis & evaluasi materi.</p>
                </a>

                {{-- Setting Uji Sertifikasi --}}
                <a href="{{ route('admin.sertifikasi') }}"
                    class="p-4 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition shadow-sm block">
                    <h3 class="text-red-700 font-semibold mb-1">🏅 Setting Uji Sertifikasi</h3>
                    <p class="text-sm text-gray-600">Konfigurasi soal uji sertifikasi.</p>
                </a>

                {{-- Setting Sertifikat --}}
                <a href="{{ route('admin.sertifikat') }}"
                    class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition shadow-sm block">
                    <h3 class="text-indigo-700 font-semibold mb-1">🎓 Setting Sertifikat</h3>
                    <p class="text-sm text-gray-600">Template & pengaturan sertifikat.</p>
                </a>

            </div>
        </div>

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ===============================
        //  INSTANCE AXIOS KHUSUS API
        // ===============================
        const api = axios.create({
            baseURL: 'http://127.0.0.1:8000/api', // sama seperti halaman admin lain
            headers: {
                Accept: 'application/json',
            },
        });

        // ===============================
        //  JAM REALTIME
        // ===============================
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('clock').textContent =
                now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ===============================
        //  DASHBOARD DATA DARI API
        // ===============================
        let chartPaket = null;
        let chartBahasa = null;
        let chartKursus = null;

        async function loadDashboardSummary() {
            try {
                // ⚠️ PENTING: pakai instance `api`, dan pathnya TANPA /api lagi
                const res = await api.get('/admin/dashboard/summary');
                const data = res.data;

                // --- NOTIFIKASI PENDING ---
                const pendingCount = data.pending_verifications ?? 0;
                const pendingBox = document.getElementById('pendingBox');
                const pendingCountEl = document.getElementById('pendingCount');

                pendingCountEl.textContent = pendingCount;
                if (pendingCount > 0) {
                    pendingBox.classList.remove('hidden');
                } else {
                    pendingBox.classList.add('hidden');
                }

                // --- STAT KECIL ---
                document.getElementById('statTotalMember').textContent =
                    data.total_members ?? 0;
                document.getElementById('statNewMember').textContent =
                    data.new_members_this_week ?? 0;

                const infoEl = document.getElementById('statInfo');
                const topBahasa = (data.bahasa?.labels?.[0]) || '-';
                const topPaket = (data.paket?.labels?.[0]) || '-';
                infoEl.textContent = `Bahasa terpopuler: ${topBahasa}, Paket terbanyak: ${topPaket}.`;

                // --- GRAFIK ---
                renderChartPaket(data.paket?.labels || [], data.paket?.data || []);
                renderChartBahasa(data.bahasa?.labels || [], data.bahasa?.data || []);
                renderChartKursus(data.kursus?.labels || [], data.kursus?.data || []);

            } catch (e) {
                console.error('Dashboard error:', e.response?.status, e.response?.data ?? e);
                alert(e.response?.data?.message || 'Gagal memuat data dashboard');
            }
        }

        // ===============================
        //  RENDER CHART (tetap sama)
        // ===============================
        function renderChartPaket(labels, values) {
            const ctx = document.getElementById('chartPaket').getContext('2d');
            if (chartPaket) chartPaket.destroy();

            chartPaket = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f97316',
                            '#a855f7', '#facc15', '#ef4444'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function renderChartBahasa(labels, values) {
            const ctx = document.getElementById('chartBahasa').getContext('2d');
            if (chartBahasa) chartBahasa.destroy();

            chartBahasa = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Member',
                        data: values,
                        backgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function renderChartKursus(labels, values) {
            const ctx = document.getElementById('chartKursus').getContext('2d');
            if (chartKursus) chartKursus.destroy();

            chartKursus = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Registrasi',
                        data: values,
                        backgroundColor: '#6366f1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // INIT
        document.addEventListener('DOMContentLoaded', loadDashboardSummary);
    </script>
@endsection
