@extends('layouts.admin')

@section('title', 'Template Sertifikat')

@section('content')
    <div class="container py-5">
        {{-- NAVIGASI --}}
        <a href="/admin/dashboard" class="text-primary d-block mb-3">
            ← Kembali ke Dashboard
        </a>

        <h2 class="fw-bold mb-2">🏅 Template Sertifikat</h2>
        <p class="text-muted mb-4">
            Pilih Paket &amp; Bahasa, lalu tentukan teks sertifikat yang akan dipakai otomatis saat member lulus kursus
            tersebut.
            Background sertifikat akan digunakan secara default (otomatis).
        </p>

        {{-- FILTER: PAKET & BAHASA --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="fw-semibold">Paket</label>
                        <select id="selectPaket" class="form-select">
                            <option value="">-- Pilih Paket --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-semibold">Bahasa</label>
                        <select id="selectBahasa" class="form-select">
                            <option value="">-- Pilih Bahasa --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">
                            Template sertifikat disimpan per kombinasi Paket + Bahasa (per course).
                        </small>
                        <small id="courseInfo" class="text-secondary d-block mt-1"></small>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM + PREVIEW --}}
        <div class="row g-4">
            {{-- FORM TEMPLATE --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <strong>📄 Form Template Sertifikat</strong>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="currentTemplateId">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Sertifikat</label>
                            <input id="tempJudul" type="text" class="form-control"
                                placeholder="Contoh: Sertifikat Kelulusan">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi Sertifikat</label>
                            <textarea id="tempDeskripsi" class="form-control" rows="3"
                                placeholder="Contoh: Diberikan kepada @{{ nama_peserta }} karena telah menyelesaikan kursus @{{ nama_kursus }}."></textarea>
                            <div class="form-text">
                                Kamu boleh pakai placeholder seperti
                                <code>&#123;&#123;nama_peserta&#125;&#125;</code> atau
                                <code>&#123;&#123;nama_kursus&#125;&#125;</code>
                                sebagai variabel yang nanti diisi otomatis.
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Penandatangan</label>
                                <input id="tempTtdNama" type="text" class="form-control"
                                    placeholder="Contoh: Andi Saputra">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jabatan Penandatangan (opsional)</label>
                                <input id="tempTtdJabatan" type="text" class="form-control"
                                    placeholder="Contoh: Direktur Akademik">
                            </div>
                        </div>

                        <button id="btnSaveTemplate" class="btn btn-primary mt-3" disabled>
                            Simpan Template
                        </button>

                        <small id="templateStatus" class="d-block mt-2 text-muted"></small>
                    </div>
                </div>
            </div>

            {{-- PREVIEW --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <strong>🔍 Preview Sertifikat</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-3">
                            <div id="preview-cert" class="position-relative rounded shadow text-center text-dark"
                                style="
                                    width: 280px;
                                    height: 400px;
                                    background: radial-gradient(circle at top left, #e0f2fe, #e5e7eb);
                                    border: 4px solid #1f2937;
                                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                                 ">

                                <div class="position-absolute w-100" style="top: 30px;">
                                    <div id="prevJudul" class="fw-bold" style="font-size: 1.1rem;"></div>
                                </div>

                                <div class="position-absolute w-100" style="top: 110px;">
                                    <div class="text-muted mb-1" style="font-size: 0.8rem;">Diberikan kepada</div>
                                    <div id="prevNamaPeserta" class="fw-semibold" style="font-size: 1.1rem;">
                                        Nama Peserta
                                    </div>
                                </div>

                                <div class="position-absolute w-100 px-3" style="top: 180px;">
                                    <div id="prevDeskripsi" style="font-size: 0.8rem;"></div>
                                </div>

                                <div class="position-absolute w-100" style="bottom: 70px;">
                                    <div id="prevTtdNama" class="fw-semibold" style="font-size: 0.85rem;"></div>
                                    <div id="prevTtdJabatan" class="text-muted" style="font-size: 0.75rem;"></div>
                                </div>

                                <div class="position-absolute w-100 text-muted" style="bottom: 15px; font-size: 0.7rem;">
                                    <span id="prevTanggal"></span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="downloadPNG()">
                                Download PNG (Preview)
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="downloadPDF()">
                                Download PDF (Preview)
                            </button>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Ini hanya preview di sisi admin. Nanti ketika member lulus (progress = 3), sistem akan
                            memakai template ini untuk membuat sertifikat otomatis dan simpan ke tabel
                            <code>sertifikat</code>.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- html2canvas + jsPDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        const api = axios.create({
            baseURL: 'http://127.0.0.1:8000/api',
            headers: {
                Accept: 'application/json'
            },
        });

        const selectPaket = document.getElementById('selectPaket');
        const selectBahasa = document.getElementById('selectBahasa');
        const courseInfo = document.getElementById('courseInfo');
        const btnSave = document.getElementById('btnSaveTemplate');
        const statusText = document.getElementById('templateStatus');
        const inputId = document.getElementById('currentTemplateId');

        const fJudul = document.getElementById('tempJudul');
        const fDeskripsi = document.getElementById('tempDeskripsi');
        const fTtdNama = document.getElementById('tempTtdNama');
        const fTtdJabatan = document.getElementById('tempTtdJabatan');

        let currentCourse = null;
        let currentTemplate = null;

        // ============================
        // LOAD PAKET & BAHASA (PAKAI ROUTE ADMIN)
        // ============================
        async function loadPakets() {
            try {
                const res = await api.get('/admin/paket');
                const data = Array.isArray(res.data.data) ? res.data.data : res.data;

                selectPaket.innerHTML = '<option value="">-- Pilih Paket --</option>';
                data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.nama_paket || ('Paket #' + p.id);
                    selectPaket.appendChild(opt);
                });
            } catch (e) {
                console.error('Error load paket', e.response || e);
                alert('Gagal memuat paket');
            }
        }

        async function loadBahasas() {
            try {
                const res = await api.get('/admin/bahasa');
                const data = Array.isArray(res.data.data) ? res.data.data : res.data;

                selectBahasa.innerHTML = '<option value="">-- Pilih Bahasa --</option>';
                data.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.textContent = b.nama_bahasa || ('Bahasa #' + b.id);
                    selectBahasa.appendChild(opt);
                });
            } catch (e) {
                console.error('Error load bahasa', e.response || e);
                alert('Gagal memuat bahasa');
            }
        }

        // ============================
        // LOAD TEMPLATE UNTUK COURSE (PAKET+BAHASA)
        // ============================
        async function loadTemplateForCourse() {
            const idPaket = selectPaket.value;
            const idBahasa = selectBahasa.value;

            courseInfo.textContent = '';
            statusText.textContent = '';
            btnSave.disabled = true;
            inputId.value = '';
            currentCourse = null;
            currentTemplate = null;

            // reset form
            fJudul.value = '';
            fDeskripsi.value = '';
            fTtdNama.value = '';
            fTtdJabatan.value = '';
            updatePreview();

            if (!idPaket || !idBahasa) {
                return;
            }

            try {
                const res = await api.get('/admin/template-sertifikat', {
                    params: {
                        id_paket: idPaket,
                        id_bahasa: idBahasa
                    }
                });

                currentCourse = res.data.course;
                currentTemplate = res.data.data;

                courseInfo.textContent =
                    `Kursus: ${currentCourse?.bahasa?.nama_bahasa || '-'} - ${currentCourse?.paket?.nama_paket || '-'}`;

                if (currentTemplate) {
                    inputId.value = currentTemplate.id;
                    fJudul.value = currentTemplate.judul || '';
                    fDeskripsi.value = currentTemplate.deskripsi || '';
                    fTtdNama.value = currentTemplate.nama_penandatangan || '';
                    fTtdJabatan.value = currentTemplate.jabatan_penandatangan || '';

                    statusText.textContent =
                        'Template sertifikat sudah tersimpan. Mengubah form ini akan meng-update template.';
                } else {
                    statusText.textContent =
                        'Belum ada template sertifikat untuk kursus ini. Silakan isi form lalu simpan.';
                }

                btnSave.disabled = false;
                updatePreview();

            } catch (e) {
                // 404 dari controller = kursus / template belum ada
                if (e.response && e.response.status === 404) {
                    const resp = e.response.data;
                    if (resp.course) {
                        currentCourse = resp.course;
                        courseInfo.textContent =
                            `Kursus: ${currentCourse?.bahasa?.nama_bahasa || '-'} - ${currentCourse?.paket?.nama_paket || '-'}`;
                    } else {
                        courseInfo.textContent = 'Kursus untuk kombinasi ini belum ada.';
                    }
                    statusText.textContent =
                        'Belum ada template sertifikat untuk kursus ini. Silakan isi form lalu simpan.';
                    btnSave.disabled = false;
                    updatePreview();
                } else {
                    console.error('Error load template', e.response || e);
                    alert(e.response?.data?.message || 'Gagal memuat template sertifikat');
                }
            }
        }

        // ============================
        // SIMPAN TEMPLATE
        // ============================
        btnSave.addEventListener('click', async () => {
            const idPaket = selectPaket.value;
            const idBahasa = selectBahasa.value;

            const judul = fJudul.value.trim();
            const desk = fDeskripsi.value.trim();
            const ttdNama = fTtdNama.value.trim();
            const ttdJab = fTtdJabatan.value.trim();

            if (!idPaket || !idBahasa) {
                alert('Pilih Paket dan Bahasa terlebih dahulu.');
                return;
            }
            if (!judul || !ttdNama) {
                alert('Judul sertifikat dan nama penandatangan wajib diisi.');
                return;
            }

            try {
                const res = await api.post('/admin/template-sertifikat', {
                    id_paket: idPaket,
                    id_bahasa: idBahasa,
                    judul: judul,
                    deskripsi: desk,
                    nama_penandatangan: ttdNama,
                    jabatan_penandatangan: ttdJab || null,
                });

                currentCourse = res.data.course;
                currentTemplate = res.data.data;
                inputId.value = currentTemplate.id;

                statusText.textContent = 'Template sertifikat berhasil disimpan / diupdate.';
                updatePreview();
            } catch (e) {
                console.error('Error save template', e.response || e);
                alert(e.response?.data?.message || 'Gagal menyimpan template sertifikat');
            }
        });

        // ============================
        // PREVIEW (LIVE)
        // ============================
        function updatePreview() {
            document.getElementById('prevJudul').innerText = fJudul.value || 'Judul Sertifikat';
            document.getElementById('prevNamaPeserta').innerText = 'Nama Peserta';
            document.getElementById('prevDeskripsi').innerText = fDeskripsi.value ||
                'Deskripsi sertifikat akan tampil di sini.';
            document.getElementById('prevTtdNama').innerText = fTtdNama.value || 'Nama Penandatangan';
            document.getElementById('prevTtdJabatan').innerText = fTtdJabatan.value || '';

            const d = new Date().toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric"
            });
            document.getElementById('prevTanggal').innerText = 'Diterbitkan pada: ' + d;
        }

        fJudul.addEventListener('input', updatePreview);
        fDeskripsi.addEventListener('input', updatePreview);
        fTtdNama.addEventListener('input', updatePreview);
        fTtdJabatan.addEventListener('input', updatePreview);

        // ============================
        // DOWNLOAD PREVIEW PNG / PDF
        // ============================
        window.downloadPNG = function() {
            const cert = document.getElementById("preview-cert");
            html2canvas(cert).then(canvas => {
                const link = document.createElement("a");
                link.download = "sertifikat-preview.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }

        window.downloadPDF = function() {
            const {
                jsPDF
            } = window.jspdf;
            const cert = document.getElementById("preview-cert");

            html2canvas(cert).then(canvas => {
                const img = canvas.toDataURL("image/png");
                const pdf = new jsPDF("portrait", "pt", [280, 400]);
                pdf.addImage(img, "PNG", 0, 0, 280, 400);
                pdf.save("sertifikat-preview.pdf");
            });
        }

        // ============================
        // INIT
        // ============================
        selectPaket.addEventListener('change', loadTemplateForCourse);
        selectBahasa.addEventListener('change', loadTemplateForCourse);

        document.addEventListener('DOMContentLoaded', async () => {
            await loadPakets();
            await loadBahasas();
        });
    </script>
@endpush
