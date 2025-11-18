@extends('layouts.main')
@section('title', 'Logout - LinguEdu')

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center bg-blue-50">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Kamu telah keluar.</h1>
        <a href="{{ route('login.simulasi') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Kembali ke Login</a>
    </div>
@endsection
