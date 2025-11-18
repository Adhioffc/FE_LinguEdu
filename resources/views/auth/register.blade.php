@extends('layouts.main')
@section('title', 'Registrasi - LinguEdu')

@section('content')
    <div class="min-h-screen bg-blue-50 py-10 px-4 flex flex-col items-center">

        <!-- HEADER -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Gabung ke LinguEdu</h1>
            <p class="text-gray-600">Ikuti langkah-langkah sederhana untuk mulai belajar bahasa pilihanmu</p>

            <div class="flex justify-center items-center mt-6 space-x-4">
                <div id="circle1"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-semibold">1
                </div>
                <div class="w-10 h-1 bg-gray-300"></div>
                <div id="circle2"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-semibold">
                    2</div>
                <div class="w-10 h-1 bg-gray-300"></div>
                <div id="circle3"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-semibold">
                    3</div>
                <div class="w-10 h-1 bg-gray-300"></div>
                <div id="circle4"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-semibold">
                    4</div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-5xl transition-all duration-500">

            <!-- STEP 1: PILIH PAKET -->
            <div id="step1">
                <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">Langkah 1: Pilih Paket Belajarmu</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="paket-card border-2 border-blue-200 rounded-xl p-6 hover:border-blue-400 transition-all transform hover:-translate-y-2 hover:shadow-xl cursor-pointer"
                        data-paket="Basic" data-harga="150000">
                        <h3 class="text-2xl font-bold mb-4 text-blue-600">Basic</h3>
                        <ul class="space-y-2 text-gray-700 text-sm mb-6">
                            <li>🔹 8x Modul Video Interaktif</li>
                            <li>🔹 Latihan Soal Setiap Bab</li>
                            <li>🔹 Sertifikat Dasar (CEFR A1–A2)</li>
                            <li>🔹 Akses 3 bulan</li>
                        </ul>
                        <div class="text-center font-semibold text-blue-700">Rp150.000</div>
                    </div>

                    <div class="paket-card border-2 border-yellow-400 bg-yellow-50 rounded-xl p-6 hover:border-yellow-500 transition-all transform hover:-translate-y-2 hover:shadow-xl cursor-pointer"
                        data-paket="Intermediate" data-harga="300000">
                        <div
                            class="absolute -top-4 right-4 bg-yellow-400 text-gray-900 px-4 py-1 rounded-full font-bold text-sm animate-bounce">
                            Terfavorit!</div>
                        <h3 class="text-2xl font-bold mb-4 text-yellow-700">Intermediate</h3>
                        <ul class="space-y-2 text-gray-700 text-sm mb-6">
                            <li>⭐ 12x Modul Terstruktur + Challenge</li>
                            <li>⭐ Sertifikat B1–B2</li>
                            <li>⭐ Durasi akses 6 bulan</li>
                        </ul>
                        <div class="text-center font-semibold text-yellow-700">Rp300.000</div>
                    </div>

                    <div class="paket-card border-2 border-blue-300 rounded-xl p-6 hover:border-blue-500 transition-all transform hover:-translate-y-2 hover:shadow-xl cursor-pointer"
                        data-paket="Advanced" data-harga="500000">
                        <h3 class="text-2xl font-bold mb-4 text-blue-700">Advanced + Sertifikasi</h3>
                        <ul class="space-y-2 text-gray-700 text-sm mb-6">
                            <li>🏅 20x Modul Advanced + Tes Akhir</li>
                            <li>🏅 Sertifikat C1–C2</li>
                            <li>🏅 Akses 1 tahun</li>
                        </ul>
                        <div class="text-center font-semibold text-blue-700">Rp500.000</div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: PILIH BAHASA -->
            <div id="step2" class="hidden">
                <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">Langkah 2: Pilih Bahasa yang Ingin
                    Dipelajari</h2>
                <p id="paketSubtitle" class="text-center text-gray-600 mb-10">Pilih bahasa sesuai paketmu</p>
                <div id="bahasaContainer" class="grid md:grid-cols-3 gap-8"></div>
                <div class="flex justify-between mt-10">
                    <button id="backToPaket"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Kembali</button>
                </div>
            </div>

            <!-- STEP 3: DATA & INVOICE -->
            <div id="step3" class="hidden">
                <h2 class="text-3xl font-extrabold text-center mb-6 text-gray-800">💳 Langkah 3: Isi Data & Lakukan
                    Pembayaran</h2>
                <p class="text-center text-gray-600 mb-8">Lengkapi identitasmu dan selesaikan pembayaran untuk mengaktifkan
                    akun LinguEdu.</p>

                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl shadow-xl p-6 mb-10">
                    <h3 class="text-xl font-bold text-blue-700 mb-3">📄 Invoice Pembayaran</h3>
                    <div class="bg-white border border-blue-100 rounded-xl p-5 shadow-sm mb-5">
                        <div class="flex justify-between mb-2"><span class="text-gray-700">Nomor Invoice</span><span
                                class="font-semibold text-blue-700">#INV-{{ date('ymd') }}{{ rand(100, 999) }}</span></div>
                        <div class="flex justify-between mb-2"><span class="text-gray-700">📦 Paket</span><span
                                id="rekapPaket" class="font-medium">-</span></div>
                        <div class="flex justify-between mb-2"><span class="text-gray-700">🌐 Bahasa</span><span
                                id="rekapBahasa" class="font-medium">-</span></div>
                        <div class="flex justify-between mb-2"><span class="text-gray-700">💰 Total Pembayaran</span><span
                                id="rekapHarga" class="font-bold text-blue-700 text-lg">-</span></div>
                        <div class="flex justify-between mb-2"><span class="text-gray-700">🏦 No. Rekening</span><span
                                class="text-gray-800 font-medium">BCA 1234567890 a/n LinguEdu Academy</span></div>
                        <div class="flex justify-between mb-1"><span class="text-gray-700">📅 Tanggal Jatuh
                                Tempo</span><span>{{ date('d M Y', strtotime('+1 day')) }}</span></div>
                    </div>

                    <div class="text-center">
                        <p class="text-gray-700 font-medium mb-3">Atau gunakan pembayaran cepat via QRIS:</p>
                        <div class="bg-white inline-block p-4 rounded-xl shadow">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=LinguEduPayment"
                                alt="QRIS Payment" class="mx-auto rounded-lg border p-2">
                        </div>
                        <p class="text-xs text-gray-500 mt-3 italic">Scan QR menggunakan OVO, DANA, GoPay, ShopeePay atau
                            m-banking lainnya.</p>
                    </div>
                </div>

                <form id="registerForm" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input id="namaField" type="text" placeholder="Nama lengkap"
                                class="w-full border rounded-lg px-4 py-3 bg-gray-50 focus:ring-2 focus:ring-blue-400"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="emailField" type="email" placeholder="Alamat email aktif"
                                class="w-full border rounded-lg px-4 py-3 bg-gray-50 focus:ring-2 focus:ring-blue-400"
                                required>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="passwordField" type="password" placeholder="Minimal 8 karakter"
                                class="w-full border rounded-lg px-4 py-3 bg-gray-50 focus:ring-2 focus:ring-blue-400"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                            <input id="buktiField" type="file" accept="image/*" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                     file:rounded-full file:border-0 file:text-sm file:font-semibold
                     file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maks 2 MB</p>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button id="backToLang" type="button"
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">⬅ Kembali</button>
                        <button id="previewBtn" type="button" disabled
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow disabled:opacity-50">✅
                            Konfirmasi & Buat Akun</button>
                    </div>
                </form>
            </div>

            <!-- STEP 4: SUKSES -->
            <div id="step4" class="hidden text-center">
                <div class="flex flex-col items-center">
                    <div class="relative mb-6">
                        <div class="bg-green-100 w-24 h-24 flex items-center justify-center rounded-full shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div
                            class="absolute -bottom-3 right-0 bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                            Verified</div>
                    </div>

                    <h2 class="text-3xl font-extrabold text-green-700 mb-2">Akun Berhasil Dibuat!</h2>
                    <p class="text-gray-600 mb-8">Selamat datang di LinguEdu 🎉 <br>Mulailah perjalanan belajarmu dan raih
                        sertifikat resmi.</p>

                    <div
                        class="bg-gradient-to-r from-green-50 to-white border border-green-200 rounded-2xl shadow-lg w-full max-w-md p-6 text-left">
                        <h3 class="text-lg font-bold text-green-700 mb-4">📘 Detail Akun Kamu</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>👤 Nama:</strong> <span id="successNama"></span></p>
                            <p><strong>📦 Paket:</strong> <span id="successPaket"></span></p>
                            <p><strong>🌐 Bahasa:</strong> <span id="successBahasa"></span></p>
                            <p><strong>📅 Masa Aktif:</strong> <span id="successExpiry"></span></p>
                        </div>
                        <div class="mt-5 text-center">
                            <span
                                class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">Sertifikat
                                Aktif</span>
                        </div>
                    </div>

                    <div class="flex justify-center mt-8 space-x-4">
                        <button id="editData" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">✏️
                            Edit Data</button>
                        <a href="{{ route('login.simulasi') }}"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">➡ Ke Halaman
                            Login</a>
                    </div>
                </div>
            </div>
        </div>
