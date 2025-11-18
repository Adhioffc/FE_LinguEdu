<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sertifikasi</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- html2canvas + jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        #preview-cert {
            width: 700px;
            height: 500px;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">

    <!-- NAVIGATION -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🏅 Manajemen Sertifikasi</h1>
        <a href="/dashboard" class="px-4 py-2 bg-gray-700 hover:bg-gray-900 text-white rounded">← Kembali ke Dashboard</a>
    </div>

    <!-- TAB SELECTION -->
    <div class="flex gap-3 mb-6">
        <button onclick="showTab('template')" id="tab-btn-template" class="bg-blue-600 text-white px-4 py-2 rounded">
            Template Sertifikat
        </button>
        <button onclick="showTab('peserta')" id="tab-btn-peserta" class="bg-gray-300 px-4 py-2 rounded">
            Daftar Peserta
        </button>
    </div>

    <!-- ============================= -->
    <!-- 1) TEMPLATE SERTIFIKAT -->
    <!-- ============================= -->
    <div id="tab-template" class="bg-white shadow p-6 rounded-lg tab-page">

        <h2 class="text-xl font-semibold mb-4">📄 Template Sertifikat</h2>

        <!-- FORM CREATE TEMPLATE -->
        <div class="mb-6">
            <label class="font-semibold">Judul Sertifikat</label>
            <input id="temp-title" type="text" class="w-full border p-2 rounded mb-2">

            <label class="font-semibold">Deskripsi</label>
            <textarea id="temp-desc" class="w-full border p-2 rounded mb-2"></textarea>

            <label class="font-semibold">Nama Penandatangan</label>
            <input id="temp-sign" type="text" class="w-full border p-2 rounded mb-2">

            <label class="font-semibold">Pilih Paket</label>
            <select id="temp-paket" class="w-full border p-2 rounded mb-2">
                <option>Inggris</option>
                <option>Jepang</option>
                <option>Korea</option>
            </select>

            <label class="font-semibold">Background Sertifikat (URL)</label>
            <input id="temp-bg" type="text" class="w-full border p-2 rounded mb-2" placeholder="https://...">

            <button onclick="createTemplate()" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">
                Simpan Template
            </button>
        </div>

        <!-- TABLE TEMPLATE -->
        <h3 class="font-semibold text-lg mt-6 mb-3">Daftar Template</h3>
        <table class="w-full border text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">Judul</th>
                    <th class="border p-2">Paket</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="template-table"></tbody>
        </table>
    </div>

    <!-- ============================= -->
    <!-- 2) PESERTA & PREVIEW -->
    <!-- ============================= -->
    <div id="tab-peserta" class="bg-white shadow p-6 rounded-lg hidden tab-page">

        <h2 class="text-xl font-semibold mb-4">👤 Daftar Peserta & Preview Sertifikat</h2>

        <!-- INPUT PESERTA -->
        <div class="mb-6">
            <label class="font-semibold">Nama Peserta</label>
            <input id="peserta-name" type="text" class="w-full border p-2 rounded mb-2">

            <label class="font-semibold">Pilih Template</label>
            <select id="peserta-template" class="w-full border p-2 rounded mb-2"></select>

            <button onclick="addPeserta()" class="bg-green-600 text-white px-4 py-2 rounded mt-2">
                Tambahkan Peserta
            </button>
        </div>

        <!-- TABLE PESERTA -->
        <h3 class="font-semibold text-lg mt-6 mb-3">Daftar Peserta</h3>
        <table class="w-full border text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Template</th>
                    <th class="border p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="peserta-table"></tbody>
        </table>

        <!-- PREVIEW -->
        <h3 class="font-semibold text-lg mt-8 mb-2">🔍 Preview Sertifikat</h3>
        <div id="preview-cert" class="border relative rounded-lg shadow bg-white">
            <div id="prev-title" class="absolute top-10 w-full text-center text-2xl font-bold"></div>
            <div id="prev-name" class="absolute top-32 w-full text-center text-xl font-semibold"></div>
            <div id="prev-desc" class="absolute top-48 w-full text-center px-6"></div>
            <div id="prev-sign" class="absolute bottom-10 w-full text-center font-medium"></div>
            <div id="prev-date" class="absolute bottom-4 w-full text-center text-sm text-gray-600"></div>
        </div>

        <!-- DOWNLOAD BUTTONS -->
        <div class="flex gap-3 mt-4">
            <button onclick="downloadPNG()" class="bg-blue-600 text-white px-4 py-2 rounded">Download PNG</button>
            <button onclick="downloadPDF()" class="bg-red-600 text-white px-4 py-2 rounded">Download PDF</button>
        </div>

    </div>

    <!-- ====================================================== -->
    <!-- JAVASCRIPT LOGIC -->
    <!-- ====================================================== -->
    <script>
        let templates = JSON.parse(localStorage.getItem("cert_templates") || "[]");
        let peserta = JSON.parse(localStorage.getItem("cert_peserta") || "[]");

        function save() {
            localStorage.setItem("cert_templates", JSON.stringify(templates));
            localStorage.setItem("cert_peserta", JSON.stringify(peserta));
        }

        // SHOW TABS
        function showTab(tab) {
            document.querySelectorAll(".tab-page").forEach(t => t.classList.add("hidden"));
            document.getElementById("tab-" + tab).classList.remove("hidden");

            document.querySelectorAll("[id^='tab-btn']").forEach(b => b.classList.remove("bg-blue-600","text-white"));
            document.getElementById("tab-btn-" + tab).classList.add("bg-blue-600","text-white");

            updateUI();
        }

        /* ============================================================
           TEMPLATE CRUD
        ============================================================ */

        function createTemplate() {
            let obj = {
                id: Date.now(),
                title: document.getElementById("temp-title").value,
                desc: document.getElementById("temp-desc").value,
                sign: document.getElementById("temp-sign").value,
                paket: document.getElementById("temp-paket").value,
                bg: document.getElementById("temp-bg").value
            };

            templates.push(obj);
            save();
            updateUI();

            alert("Template ditambahkan!");
        }

        function deleteTemplate(id) {
            templates = templates.filter(t => t.id !== id);
            save();
            updateUI();
        }

        /* ============================================================
           PESERTA CRUD
        ============================================================ */

        function addPeserta() {
            peserta.push({
                id: Date.now(),
                name: document.getElementById("peserta-name").value,
                templateId: Number(document.getElementById("peserta-template").value)
            });

            save();
            updateUI();

            alert("Peserta ditambahkan!");
        }

        function deletePeserta(id) {
            peserta = peserta.filter(p => p.id !== id);
            save();
            updateUI();
        }

        /* ============================================================
           PREVIEW UPDATE
        ============================================================ */

        function preview(id) {
            let p = peserta.find(x => x.id === id);
            if (!p) return;

            let t = templates.find(x => x.id === p.templateId);
            if (!t) return;

            // SET BACKGROUND
            document.getElementById("preview-cert").style.backgroundImage = `url('${t.bg}')`;

            // SET TEXT
            document.getElementById("prev-title").innerText = t.title;
            document.getElementById("prev-name").innerText = p.name;
            document.getElementById("prev-desc").innerText = t.desc;
            document.getElementById("prev-sign").innerText = t.sign;

            // AUTO DATE
            let d = new Date().toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric"
            });
            document.getElementById("prev-date").innerText = "Diterbitkan pada: " + d;
        }

        /* ============================================================
           DOWNLOAD PNG
        ============================================================ */

        function downloadPNG() {
            let cert = document.getElementById("preview-cert");
            html2canvas(cert).then(canvas => {
                let link = document.createElement("a");
                link.download = "sertifikat.png";
                link.href = canvas.toDataURL();
                link.click();
            });
        }

        /* ============================================================
           DOWNLOAD PDF
        ============================================================ */

        async function downloadPDF() {
            const { jsPDF } = window.jspdf;

            let cert = document.getElementById("preview-cert");
            html2canvas(cert).then(canvas => {
                let img = canvas.toDataURL("image/png");

                let pdf = new jsPDF("landscape", "pt", [700, 500]);
                pdf.addImage(img, "PNG", 0, 0, 700, 500);
                pdf.save("sertifikat.pdf");
            });
        }

        /* ============================================================
           UPDATE UI
        ============================================================ */

        function updateUI() {

            // UPDATE TEMPLATE TABLE
            let t = "";
            templates.forEach(x => {
                t += `
                <tr>
                    <td class="border p-2">${x.title}</td>
                    <td class="border p-2">${x.paket}</td>
                    <td class="border p-2 text-center">
                        <button onclick="deleteTemplate(${x.id})" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                    </td>
                </tr>
                `;
            });
            document.getElementById("template-table").innerHTML = t;

            // UPDATE SELECT TEMPLATE DROPDOWN
            let opt = "";
            templates.forEach(x => {
                opt += `<option value="${x.id}">${x.title} (${x.paket})</option>`;
            });
            document.getElementById("peserta-template").innerHTML = opt;

            // UPDATE PESERTA TABLE
            let p = "";
            peserta.forEach(x => {
                let t2 = templates.find(a => a.id === x.templateId);
                p += `
                <tr>
                    <td class="border p-2">${x.name}</td>
                    <td class="border p-2">${t2 ? t2.title : "-"}</td>
                    <td class="border p-2 text-center">
                        <button onclick="preview(${x.id})" class="bg-blue-600 text-white px-3 py-1 rounded">Preview</button>
                        <button onclick="deletePeserta(${x.id})" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                    </td>
                </tr>
                `;
            });
            document.getElementById("peserta-table").innerHTML = p;
        }

        updateUI();
    </script>

</body>
</html>