<!DOCTYPE html>
<html lang="id">

<style>
    nav a,
    #profileMenu a,
    #mobileMenu a {
        text-decoration: none !important;
    }

    .dropdown-menu {
        border-radius: 14px;
        padding: 10px;
        animation: fadeIn 0.2s ease;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 8px 14px;
    }

    .dropdown-item:hover {
        background: #f3f4f6;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LinguEdu Dashboard')</title>
    @vite('resources/css/app.css')
    @yield('style')
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    @php
        $userName = Auth::check() ? Auth::user()->name : 'Tamu';
    @endphp

    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

            <div class="flex items-center gap-2">
                <div class="p-2 bg-gradient-to-tr from-indigo-500 to-blue-400 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-5 h-5">
                        <path
                            d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 5v5.25l3.25 1.94-.75 1.22L11 13V7h1.75z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">LinguEdu</h1>
            </div>

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

            <div class="hidden md:block relative">
                <button id="profileBtn"
                    class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition font-semibold">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($userName) }}"
                        class="w-7 h-7 rounded-full">
                    <span>{{ $userName }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="profileMenu"
                    class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-md py-2">
                    <a href="{{ route('dashboard.profile') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        ✏️ Edit Profil
                    </a>

                    <a href="{{ route('logout.simulasi') }}"
                        class="block px-4 py-2 text-red-600 font-semibold hover:bg-gray-100">
                        🚪 Keluar
                    </a>
                </div>
            </div>

            <button id="menuBtn" class="md:hidden text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16m0 6H4" />
                </svg>
            </button>
        </div>

        <div id="mobileMenu" class="hidden flex-col bg-white border-t border-gray-200 shadow-sm md:hidden">
            <a href="{{ route('dashboard.index') }}"
                class="py-2 px-6">Dashboard</a>
            <a href="{{ route('dashboard.materi') }}"
                class="py-2 px-6">Materi</a>
            <a href="{{ route('dashboard.laporan') }}"
                class="py-2 px-6">Progress</a>
            <a href="{{ route('dashboard.sertifikasi') }}"
                class="py-2 px-6">Sertifikasi</a>

            <div class="border-t border-gray-100"></div>

            <a href="{{ route('dashboard.profile') }}" class="py-2 px-6 text-gray-700">
                ✏️ Edit Profil
            </a>

            <a href="{{ route('logout.simulasi') }}"
                class="py-2 px-6 text-red-600 font-semibold border-t border-gray-100">
                🚪 Keluar
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 text-center py-5 text-sm text-gray-600">
        © {{ date('Y') }} <span class="font-semibold text-indigo-600">LinguEdu</span> — All rights reserved
    </footer>

    <script>
        const menuBtn = document.getElementById("menuBtn");
        const mobileMenu = document.getElementById("mobileMenu");
        const profileBtn = document.getElementById("profileBtn");
        const profileMenu = document.getElementById("profileMenu");

        menuBtn?.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });

        profileBtn?.addEventListener("click", () => {
            profileMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", (e) => {
            if (profileBtn && profileMenu) {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add("hidden");
                }
            }
        });
    </script>
</body>
</html>
