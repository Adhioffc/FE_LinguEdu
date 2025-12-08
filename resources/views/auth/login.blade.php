@extends('member.dashboard.main') {{-- Sesuaikan dengan layout kamu --}}
@section('title', 'Login - LinguEdu')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-blue-50 py-16 px-4">
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-2">Login LinguEdu</h1>
            <p class="text-center text-gray-500 mb-8">Silakan masuk untuk mulai belajar.</p>

            {{-- Tampilkan Error jika Login Gagal --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form Login Murni Laravel --}}
            <form action="{{ route('login.perform') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="adminlinguedu@gmail.com" required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition transform hover:scale-[1.02]">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                Belum punya akun? <a href="{{ route('register.simulasi') }}" class="text-blue-600 font-semibold hover:underline">Daftar disini</a>
            </div>
        </div>
    </div>
@endsection
