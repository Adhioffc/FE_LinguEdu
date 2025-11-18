@extends('member.dashboard.main')

@section('title', 'Sertifikasi Pembelajaran')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/materi.css') }}">

@section('content')
        <main class="flex-1 p-8 text-center">
            <h1 class="text-2xl font-bold mb-4">Ujian Sertifikasi</h1>
            <p class="text-gray-600 mb-6">Ujian ini hanya bisa diakses jika kamu sudah menyelesaikan semua level.</p>

            <div class="bg-white rounded-xl p-8 shadow-lg inline-block">
                <i class="fas fa-lock text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">Kamu belum mencapai Level 3. Selesaikan semua materi untuk membuka ujian
                    sertifikasi.</p>
            </div>
        </main>
    </div>
@endsection
