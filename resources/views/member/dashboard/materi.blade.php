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
            {{-- TOMBOL LEVEL 1 (Selalu Terbuka) --}}
            <button class="btn btn-primary" id="btnLevel1">Level 1</button>

            {{-- TOMBOL LEVEL 2 (Cek Logic) --}}
            @if(isset($userLevel) && $userLevel >= 2)
                {{-- Kalau Level >= 2: Tombol Nyala & Gembok Hilang --}}
                <button class="btn btn-outline-primary" id="btnLevel2" onclick="showLevel(2)">
                    Level 2 🔓
                </button>
            @else
                {{-- Kalau Level < 2: Tombol Mati & Gembok Ada --}}
                <button class="btn btn-outline-secondary" id="btnLevel2" disabled>
                    Level 2 🔒
                </button>
            @endif

            {{-- TOMBOL LEVEL 3 (Cek Logic) --}}
            @if(isset($userLevel) && $userLevel >= 3)
                <button class="btn btn-outline-primary" id="btnLevel3" onclick="showLevel(3)">
                    Level 3 🔓
                </button>
            @else
                <button class="btn btn-outline-secondary" id="btnLevel3" disabled>
                    Level 3 🔒
                </button>
            @endif
        </div>

        {{-- LEVEL 1 --}}
        <div class="row" id="level1">

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
                                    {{-- Progress Bar Dinamis --}}
                                    <div class="progress-bar bg-success" style="width: {{ $m['progress'] }}%;"></div>
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

            {{-- 👇 BAGIAN INI YANG KITA UPDATE (Pintar) --}}
            <div class="text-center mt-3">
                @if(isset($isLevel1Finished) && $isLevel1Finished)
                    {{-- Kalau Selesai: Tombol Nyala & Bisa Diklik --}}
                    {{-- Cek apakah level user sudah lebih dari 1? Kalau iya, tombolnya berubah jadi 'Sudah Selesai' --}}
                    @if(isset($userLevel) && $userLevel > 1)
                         <button class="btn btn-success" disabled>
                            ✅ Level 1 Selesai (Level 2 Terbuka)
                        </button>
                    @else
                        <button onclick="unlockLevel(1)" class="btn btn-primary">
                            ✅ Tandai Level 1 Selesai & Buka Level 2
                        </button>
                    @endif
                @else
                    {{-- Kalau Belum Selesai: Tombol Mati (Disabled) --}}
                    <button class="btn btn-secondary" style="cursor: not-allowed; opacity: 0.6;" disabled title="Selesaikan semua materi & kuis dulu!">
                        🔒 Selesaikan Semua Materi Dulu
                    </button>
                @endif
            </div>

        </div>

        {{-- LEVEL 2 --}}
        <div class="row d-none" id="level2">
            @forelse ($materiLevel2 as $m)
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
                                    <div class="progress-bar bg-success" style="width: {{ $m['progress'] }}%;"></div>
                                </div>
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

    {{-- 👇 SCRIPT LENGKAP UNLOCK LEVEL --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Ambil ID user dari auth blade
        const currentUserId = {{ Auth::id() ?? 'null' }};

        function unlockLevel(currentLevel) {
            Swal.fire({
                title: 'Naik Level?',
                text: "Kamu akan membuka materi Level " + (currentLevel + 1),
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Buka Level Berikutnya!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // 1. Tampilkan Loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Membuka kunci level...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    // 2. Tembak API Backend
                    fetch('http://127.0.0.1:8000/api/member/level-up', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id_member: currentUserId,
                            current_level: currentLevel
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Tutup loading
                        Swal.close();

                        if(data.new_level) {
                            // SUKSES!
                            Swal.fire({
                                title: 'Selamat!',
                                text: 'Level ' + data.new_level + ' berhasil dibuka! 🎉',
                                icon: 'success'
                            }).then(() => {
                                // Reload halaman biar gembok kebuka
                                location.reload();
                            });
                        } else {
                            // Gagal (Misal materi belum kelar)
                            // Tampilkan pesan error dari backend
                            Swal.fire('Gagal', data.message || 'Gagal update level', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Ups!', 'Terjadi kesalahan koneksi.', 'error');
                    });
                }
            })
        }

        // 👇 Logic Pindah Tab Level (Visual)
        function showLevel(level) {
            // 1. Sembunyikan semua level dulu
            document.getElementById('level1').classList.add('d-none');
            document.getElementById('level2').classList.add('d-none');
            // document.getElementById('level3').classList.add('d-none'); // Kalau ada level 3

            // 2. Munculkan level yang dipilih
            document.getElementById('level' + level).classList.remove('d-none');

            // 3. Update Tampilan Tombol (Biar kelihatan mana yang aktif)
            updateButtonStyles(level);
        }

        function updateButtonStyles(activeLevel) {
            // Reset semua tombol ke style "Outline"
            const btn1 = document.getElementById('btnLevel1');
            const btn2 = document.getElementById('btnLevel2');
            const btn3 = document.getElementById('btnLevel3');

            // Reset Style
            if(btn1) btn1.className = 'btn btn-outline-primary';
            if(btn2 && !btn2.disabled) btn2.className = 'btn btn-outline-primary';
            if(btn3 && !btn3.disabled) btn3.className = 'btn btn-outline-primary';

            // Set tombol aktif jadi "Solid"
            const activeBtn = document.getElementById('btnLevel' + activeLevel);
            if(activeBtn) {
                activeBtn.className = 'btn btn-primary';
            }
        }

        // Tambahkan Event Listener buat Tombol Level 1 (karena dia gapake onclick di HTML)
        document.getElementById('btnLevel1').addEventListener('click', function() {
            showLevel(1);
        });
    </script>
@endsection
