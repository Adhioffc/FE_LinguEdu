@extends('layouts.admin')

@section('title', 'Kelola Sertifikasi')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">🎓 Kelola Sertifikasi</h1>
    <p class="text-gray-600 mb-6">
        Sistem sertifikasi otomatis berdasarkan penyelesaian paket belajar.
        (Simulasi tanpa database)
    </p>

    {{-- ======================= --}}
    {{-- 1. SIMULASI PAKET SELESAI --}}
    {{-- ======================= --}}
    <div class="bg-white p-5 shadow rounded-lg mb-6">
        <h2 class="text-xl font-semibold mb-3">✔ Tandai Paket Selesai</h2>
        <p class="text-gray-500 mb-4">Ketika paket ditandai selesai, sertifikat otomatis dibuat.</p>

        <form id="finishPackageForm" class="flex gap-3">
            <select id="paketSelesai" class="border px-3 py-2 rounded w-64">
                <option value="">-- Pilih Paket --</option>
                <option value="Paket A (Basic English)">Paket A (Basic English)</option>
                <option value="Paket B (Japanese Intro)">Paket B (Japanese Intro)</option>
                <option value="Paket C (Korean Beginner)">Paket C (Korean Beginner)</option>
            </select>

            <button type="button"
                id="btnTandai"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                Tandai Selesai
            </button>
        </form>
    </div>

    {{-- ======================= --}}
    {{-- 2. DAFTAR SERTIFIKAT --}}
    {{-- ======================= --}}
    <div class="bg-white p-5 shadow rounded-lg">
        <h2 class="text-xl font-semibold mb-4">📜 Daftar Sertifikasi</h2>

        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border">Nama User</th>
                    <th class="p-3 border">Paket</th>
                    <th class="p-3 border">Tanggal</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="sertifTable">
                {{-- Sertifikat otomatis muncul disini --}}
            </tbody>
        </table>
    </div>

    {{-- ======================= --}}
    {{-- 3. TEMPLATE SERTIFIKAT --}}
    {{-- ======================= --}}
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-3">🎨 Preview Sertifikat</h2>

        <div id="certificatePreview"
             class="w-[800px] h-[550px] bg-white border shadow relative p-10">

            {{-- Title --}}
            <h1 class="text-4xl font-bold text-center mt-6">SERTIFIKAT</h1>

            {{-- Subtitle --}}
            <p class="text-center text-gray-600 mt-2 text-lg">
                Diberikan kepada:
            </p>

            {{-- Nama --}}
            <h2 id="certName"
                class="text-3xl font-bold text-center mt-4">
                -
            </h2>

            {{-- Paket --}}
            <p id="certPackage"
               class="text-center mt-2 text-xl text-gray-700">
               -
            </p>

            {{-- Tanggal --}}
            <p id="certDate"
               class="text-center mt-2 text-gray-600">
               -
            </p>

            {{-- Signature --}}
            <div class="absolute bottom-10 right-14 text-right">
                <p class="font-semibold">LinguEdu Academy</p>
                <p class="text-gray-500 text-sm">Administrator</p>
            </div>
        </div>

        {{-- Button Download --}}
        <button id="btnDownloadPDF"
                class="mt-4 bg-green-600 text-white px-5 py-3 rounded hover:bg-green-700">
            Download Sertifikat (PDF)
        </button>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    const table = document.getElementById("sertifTable");
    const btnTandai = document.getElementById("btnTandai");

    const userName = "{{ session('user') ?? 'Nama User' }}";

    btnTandai.addEventListener('click', () => {
        const paket = document.getElementById("paketSelesai").value;
        if (!paket) return alert("Pilih paket dulu");

        const today = new Date().toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric"
        });

        // 1. Tambahkan ke tabel sertifikat
        const row = `
            <tr>
                <td class="border p-3">${userName}</td>
                <td class="border p-3">${paket}</td>
                <td class="border p-3">${today}</td>
                <td class="border p-3">
                    <button onclick="preview('${userName}', '${paket}', '${today}')"
                        class="bg-blue-600 text-white px-3 py-1 rounded">
                        Preview
                    </button>
                </td>
            </tr>
        `;
        table.innerHTML += row;

        // 2. Update preview otomatis
        preview(userName, paket, today);
    });

    function preview(name, paket, tanggal) {
        document.getElementById("certName").innerText = name;
        document.getElementById("certPackage").innerText = paket;
        document.getElementById("certDate").innerText = tanggal;
    }

    // DOWNLOAD PDF
    document.getElementById("btnDownloadPDF").addEventListener("click", async () => {
        const certEl = document.getElementById("certificatePreview");
        const canvas = await html2canvas(certEl, { scale: 2 });
        const imgData = canvas.toDataURL("image/png");

        const pdf = new jspdf.jsPDF("landscape", "pt", [800, 550]);
        pdf.addImage(imgData, "PNG", 0, 0, 800, 550);
        pdf.save("sertifikat.pdf");
    });
</script>
@endpush