@endsection
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const steps = [step1, step2, step3, step4] = ['step1', 'step2', 'step3', 'step4'].map(id => document.getElementById(id));
                const circles = ['circle1', 'circle2', 'circle3', 'circle4'].map(id => document.getElementById(id));
                const bahasaContainer = document.getElementById('bahasaContainer');
                const paketSubtitle = document.getElementById('paketSubtitle');
                const inputPaket = {}, rekap = {};
                ['rekapPaket', 'rekapBahasa', 'rekapHarga'].forEach(id => rekap[id] = document.getElementById(id));

                const bahasaPerPaket = {
                    "Basic": [
                        { flag: "https://flagcdn.com/w40/gb.png", nama: "Bahasa Inggris Dasar", deskripsi: "Percakapan sehari-hari dan kosakata dasar.", level: "CEFR A1–A2", durasi: "3 bulan", pengajar: { nama: "Emily Clark, B.Ed.", info: "TESOL Certified | British Council" }, color: "blue" },
                        { flag: "https://flagcdn.com/w40/jp.png", nama: "Bahasa Jepang Dasar", deskripsi: "Huruf Hiragana, Katakana, dan salam dasar.", level: "JLPT N5–N4", durasi: "3 bulan", pengajar: { nama: "Hana Suzuki, S.Pd.", info: "JLPT N2 | Osaka Education Center" }, color: "red" },
                        { flag: "https://flagcdn.com/w40/kr.png", nama: "Bahasa Korea Dasar", deskripsi: "Pelafalan Hangul dan percakapan ringan.", level: "TOPIK I", durasi: "3 bulan", pengajar: { nama: "Kim Soo-yeon, S.S.", info: "TOPIK IV | Hankuk University" }, color: "pink" }
                    ],
                    "Intermediate": [
                        { flag: "https://flagcdn.com/w40/gb.png", nama: "Bahasa Inggris Intermediate", deskripsi: "Grammar kompleks, writing & speaking profesional.", level: "CEFR B1–B2", durasi: "6 bulan", pengajar: { nama: "Joshua Smith, M.Ed.", info: "TESOL Certified | Cambridge University" }, color: "yellow" },
                        { flag: "https://flagcdn.com/w40/jp.png", nama: "Bahasa Jepang Intermediate", deskripsi: "Latihan kanji dan percakapan bisnis.", level: "JLPT N3–N2", durasi: "6 bulan", pengajar: { nama: "Aiko Tanaka, M.A.", info: "JLPT N1 | Waseda University" }, color: "red" },
                        { flag: "https://flagcdn.com/w40/kr.png", nama: "Bahasa Korea Intermediate", deskripsi: "Struktur kalimat kompleks & ungkapan budaya pop.", level: "TOPIK III–IV", durasi: "6 bulan", pengajar: { nama: "Lee Min-ho, M.Pd.", info: "TOPIK VI | Yonsei University" }, color: "pink" }
                    ],
                    "Advanced": [
                        { flag: "https://flagcdn.com/w40/gb.png", nama: "Bahasa Inggris Advanced + Sertifikasi", deskripsi: "Persiapan TOEFL, IELTS, akademik global.", level: "CEFR C1–C2", durasi: "12 bulan", pengajar: { nama: "Dr. Catherine Hall", info: "PhD in Applied Linguistics | Oxford" }, color: "blue" },
                        { flag: "https://flagcdn.com/w40/jp.png", nama: "Bahasa Jepang Advanced + Sertifikasi", deskripsi: "Persiapan JLPT N1 & wawancara kerja Jepang.", level: "JLPT N2–N1", durasi: "12 bulan", pengajar: { nama: "Takashi Watanabe, Ph.D.", info: "Profesor | Kyoto University" }, color: "red" },
                        { flag: "https://flagcdn.com/w40/kr.png", nama: "Bahasa Korea Advanced + Sertifikasi", deskripsi: "Persiapan TOPIK V–VI & komunikasi akademik.", level: "TOPIK V–VI", durasi: "12 bulan", pengajar: { nama: "Dr. Park Ji-hyun", info: "SNU Graduate | King Sejong Institute" }, color: "pink" }
                    ]
                };

                // Navigasi antar step
                function goToStep(n) {
                    steps.forEach((s, i) => s.classList.toggle('hidden', i !== n));
                    circles.forEach((c, i) => {
                        if (i <= n) { c.classList.add('bg-blue-600', 'text-white'); c.classList.remove('bg-gray-300', 'text-gray-600'); }
                        else { c.classList.add('bg-gray-300', 'text-gray-600'); c.classList.remove('bg-blue-600', 'text-white'); }
                    });
                }

                document.querySelectorAll('.paket-card').forEach(card => {
                    card.addEventListener('click', () => {
                        const paket = card.dataset.paket, harga = card.dataset.harga;
                        inputPaket.value = paket;
                        rekap.rekapPaket.textContent = paket;
                        rekap.rekapHarga.textContent = `Rp${Number(harga).toLocaleString('id-ID')}`;
                        renderBahasa(paket);
                        goToStep(1);
                    });
                });

                function renderBahasa(paket) {
                    bahasaContainer.innerHTML = "";
                    (bahasaPerPaket[paket] || []).forEach(lang => {
                        const div = document.createElement('div');
                        div.className = `border-2 border-${lang.color}-200 bg-${lang.color}-50 rounded-2xl p-6 hover:border-${lang.color}-400 hover:shadow-xl transition cursor-pointer`;
                        div.innerHTML = `
            <div class="flex items-center space-x-3 mb-4"><img src="${lang.flag}" class="rounded shadow"><h3 class="text-xl font-bold text-${lang.color}-700">${lang.nama}</h3></div>
            <p class="text-gray-700 text-sm mb-3">${lang.deskripsi}</p>
            <ul class="text-gray-600 text-sm mb-4"><li>📚 Level: ${lang.level}</li><li>⏰ Durasi: ${lang.durasi}</li></ul>
            <div class="border-t pt-3"><h4 class="font-semibold">${lang.pengajar.nama}</h4><p class="text-xs text-gray-500">${lang.pengajar.info}</p></div>`;
                        div.onclick = () => { rekap.rekapBahasa.textContent = lang.nama; goToStep(2); };
                        bahasaContainer.appendChild(div);
                    });
                }

                document.getElementById('backToPaket').onclick = () => goToStep(0);
                document.getElementById('backToLang').onclick = () => goToStep(1);
                document.getElementById('editData').onclick = () => goToStep(2);

                const previewBtn = document.getElementById('previewBtn');
                ['namaField', 'emailField', 'passwordField', 'buktiField'].forEach(id => {
                    const el = document.getElementById(id);
                    el.addEventListener('input', validateForm);
                    el.addEventListener('change', validateForm);
                });
                function validateForm() {
                    const allFilled = ['namaField', 'emailField', 'passwordField', 'buktiField'].every(id => {
                        const e = document.getElementById(id);
                        return e && e.value.trim() !== "";
                    });
                    previewBtn.disabled = !allFilled;
                }

                previewBtn.onclick = () => {
                    const expiry = new Date(); expiry.setFullYear(expiry.getFullYear() + 1);
                    document.getElementById('successNama').textContent = document.getElementById('namaField').value;
                    document.getElementById('successPaket').textContent = rekap.rekapPaket.textContent;
                    document.getElementById('successBahasa').textContent = rekap.rekapBahasa.textContent;
                    document.getElementById('successExpiry').textContent = expiry.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
                    goToStep(3);
                }

                goToStep(0);
            });
        </script>
    @endpush
