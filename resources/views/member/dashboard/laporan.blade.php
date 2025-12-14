@extends('member.dashboard.main')
@section('title', 'Laporan Progres - LinguEdu')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container py-5">
        <h3 class="fw-bold mb-4 text-primary">📊 Laporan Progres Belajar</h3>

        <div class="row g-4">
            {{-- Kartu Level --}}
            <div class="col-md-6">
                <div class="p-4 bg-white rounded shadow-sm">
                    <h6 class="fw-bold text-secondary mb-2">Level Kamu Saat Ini</h6>
                    <h4 class="fw-bold text-dark">
                        Level {{ $userLevel ?? 1 }} - {{ $levelLabel ?? 'Beginner' }}
                    </h4>
                    <p class="text-muted small mb-0">
                        Terus lanjutkan belajar untuk naik ke level berikutnya!
                    </p>
                </div>
            </div>

            {{-- Kartu Progress --}}
            @php
                $total = $summary['total'] ?? 0;
                $completed = $summary['completed'] ?? 0;
                $avgScore = $summary['avg_score'] ?? 0;
                $percent = $total ? round(($completed / $total) * 100) : 0;
            @endphp

            <div class="col-md-6">
                <div class="p-4 bg-white rounded shadow-sm">
                    <h6 class="fw-bold text-secondary mb-2">Kuis Diselesaikan</h6>
                    <h4 class="fw-bold text-success">
                        {{ $completed }} / {{ $total }} Kuis
                    </h4>

                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $percent }}%;"></div>
                    </div>

                    <p class="text-muted small mt-2 mb-0">
                        Rata-rata nilai kuis:
                        <strong>{{ $avgScore }} / 100</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat Kuis --}}
        <div class="mt-5">
            <h6 class="fw-bold text-secondary mb-3">Riwayat Kuis Kamu</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Materi</th>
                            <th>Status</th>
                            <th>Nilai Kuis</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasil as $row)
                            @php
                                // Karena dari API, bentuknya array
                                $materiJudul = $row['kuis']['materi']['judul'] ?? '-';
                                $status = $row['desc'] ?? '-';
                                $skor = $row['skor'] ?? 0;
                                $tanggalRaw = $row['tanggal'] ?? null;
                                $tanggal = $tanggalRaw
                                    ? \Carbon\Carbon::parse($tanggalRaw)->translatedFormat('d M Y')
                                    : '-';
                            @endphp

                            <tr>
                                <td>{{ $materiJudul }}</td>
                                <td>
                                    @if ($status === 'Lulus')
                                        <span class="badge bg-success">Lulus</span>
                                    @elseif ($status === 'Tidak lulus')
                                        <span class="badge bg-danger">Tidak lulus</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $status }}</span>
                                    @endif
                                </td>
                                <td>{{ $skor }} / 100</td>
                                <td>{{ $tanggal }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada hasil kuis yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
