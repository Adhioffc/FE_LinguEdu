@extends('member.dashboard.main')

@section('title', 'Materi Pembelajaran')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/materi.css') }}">

    <div class="container py-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">🎓 Materi Pembelajaran</h2>
            <p class="text-muted mb-0">Selesaikan setiap materi di tiap level untuk membuka level berikutnya!</p>
        </div>

        <div class="level-buttons flex-wrap d-flex justify-content-center">
            <button class="btn btn-primary" id="btnLevel1">Level 1</button>
            <button class="btn btn-outline-secondary" id="btnLevel2" disabled>Level 2 🔒</button>
            <button class="btn btn-outline-secondary" id="btnLevel3" disabled>Level 3 🔒</button>
        </div>

        {{-- LEVEL 1 --}}
        <div class="row" id="level1">
            @php
                $materiLevel1 = [
                    ['title' => 'Introduction to Programming', 'desc' => 'Pengenalan dasar tentang logika dan konsep pemrograman.', 'img' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80', 'progress' => 100],
                    ['title' => 'Variables & Data Types', 'desc' => 'Mengenal tipe data dan cara menyimpan informasi.', 'img' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80', 'progress' => 100],
                    ['title' => 'Operators & Expressions', 'desc' => 'Memahami cara kerja operator dan ekspresi dalam program.', 'img' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80', 'progress' => 100],
                    ['title' => 'Conditional Statements', 'desc' => 'Menggunakan IF, ELSE, dan SWITCH untuk keputusan program.', 'img' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80', 'progress' => 100],
                    ['title' => 'Loops', 'desc' => 'Membuat perulangan menggunakan FOR, WHILE, dan DO WHILE.', 'img' => 'https://images.unsplash.com/photo-1581090700227-1e37b190418e?auto=format&fit=crop&w=800&q=80', 'progress' => 100]
                ];
            @endphp
            @php use Illuminate\Support\Str; @endphp
            @foreach ($materiLevel1 as $m)
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="{{ $m['img'] }}" class="card-img-top" alt="{{ $m['title'] }}"
                            onerror="this.src='https://via.placeholder.com/800x400?text=No+Image';">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title">{{ $m['title'] }}</h5>
                                <p class="text-muted small">{{ $m['desc'] }}</p>
                            </div>
                            <div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" style="width: <?php    echo $m['progress']; ?>%;">
                                    </div>
                                </div>
                                <a href="{{ route('member.video', ['slug' => Str::slug($m['title'], '-')]) }}"
                                    class="btn btn-success w-100">
                                    Mulai Belajar
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-3">
                <button class="btn btn-primary" id="btnCompleteLevel1">✅ Tandai Level 1 Selesai</button>
            </div>
        </div>

        {{-- LEVEL 2 --}}
        <div class="row d-none" id="level2">
            @php
                $materiLevel2 = [
                    ['title' => 'Object-Oriented Basics', 'desc' => 'Konsep dasar OOP dan mengapa penting dalam pengembangan software.', 'img' => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?auto=format&fit=crop&w=800&q=80', 'progress' => 60],
                    ['title' => 'Constructors', 'desc' => 'Memahami fungsi konstruktor dan inisialisasi objek.', 'img' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80', 'progress' => 40],
                    ['title' => 'Encapsulation', 'desc' => 'Mengamankan data dengan konsep encapsulation.', 'img' => 'https://images.unsplash.com/photo-1591696205602-2f950c417cb9?auto=format&fit=crop&w=800&q=80', 'progress' => 20],
                    ['title' => 'Inheritance', 'desc' => 'Konsep pewarisan atribut dan metode antar kelas.', 'img' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=800&q=80', 'progress' => 0],
                    ['title' => 'Polymorphism', 'desc' => 'Mengenal polymorphism dan penerapannya di Java.', 'img' => 'https://images.unsplash.com/photo-1547658718-dde1b3023ef0?auto=format&fit=crop&w=800&q=80', 'progress' => 0],
                    ['title' => 'Abstraction', 'desc' => 'Konsep abstraksi dalam pemrograman berorientasi objek.', 'img' => 'https://images.unsplash.com/photo-1532619187608-e5375cab36aa?auto=format&fit=crop&w=800&q=80', 'progress' => 0],
                ];
            @endphp

            @foreach ($materiLevel2 as $m)
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card shadow-sm {{ $m['progress'] == 0 ? 'locked' : '' }} h-100">
                        <img src="{{ $m['img'] }}" class="card-img-top" alt="{{ $m['title'] }}"
                            onerror="this.src='https://via.placeholder.com/800x400?text=No+Image';">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title">{{ $m['title'] }}</h5>
                                <p class="text-muted small">{{ $m['desc'] }}</p>
                            </div>
                            <div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" style="width: <?php    echo $m['progress']; ?>%;">
                                    </div>
                                </div>
                                @if ($m['progress'] > 0)
                                    <button class="btn btn-success w-100">Lanjutkan</button>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>🔒 Terkunci</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/materi.js') }}"></script>
@endsection
