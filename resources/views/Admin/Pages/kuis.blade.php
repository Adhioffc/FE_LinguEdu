@extends('layouts.admin')

@section('title', 'Manajemen Kuis & Evaluasi')

@section('content')
<div class="container py-5">

    {{-- NAVIGASI --}}
    <a href="/admin/dashboard" class="text-primary d-block mb-3">
        ← Kembali ke Dashboard
    </a>

    <h2 class="fw-bold mb-2">📝 Manajemen Kuis & Evaluasi</h2>
    <p class="text-muted mb-4">Kelola soal kuis sesuai materi yang tersedia.</p>

    {{-- PILIH LEVEL --}}
    <div class="mb-3">
        <label class="fw-semibold">Pilih Level</label>
        <select id="selectLevel" class="form-select w-auto">
            <option value="level1">Level 1</option>
            <option value="level2">Level 2</option>
            <option value="level3">Level 3</option>
        </select>
    </div>

    {{-- PILIH MATERI --}}
    <div class="mb-4">
        <label class="fw-semibold">Pilih Materi</label>
        <select id="selectMateri" class="form-select w-auto"></select>
    </div>

    {{-- TOMBOL TAMBAH --}}
    <button class="btn btn-primary mb-3" id="btnAddSoal">+ Tambah Soal</button>

    {{-- TABEL --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th style="width: 40%">Pertanyaan</th>
                    <th style="width: 40%">Pilihan</th>
                    <th style="width: 20%">Aksi</th>
                </tr>
            </thead>
            <tbody id="soalTable"></tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH SOAL --}}
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <label>Pertanyaan</label>
                <textarea id="addPertanyaan" class="form-control mb-3" rows="3"></textarea>

                <div class="row">
                    <div class="col-md-6">
                        <label>Pilihan A</label>
                        <input type="text" id="addA" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label>Pilihan B</label>
                        <input type="text" id="addB" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label>Pilihan C</label>
                        <input type="text" id="addC" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label>Pilihan D</label>
                        <input type="text" id="addD" class="form-control mb-2">
                    </div>
                </div>

                <label class="mt-2">Jawaban Benar</label>
                <select id="addCorrect" class="form-select w-auto">
                    <option value="A">Pilihan A</option>
                    <option value="B">Pilihan B</option>
                    <option value="C">Pilihan C</option>
                    <option value="D">Pilihan D</option>
                </select>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSaveAdd">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="editIndex">

                <label>Pertanyaan</label>
                <textarea id="editPertanyaan" class="form-control mb-3" rows="3"></textarea>

                <div class="row">
                    <div class="col-md-6">
                        <label>Pilihan A</label>
                        <input type="text" id="editA" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label>Pilihan B</label>
                        <input type="text" id="editB" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label>Pilihan C</label>
                        <input type="text" id="editC" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label>Pilihan D</label>
                        <input type="text" id="editD" class="form-control mb-2">
                    </div>
                </div>

                <label class="mt-2">Jawaban Benar</label>
                <select id="editCorrect" class="form-select w-auto">
                    <option value="A">Pilihan A</option>
                    <option value="B">Pilihan B</option>
                    <option value="C">Pilihan C</option>
                    <option value="D">Pilihan D</option>
                </select>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSaveEdit">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* =============================
   DATA KUIS (LOCALSTORAGE)
============================= */
let materiList = JSON.parse(localStorage.getItem("materiData")) || {
    level1: [],
    level2: [],
    level3: []
};

let kuisData = JSON.parse(localStorage.getItem("kuisData")) || {
    level1: {},
    level2: {},
    level3: {}
};

/* Jika materi belum ada, kuis tetap dibuat berdasarkan materi yang muncul */
function loadMateriOptions(level) {
    let dropdown = document.getElementById("selectMateri");
    dropdown.innerHTML = "";

    materiList[level].forEach((m, index) => {
        let opt = document.createElement("option");
        opt.value = index;
        opt.textContent = m.title;
        dropdown.appendChild(opt);
    });

    renderSoal();
}

/* =============================
   RENDER SOAL
============================= */
function renderSoal() {
    let level = document.getElementById("selectLevel").value;
    let materiIndex = document.getElementById("selectMateri").value;
    let table = document.getElementById("soalTable");

    table.innerHTML = "";

    if (!kuisData[level][materiIndex]) {
        kuisData[level][materiIndex] = [];
    }

    kuisData[level][materiIndex].forEach((s, i) => {
        table.innerHTML += `
            <tr>
                <td>${s.question}</td>
                <td>
                    A. ${s.a}<br>
                    B. ${s.b}<br>
                    C. ${s.c}<br>
                    D. ${s.d}<br>
                    <strong>Jawaban: ${s.correct}</strong>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="openEdit(${i})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteSoal(${i})">Hapus</button>
                </td>
            </tr>
        `;
    });
}

/* =============================
   TAMBAH SOAL
============================= */
document.getElementById("btnAddSoal").onclick = () => {
    new bootstrap.Modal(document.getElementById("modalAdd")).show();
};

document.getElementById("btnSaveAdd").onclick = () => {
    let level = selectLevel.value;
    let materiIndex = selectMateri.value;

    let data = {
        question: addPertanyaan.value,
        a: addA.value,
        b: addB.value,
        c: addC.value,
        d: addD.value,
        correct: addCorrect.value
    };

    if (!kuisData[level][materiIndex]) kuisData[level][materiIndex] = [];
    kuisData[level][materiIndex].push(data);

    localStorage.setItem("kuisData", JSON.stringify(kuisData));
    renderSoal();

    bootstrap.Modal.getInstance(document.getElementById("modalAdd")).hide();
};

/* =============================
   EDIT SOAL
============================= */
function openEdit(index) {
    let level = selectLevel.value;
    let materiIndex = selectMateri.value;
    let s = kuisData[level][materiIndex][index];

    editIndex.value = index;
    editPertanyaan.value = s.question;
    editA.value = s.a;
    editB.value = s.b;
    editC.value = s.c;
    editD.value = s.d;
    editCorrect.value = s.correct;

    new bootstrap.Modal(document.getElementById("modalEdit")).show();
}

document.getElementById("btnSaveEdit").onclick = () => {
    let level = selectLevel.value;
    let materiIndex = selectMateri.value;
    let i = editIndex.value;

    kuisData[level][materiIndex][i] = {
        question: editPertanyaan.value,
        a: editA.value,
        b: editB.value,
        c: editC.value,
        d: editD.value,
        correct: editCorrect.value
    };

    localStorage.setItem("kuisData", JSON.stringify(kuisData));
    renderSoal();

    bootstrap.Modal.getInstance(document.getElementById("modalEdit")).hide();
};

/* =============================
   DELETE SOAL
============================= */
function deleteSoal(i) {
    let level = selectLevel.value;
    let materiIndex = selectMateri.value;

    kuisData[level][materiIndex].splice(i, 1);

    localStorage.setItem("kuisData", JSON.stringify(kuisData));
    renderSoal();
}

/* =============================
   INIT
============================= */
document.getElementById("selectLevel").onchange = () => loadMateriOptions(selectLevel.value);
document.getElementById("selectMateri").onchange = renderSoal;

// default load
loadMateriOptions("level1");
</script>
@endpush
