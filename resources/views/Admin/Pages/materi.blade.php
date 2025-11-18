@extends('layouts.admin')

@section('content')
<div class="container py-5">

    <a href="/admin/dashboard" class="btn btn-secondary mb-4">⬅ Kembali ke Dashboard</a>

    <h1 class="mb-4">📘 Manajemen Materi</h1>

    <!-- Level Selector -->
    <div class="mb-4">
        <label class="form-label fw-bold">Pilih Level</label>
        <select id="levelSelect" class="form-select w-50">
            <option value="level1">Level 1</option>
            <option value="level2">Level 2</option>
            <option value="level3">Level 3</option>
        </select>
    </div>

    <!-- Button Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
        + Tambah Materi
    </button>

    <!-- Table Materi -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped" id="materiTable">
                <thead>
                    <tr class="text-center">
                        <th width="20%">Gambar</th>
                        <th width="25%">Judul</th>
                        <th width="40%">Deskripsi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materiBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- ===================== ADD MODAL ========================= -->
<!-- ========================================================= -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Materi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label fw-bold">Judul Materi</label>
                <input type="text" class="form-control mb-3" id="addTitle">

                <label class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control mb-3" id="addDesc" rows="3"></textarea>

                <label class="form-label fw-bold">URL Gambar</label>
                <input type="text" class="form-control mb-3" id="addImg">

                <img id="addPreview" src="" class="img-fluid rounded mt-2 d-none" style="max-height: 200px;">
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" onclick="saveNewMateri()">Simpan</button>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- ===================== EDIT MODAL ======================== -->
<!-- ========================================================= -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Materi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="editIndex">

                <label class="form-label fw-bold">Judul Materi</label>
                <input type="text" class="form-control mb-3" id="editTitle">

                <label class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control mb-3" id="editDesc" rows="3"></textarea>

                <label class="form-label fw-bold">URL Gambar</label>
                <input type="text" class="form-control mb-3" id="editImg">

                <img id="editPreview" src="" class="img-fluid rounded mt-2 d-none" style="max-height: 200px;">
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="saveEdit()">Simpan Perubahan</button>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- ===================== JAVASCRIPT ======================== -->
<!-- ========================================================= -->
<script>
    // Default materi (jika localStorage kosong)
    let materi = JSON.parse(localStorage.getItem("materi")) || {
        level1: [
            {
                title: "Pengenalan Dasar",
                desc: "Materi dasar untuk pemula.",
                img: "https://via.placeholder.com/200"
            }
        ],
        level2: [],
        level3: []
    };

    let currentLevel = "level1";

    // ====================================================
    // Render Data ke Table
    // ====================================================
    function renderTable() {
        const tbody = document.getElementById("materiBody");
        tbody.innerHTML = "";

        materi[currentLevel].forEach((item, index) => {
            tbody.innerHTML += `
                <tr>
                    <td class="text-center">
                        <img src="${item.img}" class="img-fluid rounded" style="max-height:120px;">
                    </td>
                    <td>${item.title}</td>
                    <td>${item.desc}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="openEdit(${index})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteMateri(${index})">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById("levelSelect").addEventListener("change", function () {
        currentLevel = this.value;
        renderTable();
    });

    // ====================================================
    // Tambah Materi
    // ====================================================
    function saveNewMateri() {
        const title = document.getElementById("addTitle").value;
        const desc = document.getElementById("addDesc").value;
        const img = document.getElementById("addImg").value;

        if (!title || !desc || !img) {
            alert("Semua field wajib diisi!");
            return;
        }

        materi[currentLevel].push({ title, desc, img });
        localStorage.setItem("materi", JSON.stringify(materi));

        document.getElementById("addTitle").value = "";
        document.getElementById("addDesc").value = "";
        document.getElementById("addImg").value = "";

        document.getElementById("addPreview").classList.add("d-none");

        renderTable();
        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
    }

    // Preview gambar ADD
    document.getElementById("addImg").addEventListener("input", function () {
        const img = document.getElementById("addPreview");
        img.src = this.value;
        img.classList.remove("d-none");
    });

    // ====================================================
    // Edit Materi
    // ====================================================
    function openEdit(index) {
        const item = materi[currentLevel][index];

        document.getElementById("editIndex").value = index;
        document.getElementById("editTitle").value = item.title;
        document.getElementById("editDesc").value = item.desc;
        document.getElementById("editImg").value = item.img;

        const prev = document.getElementById("editPreview");
        prev.src = item.img;
        prev.classList.remove("d-none");

        new bootstrap.Modal(document.getElementById("editModal")).show();
    }

    function saveEdit() {
        const i = document.getElementById("editIndex").value;

        materi[currentLevel][i].title = document.getElementById("editTitle").value;
        materi[currentLevel][i].desc = document.getElementById("editDesc").value;
        materi[currentLevel][i].img = document.getElementById("editImg").value;

        localStorage.setItem("materi", JSON.stringify(materi));
        renderTable();

        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    }

    // Preview gambar EDIT
    document.getElementById("editImg").addEventListener("input", function () {
        const img = document.getElementById("editPreview");
        img.src = this.value;
        img.classList.remove("d-none");
    });

    // ====================================================
    // Hapus Materi
    // ====================================================
    function deleteMateri(index) {
        if (confirm("Yakin ingin menghapus materi ini?")) {
            materi[currentLevel].splice(index, 1);
            localStorage.setItem("materi", JSON.stringify(materi));
            renderTable();
        }
    }

    // Render awal
    renderTable();
</script>
@endsection
