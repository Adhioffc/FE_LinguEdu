<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LinguEdu')</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex flex-col min-h-screen">
    {{-- HEADER --}}
    <header class="bg-white shadow-md px-6 py-4 flex justify-between items-center relative z-[9999]">
        {{-- Nama Aplikasi --}}
        <div class="text-2xl font-bold text-blue-700">LinguEdu</div>

        {{-- Menubar --}}
        <nav class="hidden md:flex space-x-6 text-gray-700 font-medium">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
            <a href="{{ route('paket') }}" class="hover:text-blue-600 transition">Paket</a>
            <a href="{{ route('materi') }}" class="hover:text-blue-600 transition">Materi</a>
            <a href="{{ route('kuis') }}" class="hover:text-blue-600 transition">Kuis</a>
            <a href="{{ route('sertifikasi') }}" class="hover:text-blue-600 transition">Sertifikasi</a>
            <a href="{{ route('manajemen-user') }}" class="hover:text-blue-600 transition">Manajemen User</a>
        </nav>

        {{-- PROFIL DROPDOWN --}}
        <div class="relative" id="profileContainer">
            <button id="profileBtn"
                class="flex items-center space-x-3 bg-white border border-gray-300 rounded-full px-3 py-2 shadow-sm hover:shadow-md transition focus:outline-none">
                
                {{-- Foto profil --}}
                <img src="{{ asset('images/profile_default.jpg') }}" 
                     alt="Profile"
                     class="w-9 h-9 rounded-full object-cover border border-blue-400 hover:scale-105 transition-transform duration-200">

                {{-- Nama pengguna --}}
                <span class="font-semibold text-gray-800 hover:text-blue-600 transition">
                    {{ Auth::user()->name ?? 'Admin LinguEdu' }}
                </span>

                {{-- Icon dropdown --}}
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="w-5 h-5 text-gray-500" 
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown --}}
            <div id="profileMenu"
                class="hidden absolute right-0 mt-2 bg-white border rounded-xl shadow-lg w-52 z-[10000]">
                <div class="px-4 py-3 border-b">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ Auth::user()->name ?? 'Admin LinguEdu' }}
                    </p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Profil</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                <div class="px-4 py-2 text-xs text-gray-500 border-t mt-1 bg-gray-50">
                    <span id="clock" class="font-semibold"></span>
                </div>
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 p-6 relative overflow-visible">
        @yield('content')
    </main>
</div>

{{-- SCRIPT DROPDOWN & JAM REALTIME --}}
<script>
    // Toggle dropdown profil
    const btn = document.getElementById('profileBtn');
    const menu = document.getElementById('profileMenu');
    document.addEventListener('click', (e) => {
        if (btn.contains(e.target)) {
            menu.classList.toggle('hidden');
        } else if (!menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // Realtime clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('clock').textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

</body>
</html>
