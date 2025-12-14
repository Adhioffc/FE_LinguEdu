{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Admin LinguEdu</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50 min-h-screen">

    {{-- TOP NAVBAR ADMIN --}}
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">

            {{-- Logo + Nav --}}
            <div class="flex items-center gap-8">
                {{-- LOGO / BRAND --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-lg font-extrabold text-indigo-600">LinguEdu</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600">
                        Admin
                    </span>
                </a>

                {{-- NAV DESKTOP --}}
                <nav class="hidden md:flex items-center gap-6 text-[14px] font-medium">
                    <a href="{{ route('admin.dashboard') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.dashboard')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.users') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.users')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Users
                    </a>

                    <a href="{{ route('admin.paket') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.paket')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Paket
                    </a>

                    <a href="{{ route('admin.bahasa') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.bahasa')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Bahasa
                    </a>

                    <a href="{{ route('admin.materi') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.materi')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Materi
                    </a>

                    <a href="{{ route('admin.teori') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.teori')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Teori
                    </a>

                    <a href="{{ route('admin.kuis') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.kuis')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Kuis
                    </a>

                    <a href="{{ route('admin.sertifikasi') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.sertifikasi')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Sertifikasi
                    </a>

                    <a href="{{ route('admin.sertifikat') }}"
                        class="pb-1 border-b-2 transition
                        {{ request()->routeIs('admin.sertifikat')
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-700 hover:text-indigo-600 hover:border-indigo-200' }}">
                        Sertifikat
                    </a>
                </nav>
            </div>

            {{-- KANAN: Nama Admin + Logout --}}
            <div class="flex items-center gap-3">
                <span class="hidden md:inline text-sm text-gray-600">
                    {{ auth()->user()->name ?? 'Admin' }}
                </span>

                <a href="{{ route('logout.simulasi') }}"
                    class="text-xs sm:text-sm px-3 py-1.5 rounded-full border border-gray-300
                          text-gray-600 hover:bg-gray-100 transition">
                    Logout
                </a>
            </div>
        </div>
    </header>

    {{-- KONTEN HALAMAN ADMIN --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>
</body>

</html>
