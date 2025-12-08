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
            {{-- ❌ HAPUS BAGIAN YANG NYASAR DISINI KEMARIN --}}

            {{-- Loop Data dari Route --}}
            @forelse ($materiLevel1 as $m)
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
                                    <div class="progress-bar bg-success" style="width: {{ $m['progress'] }}%;">
                                    </div>
                                </div>
                                <a href="{{ route('member.video', ['slug' => $m['slug']]) }}"
                                    class="btn btn-success w-100">
                                    Mulai Belajar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada materi di Level 1.</p>
                </div>
            @endforelse

            <div class="text-center mt-3">
                <button class="btn btn-primary" id="btnCompleteLevel1">✅ Tandai Level 1 Selesai</button>
            </div>
        </div>

        {{-- LEVEL 2 --}}
        <div class="row d-none" id="level2">
            {{-- ❌ DATA DUMMY DIHAPUS, SEKARANG PAKAI DATA DARI ROUTE --}}

            @forelse ($materiLevel2 as $m)
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    {{-- Logic class locked jika progress 0 (sementara kita buka dulu logic-nya) --}}
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
                                    <div class="progress-bar bg-success" style="width: {{ $m['progress'] }}%;">
                                    </div>
                                </div>

                                {{-- Tombol Logic --}}
                                <a href="{{ route('member.video', ['slug' => $m['slug']]) }}" class="btn btn-success w-100">
                                    Lanjutkan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                 <div class="col-12 text-center">
                    <p class="text-muted">Belum ada materi di Level 2.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="{{ asset('js/materi.js') }}"></script>
@endsection
