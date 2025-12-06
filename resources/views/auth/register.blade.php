@extends('layouts.main')
@section('title', 'Registrasi - LinguEdu')

@section('content')
    <div class="min-h-screen bg-blue-50 py-10 px-4 flex flex-col items-center">

        {{-- HEADER --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Gabung ke LinguEdu</h1>
            <p class="text-gray-600">Ikuti langkah-langkah sederhana untuk mulai belajar bahasa pilihanmu</p>

            <div class="flex justify-center items-center mt-6 space-x-4">
                <div id="circle1"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-semibold">
                    1
                </div>
                <div class="w-10 h-1 bg-gray-300"></div>
                <div id="circle2"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-semibold">
                    2
                </div>
                <div class="w-10 h-1 bg-gray-300"></div>
                <div id="circle3"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-semibold">
                    3
                </div>
                <div class="w-10 h-1 bg-gray-300"></div>
                <div id="circle4"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-semibold">
                    4
                </div>
            </div>
        </div>

        {{-- CARD WRAPPER --}}
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-5xl transition-all duration-500">

            {{-- STEP 1: PILIH PAKET (DARI DB) --}}
            <div id="step1">
                <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">
                    Langkah 1: Pilih Paket Belajarmu
                </h2>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($paket as $row)
                        @php
                            $isFavorite = $row['nama_paket'] === 'Intermediate';
                        @endphp

                        <div class="paket-card relative border-2 rounded-xl p-6 hover:-translate-y-2 hover:shadow-xl cursor-pointer transition-all
                            {{ $isFavorite ? 'border-yellow-400 bg-yellow-50 hover:border-yellow-500' : 'border-blue-200 hover:border-blue-400' }}"
                            data-id_paket="{{ $row['id'] }}" data-paket="{{ $row['nama_paket'] }}"
                            data-harga="{{ $row['harga'] }}">
                            @if ($isFavorite)
                                <div
                                    class="absolute -top-4 right-4 bg-yellow-400 text-gray-900 px-4 py-1 rounded-full font-bold text-sm animate-bounce">
                                    Terfavorit!
                                </div>
                            @endif

                            <h3 class="text-2xl font-bold mb-4 {{ $isFavorite ? 'text-yellow-700' : 'text-blue-600' }}">
                                {{ $row['nama_paket'] }}
                            </h3>

                            <p class="text-gray-700 text-sm mb-6">
                                {{ $row['desc'] }}
                            </p>

                            <div class="text-center font-semibold {{ $isFavorite ? 'text-yellow-700' : 'text-blue-700' }}">
                                Rp{{ number_format($row['harga'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: PILIH BAHASA/KURSUS (DARI /api/kursus) --}}
            <div id="step2" class="hidden">
                <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">
                    Langkah 2: Pilih Bahasa yang Ingin Dipelajari
                </h2>
                <p id="paketSubtitle" class="text-center text-gray-600 mb-10">
                    Pilih bahasa yang tersedia di paket yang kamu pilih
                </p>

                <div id="bahasaContainer" class="grid md:grid-cols-3 gap-8"></div>

                <div class="flex justify-between mt-10">
                    <button id="backToPaket" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Kembali
                    </button>
                </div>
            </div>

            {{-- STEP 3: FORM DATA & INVOICE --}}
            <div id="step3" class="hidden">
                <h2 class="text-3xl font-extrabold text-center mb-6 text-gray-800">
                    💳 Langkah 3: Isi Data & Lakukan Pembayaran
                </h2>
                <p class="text-center text-gray-600 mb-8">
                    Lengkapi identitasmu dan selesaikan pembayaran untuk mengaktifkan akun LinguEdu.
                </p>

                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl shadow-xl p-6 mb-10">
                    <h3 class="text-xl font-bold text-blue-700 mb-3">📄 Invoice Pembayaran</h3>
                    <div class="bg-white border border-blue-100 rounded-xl p-5 shadow-sm mb-5">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700">Nomor Invoice</span>
                            <span class="font-semibold text-blue-700">
                                #INV-{{ date('ymd') }}{{ rand(100, 999) }}
                            </span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700">📦 Paket</span>
                            <span id="rekapPaket" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700">🌐 Bahasa</span>
                            <span id="rekapBahasa" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700">💰 Total Pembayaran</span>
                            <span id="rekapHarga" class="font-bold text-blue-700 text-lg">-</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700">🏦 No. Rekening</span>
                            <span class="text-gray-800 font-medium">
                                BCA 1234567890 a/n LinguEdu Academy
                            </span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-700">📅 Tanggal Jatuh Tempo</span>
                            <span>{{ date('d M Y', strtotime('+1 day')) }}</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-gray-700 font-medium mb-3">
                            Atau gunakan pembayaran cepat via QRIS:
                        </p>
                        <div class="bg-white inline-block p-4 rounded-xl shadow">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=LinguEduPayment"
                                alt="QRIS Payment" class="mx-auto rounded-lg border p-2">
                        </div>
                        <p class="text-xs text-gray-500 mt-3 italic">
                            Scan QR menggunakan OVO, DANA, GoPay, ShopeePay atau m-banking lainnya.
                        </p>
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
                            <input id="passwordField" type="password" placeholder="Minimal 6 karakter"
                                class="w-full border rounded-lg px-4 py-3 bg-gray-50 focus:ring-2 focus:ring-blue-400"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                            <input id="buktiField" type="file" accept="image/*"
                                class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0 file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                required>
                            <p class="text-xs text-gray-500 mt-1">
                                Format JPG/PNG, maks 2 MB
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button id="backToLang" type="button"
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            ⬅ Kembali
                        </button>
                        <button id="previewBtn" type="button" disabled
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow disabled:opacity-50">
                            ✅ Konfirmasi & Buat Akun
                        </button>
                    </div>
                </form>
            </div>

            {{-- STEP 4: SUKSES --}}
            <div id="step4" class="hidden text-center">
                <div class="flex flex-col items-center">
                    <div class="relative mb-6">
                        <div class="bg-green-100 w-24 h-24 flex items-center justify-center rounded-full shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div
                            class="absolute -bottom-3 right-0 bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                            Verified
                        </div>
                    </div>

                    <h2 class="text-3xl font-extrabold text-green-700 mb-2">Akun Berhasil Dibuat!</h2>
                    <p class="text-gray-600 mb-8">
                        Selamat datang di LinguEdu 🎉 <br>
                        Mulailah perjalanan belajarmu dan raih sertifikat resmi.
                    </p>

                    <div
                        class="bg-gradient-to-r from-green-50 to-white border border-green-200 rounded-2xl shadow-lg w-full max-w-md p-6 text-left">
                        <h3 class="text-lg font-bold text-green-700 mb-4">📘 Detail Akun Kamu</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>👤 Nama:</strong> <span id="successNama"></span></p>
                            <p><strong>📦 Paket:</strong> <span id="successPaket"></span></p>
                            <p><strong>🌐 Bahasa:</strong> <span id="successBahasa"></span></p>
                            <p><strong>💰 Total Bayar:</strong> <span id="successTotal"></span></p>
                            <p><strong>📅 Tanggal Transaksi:</strong> <span id="successTgl"></span></p>
                            <p><strong>📅 Masa Aktif:</strong> <span id="successExpiry"></span></p>
                        </div>
                        <div class="mt-5 text-center">
                            <span
                                class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                Sertifikat Aktif
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-center mt-8 space-x-4">
                        <button id="editData" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            ✏️ Edit Data
                        </button>
                        <a href="{{ route('login.simulasi') }}"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                            ➡ Ke Halaman Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const api = axios.create({
                baseURL: "http://127.0.0.1:8000/api",
                headers: {
                    Accept: "application/json"
                },
            });

            // STEP containers
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            const step4 = document.getElementById('step4');
            const steps = [step1, step2, step3, step4];

            // Circles
            const circle1 = document.getElementById('circle1');
            const circle2 = document.getElementById('circle2');
            const circle3 = document.getElementById('circle3');
            const circle4 = document.getElementById('circle4');
            const circles = [circle1, circle2, circle3, circle4];

            const bahasaContainer = document.getElementById('bahasaContainer');
            const rekapPaket = document.getElementById('rekapPaket');
            const rekapBahasa = document.getElementById('rekapBahasa');
            const rekapHarga = document.getElementById('rekapHarga');

            const namaField = document.getElementById('namaField');
            const emailField = document.getElementById('emailField');
            const passwordField = document.getElementById('passwordField');
            const buktiField = document.getElementById('buktiField');
            const previewBtn = document.getElementById('previewBtn');

            const successNama = document.getElementById('successNama');
            const successPaket = document.getElementById('successPaket');
            const successBahasa = document.getElementById('successBahasa');
            const successExpiry = document.getElementById('successExpiry');
            const successTotal = document.getElementById('successTotal');
            const successTgl = document.getElementById('successTgl');


            const backToPaketBtn = document.getElementById('backToPaket');
            const backToLangBtn = document.getElementById('backToLang');
            const editDataBtn = document.getElementById('editData');

            // STATE
            let selectedPaketId = null;
            let selectedPaketName = null;
            let selectedPaketHarga = 0;
            let selectedBahasaId = null;
            let selectedBahasaName = null;

            function goToStep(n) {
                steps.forEach((s, i) => s.classList.toggle('hidden', i !== n));
                circles.forEach((c, i) => {
                    if (i <= n) {
                        c.classList.add('bg-blue-600', 'text-white');
                        c.classList.remove('bg-gray-300', 'text-gray-600');
                    } else {
                        c.classList.add('bg-gray-300', 'text-gray-600');
                        c.classList.remove('bg-blue-600', 'text-white');
                    }
                });
            }

            // STEP 1: pilih paket (kartu Blade)
            document.querySelectorAll('.paket-card').forEach(card => {
                card.addEventListener('click', async () => {
                    selectedPaketId = card.dataset.id_paket;
                    selectedPaketName = card.dataset.paket;
                    selectedPaketHarga = Number(card.dataset.harga || 0);

                    // Set rekap paket & harga di invoice dulu
                    rekapPaket.textContent = selectedPaketName;
                    rekapHarga.textContent =
                        'Rp' + selectedPaketHarga.toLocaleString('id-ID');

                    // Load semua bahasa
                    await loadBahasa();
                    goToStep(1); // pindah ke Step 2
                });
            });

            // STEP 2: ambil bahasa dari /api/bahasa (tidak tergantung paket)
            async function loadBahasa() {
                bahasaContainer.innerHTML =
                    '<p class="col-span-3 text-center text-gray-500">Memuat daftar bahasa...</p>';

                try {
                    const res = await api.get('/bahasa');
                    const list = res.data.data || [];

                    bahasaContainer.innerHTML = '';

                    if (!list.length) {
                        bahasaContainer.innerHTML =
                            '<p class="col-span-3 text-center text-gray-500">Belum ada data bahasa.</p>';
                        return;
                    }

                    list.forEach(bahasa => {
                        const div = document.createElement('div');
                        div.className =
                            'border-2 border-blue-200 bg-blue-50 rounded-2xl p-6 hover:border-blue-400 hover:shadow-xl transition cursor-pointer';

                        div.innerHTML = `
                            <h3 class="text-xl font-bold text-blue-700 mb-2">${bahasa.nama_bahasa}</h3>
                            <p class="text-gray-700 text-sm mb-3">${bahasa.desc || ''}</p>
                        `;

                        div.addEventListener('click', () => {
                            selectedBahasaId = bahasa.id;
                            selectedBahasaName = bahasa.nama_bahasa;

                            rekapBahasa.textContent = selectedBahasaName;

                            goToStep(2); // ke Step 3 (form + invoice)
                            validateForm();
                        });

                        bahasaContainer.appendChild(div);
                    });
                } catch (error) {
                    console.error(error);
                    bahasaContainer.innerHTML =
                        '<p class="col-span-3 text-center text-red-500">Gagal memuat data bahasa dari server.</p>';
                }
            }

            // tombol kembali
            backToPaketBtn?.addEventListener('click', () => goToStep(0));
            backToLangBtn?.addEventListener('click', () => goToStep(1));
            editDataBtn?.addEventListener('click', () => goToStep(2));

            // validasi form step 3
            ['namaField', 'emailField', 'passwordField', 'buktiField'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.addEventListener('input', validateForm);
                el.addEventListener('change', validateForm);
            });

            function validateForm() {
                const allFilled = [
                    namaField.value.trim(),
                    emailField.value.trim(),
                    passwordField.value.trim(),
                    buktiField.value
                ].every(v => v && v !== "");

                // Harus sudah pilih paket & bahasa juga
                previewBtn.disabled = !(
                    allFilled &&
                    selectedPaketId &&
                    selectedBahasaId
                );
            }

            // STEP 3: kirim ke /api/registrasi dengan id_paket + id_bahasa
            previewBtn.addEventListener('click', async () => {
                if (!selectedPaketId || !selectedBahasaId) {
                    alert('Silakan pilih paket dan bahasa terlebih dahulu.');
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('name', namaField.value);
                    formData.append('email', emailField.value);
                    formData.append('password', passwordField.value);
                    formData.append('id_paket', selectedPaketId);
                    formData.append('id_bahasa', selectedBahasaId);
                    formData.append('metode_bayar', 'Transfer BCA');

                    const buktiFile = buktiField.files[0];
                    if (buktiFile) {
                        formData.append('bukti_byr', buktiFile);
                    }

                    const res = await api.post('/registrasi', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    const data = res.data;

                    successNama.textContent = data.user.name;
                    successPaket.textContent = data.kursus.paket.nama_paket;
                    successBahasa.textContent = data.kursus.bahasa.nama_bahasa;

                    // ⬇️ Tambahan: total bayar
                    if (successTotal) {
                        const total = Number(data.registrasi.total_byr || 0);
                        successTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    }

                    // ⬇️ Tambahan: tanggal transaksi (ambil dari backend)
                    if (successTgl && data.registrasi.tgl_trans) {
                        const tgl = new Date(data.registrasi.tgl_trans);
                        successTgl.textContent = tgl.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                        });
                    }

                    // Expiry masih pakai +1 tahun (boleh tetap)
                    const expiry = new Date();
                    expiry.setFullYear(expiry.getFullYear() + 1);
                    successExpiry.textContent = expiry.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    goToStep(3);; // Step 4 sukses
                } catch (error) {
                    console.error(error);
                    const msg = error.response?.data?.message || 'Terjadi kesalahan saat registrasi.';
                    alert(msg);
                }
            });

            goToStep(0);
        });
    </script>
@endpush
