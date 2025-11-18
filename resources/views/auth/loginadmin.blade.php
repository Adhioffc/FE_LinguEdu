@extends('layouts.main')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">

        {{-- Logo / Title --}}
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Admin Panel</h2>
        <p class="text-center text-gray-500 mb-6 text-sm">Silakan login sebagai administrator</p>

        {{-- Form Login --}}
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            {{-- Email --}}
            <label class="block mb-3">
                <span class="text-gray-700 text-sm">Email</span>
                <input type="email" name="email" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="admin@example.com">
            </label>

            {{-- Password --}}
            <label class="block mb-4">
                <span class="text-gray-700 text-sm">Password</span>
                <input type="password" name="password" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="••••••••">
            </label>

            {{-- Tombol login --}}
            <button type="submit"
                class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                Login Admin
            </button>
        </form>

        {{-- Link Back --}}
        <div class="text-center mt-6">
            <a href="{{ route('login.simulasi') }}" class="text-sm text-blue-600 hover:underline">
                Login sebagai User →
            </a>
        </div>

    </div>
</div>

@endsection
