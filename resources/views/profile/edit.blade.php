@extends('layouts.main')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Edit Profil</h2>

    <div id="alert" class="hidden mb-4 text-center text-sm"></div>

    <form id="profileForm">
        @csrf
        <input type="hidden" id="user_id" value="{{ session('user')['id'] }}">

        <div class="mb-4">
            <label class="block mb-1 text-sm font-semibold">Nama</label>
            <input id="name" type="text"
                value="{{ session('user')['name'] }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm font-semibold">Email</label>
            <input id="email" type="email"
                value="{{ session('user')['email'] }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <button class="bg-indigo-600 text-white px-5 py-2 rounded">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const api = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: {
        Accept: "application/json"
    }
});

document.getElementById("profileForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const name  = document.getElementById("name").value;
    const email = document.getElementById("email").value;

    try {
        const res = await api.post("/profile", { name, email });

        alert("✅ Profil berhasil diperbarui");
        location.reload();

    } catch (err) {
        alert("❌ Gagal menyimpan profil");
        console.error(err.response.data);
    }
});
</script>
@endpush
