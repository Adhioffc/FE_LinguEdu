@extends('member.dashboard.main')
@section('title', 'Laporan Progres - LinguEdu')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
    <h3 class="fw-bold mb-4 text-primary">📊 Laporan Progres Belajar</h3>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="p-4 bg-white rounded shadow-sm">
                <h6 class="fw-bold text-secondary mb-2">Level Kamu Saat Ini</h6>
                <h4 class="fw-bold text-dark">Level 1 - Beginner</h4>
                <p class="text-muted small mb-0">Terus lanjutkan belajar untuk naik ke Level 2!</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-4 bg-white rounded shadow-sm">
                <h6 class="fw-bold text-secondary mb-2">Materi Diselesaikan</h6>
                <h4 class="fw-bold text-success">3 / 10 Modul</h4>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 30%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h6 class="fw-bold text-secondary mb-3">Detail Aktivitas Terakhir</h6>
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
                    <tr>
                        <td>Introduction to Programming</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>90 / 100</td>
                        <td>10 Nov 2025</td>
                    </tr>
                    <tr>
                        <td>Variabel dan Operator</td>
                        <td><span class="badge bg-warning text-dark">Proses</span></td>
                        <td>-</td>
                        <td>Belum</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
