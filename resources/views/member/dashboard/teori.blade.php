@extends('member.dashboard.main')

@section('title', 'Teori Pembelajaran')

@section('content')
<div class="container py-5 text-center">
    <h2 class="fw-bold text-primary mb-4">📘 Teori: {{ ucwords(str_replace('-', ' ', request()->segment(3) ?? 'Materi')) }}</h2>
    <p>Selamat! Kamu sudah menonton video. Sekarang saatnya memahami teori lebih dalam.</p>
    <a href="#" class="btn btn-success mt-3">Mulai Kuis 🎯</a>
</div>
@endsection
