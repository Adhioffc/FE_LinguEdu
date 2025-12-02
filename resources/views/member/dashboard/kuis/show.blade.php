@extends('member.dashboard.main')

@section('title', 'Kuis Pembelajaran')

@section('content')
    @php
        $slug = $slug ?? (request()->route('slug') ?? 'introduction-to-programming');
        $title = 'Kuis: ' . ucwords(str_replace('-', ' ', $slug));
        $teoriUrl = route('member.teori', ['slug' => $slug]);
    @endphp

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">{{ $title }}</h2>
                <small class="text-muted">Jawab pertanyaan berikut untuk menguji pemahamanmu.</small>
            </div>
            <div>
                <a href="{{ $teoriUrl }}" class="btn btn-outline-secondary me-2">← Kembali ke Teori</a>
                <a href="{{ route('dashboard.materi') }}" class="btn btn-outline-primary">Kembali ke Materi</a>
            </div>
        </div>

        {{-- Kuis dummy sederhana --}}
        <form class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">1. Apa itu variabel dalam pemrograman?</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q1" id="q1a" value="a">
                    <label class="form-check-label" for="q1a">
                        Tempat menyimpan data di memori.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q1" id="q1b" value="b">
                    <label class="form-check-label" for="q1b">
                        Sebuah perulangan.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q1" id="q1c" value="c">
                    <label class="form-check-label" for="q1c">
                        Kesalahan program.
                    </label>
                </div>

                <hr>

                <h5 class="mb-3">2. Operator apa yang digunakan untuk membandingkan kesamaan di banyak bahasa?</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q2" id="q2a" value="a">
                    <label class="form-check-label" for="q2a">
                        =
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q2" id="q2b" value="b">
                    <label class="form-check-label" for="q2b">
                        ==
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q2" id="q2c" value="c">
                    <label class="form-check-label" for="q2c">
                        +
                    </label>
                </div>

                <hr>

                <h5 class="mb-3">3. Manakah yang termasuk struktur kontrol alur?</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="q3a" value="if">
                    <label class="form-check-label" for="q3a">
                        if / else
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="q3b" value="for">
                    <label class="form-check-label" for="q3b">
                        for / while
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="q3c" value="echo">
                    <label class="form-check-label" for="q3c">
                        echo / print
                    </label>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Kuis untuk materi: {{ $slug }}</small>
                    <button type="button" class="btn btn-success">
                        Selesai Kuis (dummy)
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
