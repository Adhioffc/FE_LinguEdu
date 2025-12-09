@extends('member.dashboard.main')

@section('title', 'Sertifikat Kelulusan')

@section('style')
    <style>
        .cert-wrapper {
            background: radial-gradient(circle at top, #e0f2fe 0, #f8fafc 45%, #f1f5f9 100%);
        }

        .cert-card {
            background: #ffffff;
            border-radius: 28px;
            box-shadow:
                0 20px 45px rgba(15, 23, 42, 0.12),
                0 0 0 1px rgba(148, 163, 184, 0.25);
            position: relative;
            overflow: hidden;
        }

        .cert-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 28px;
            padding: 2px;
            background: linear-gradient(135deg, #2563eb, #22c55e, #06b6d4);
            -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.9;
        }

        .cert-inner {
            position: relative;
            z-index: 1;
        }

        .cert-badge {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 30% 20%, #facc15, #f97316);
            color: white;
            box-shadow: 0 10px 30px rgba(248, 181, 0, 0.55);
        }

        .cert-name {
            font-size: 2.2rem;
            letter-spacing: 0.03em;
        }

        .cert-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
        }

        .cert-footer-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5f5, transparent);
        }

        @media print {
            body {
                background: #ffffff !important;
            }

            .no-print {
                display: none !important;
            }

            .cert-wrapper {
                padding: 0 !important;
            }

            .cert-card {
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="cert-wrapper min-h-[80vh] py-10">
        <div class="max-w-5xl mx-auto px-4">

            {{-- HEADER + TOMBOL DOWNLOAD --}}
            <div class="flex items-center justify-between mb-6 no-print">
                <div>
                    <h1 class="text-xl font-semibold text-slate-800">Sertifikat Kelulusan</h1>
                    <p class="text-sm text-slate-500">
                        Simpan sertifikat ini sebagai bukti kelulusan ujian sertifikasi LinguEdu.
                    </p>
                </div>

                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-indigo-500 text-indigo-600 bg-white text-sm font-semibold shadow-sm hover:bg-indigo-50">
                    <i class="bi bi-download"></i>
                    <span>Download / Cetak PDF</span>
                </button>
            </div>

            {{-- KARTU SERTIFIKAT --}}
            <div class="cert-card">
                <div class="cert-inner px-8 py-10 md:px-14 md:py-12">

                    {{-- HEADER ATAS --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="cert-badge">
                                <i class="bi bi-patch-check-fill text-3xl"></i>
                            </div>
                            <div>
                                <div class="cert-label text-sky-600 font-semibold mb-1">
                                    Certificate of Achievement
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900">
                                    PT Linguedu
                                </h2>
                                <p class="text-sm text-slate-500 mt-1">
                                    Sertifikat resmi kelulusan ujian sertifikasi LinguEdu.
                                </p>
                            </div>
                        </div>

                        <div class="text-right text-xs text-slate-500 space-y-1">
                            @php
                                $noSertif = 'LNG-' . str_pad($hasil->id_hasil, 5, '0', STR_PAD_LEFT);
                            @endphp
                            <p><span class="font-semibold text-slate-700">No. Sertifikat:</span> {{ $noSertif }}</p>
                            <p>
                                <span class="font-semibold text-slate-700">Tanggal Terbit:</span>
                                {{ \Carbon\Carbon::parse($hasil->tanggal)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- NAMA PESERTA --}}
                    <div class="text-center mb-8">
                        <p class="cert-label text-slate-500 mb-3">Diberikan kepada</p>
                        <div class="cert-name font-extrabold text-slate-900 mb-2">
                            {{ $user->name }}
                        </div>
                        <p class="text-sm text-slate-500 max-w-xl mx-auto">
                            Atas keberhasilan menyelesaikan seluruh tahapan pembelajaran dan lulus ujian sertifikasi
                            yang diselenggarakan oleh <strong>PT Linguedu</strong>.
                        </p>
                    </div>

                    {{-- INFO COURSE --}}
                    <div class="grid md:grid-cols-3 gap-6 mb-10">
                        <div class="bg-slate-50 rounded-2xl px-5 py-4 border border-slate-100">
                            <p class="cert-label text-slate-500 mb-1">Kursus</p>
                            <p class="font-semibold text-slate-900">
                                {{ $hasil->nama_bahasa ?? 'Bahasa' }} – {{ $hasil->nama_paket ?? 'Paket' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl px-5 py-4 border border-slate-100">
                            <p class="cert-label text-slate-500 mb-1">Skor Akhir</p>
                            <p class="font-semibold text-emerald-600 text-lg">
                                {{ $hasil->skor }} / 100
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">Status: <strong>{{ $hasil->status }}</strong></p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl px-5 py-4 border border-slate-100">
                            <p class="cert-label text-slate-500 mb-1">Penyelenggara</p>
                            <p class="font-semibold text-slate-900">PT Linguedu</p>
                            <p class="text-xs text-slate-500 mt-0.5">Certified by LinguEdu Learning Center</p>
                        </div>
                    </div>

                    {{-- FOOTER TANDA TANGAN --}}
                    <div class="mt-6">
                        <div class="cert-footer-line mb-6"></div>

                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                            <div>
                                <p class="text-xs text-slate-400 mb-1">Dikeluarkan oleh</p>
                                <p class="font-semibold text-slate-800">PT Linguedu</p>
                                <p class="text-xs text-slate-500">Jakarta, Indonesia</p>
                            </div>

                            <div class="text-right">
                                <p class="text-xs text-slate-400 mb-10">Tanda tangan & stempel resmi</p>
                                <div class="inline-flex flex-col items-center">
                                    <div class="w-40 border-b border-slate-300 mb-1"></div>
                                    <p class="text-xs font-semibold text-slate-700">Direktur Program</p>
                                    <p class="text-[11px] text-slate-500">PT Linguedu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- KEMBALI KE SERTIFIKASI (HANYA DI LAYAR) --}}
            <div class="no-print mt-6 text-center">
                <a href="{{ route('dashboard.sertifikasi') }}"
                   class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Halaman Sertifikasi</span>
                </a>
            </div>
        </div>
    </div>
@endsection
