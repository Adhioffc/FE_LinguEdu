<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LinguEdu Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

            <!-- LOGO -->
            <div class="flex items-center gap-2">
                <div class="p-2 bg-gradient-to-tr from-indigo-500 to-blue-400 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-5 h-5">
                        <path
                            d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 5v5.25l3.25 1.94-.75 1.22L11 13V7h1.75z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">LinguEdu</h1>
            </div>

            <!-- NAVIGATION -->
            <nav class="hidden md:flex items-center gap-8 text-[15px] font-medium">
                <a href="{{ route('dashboard.index') }}"
                    class="transition {{ request()->routeIs('dashboard.index') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                    Dashboard
                </a>
                <a href="{{ route('dashboard.materi') }}"
                    class="transition {{ request()->routeIs('dashboard.materi') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                    Materi
                </a>
                <a href="{{ route('dashboard.laporan') }}"
                    class="transition {{ request()->routeIs('dashboard.laporan') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                    Progress
                </a>
                <a href="{{ route('dashboard.sertifikasi') }}"
                    class="transition {{ request()->routeIs('dashboard.sertifikasi') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                    Sertifikasi
                </a>
            </nav>

            <!-- LOGOUT -->
            <div class="hidden md:block">
                <a href="{{ route('login.simulasi') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md font-semibold transition">
                    Keluar
                </a>
            </div>

            <!-- MOBILE MENU BUTTON -->
            <button id="menuBtn" class="md:hidden text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16m0 6H4" />
                </svg>
            </button>
        </div>

        <!-- MOBILE MENU -->
        <div id="mobileMenu" class="hidden flex-col bg-white border-t border-gray-200 shadow-sm md:hidden">
            <a href="{{ route('dashboard.index') }}"
                class="py-2 px-6 {{ request()->routeIs('dashboard.index') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                Dashboard
            </a>
            <a href="{{ route('dashboard.materi') }}"
                class="py-2 px-6 {{ request()->routeIs('dashboard.materi') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                Materi
            </a>
            <a href="{{ route('dashboard.laporan') }}"
                class="py-2 px-6 {{ request()->routeIs('dashboard.laporan') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                Progress
            </a>
            <a href="{{ route('dashboard.sertifikasi') }}"
                class="py-2 px-6 {{ request()->routeIs('dashboard.sertifikasi') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                Sertifikasi
            </a>
            <a href="{{ route('login.simulasi') }}"
                class="py-2 px-6 text-red-600 hover:bg-gray-50 font-semibold border-t border-gray-100">
                Keluar
            </a>
        </div>
    </header>

    <!-- KONTEN -->
    <main class="flex-1 max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 text-center py-5 text-sm text-gray-600">
        © {{ date('Y') }} <span class="font-semibold text-indigo-600">LinguEdu</span> — All rights reserved
    </footer>

    <!-- JS MENU -->
    <script>
        const menuBtn = document.getElementById("menuBtn");
        const mobileMenu = document.getElementById("mobileMenu");
        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    </script>

</body>

</html>
