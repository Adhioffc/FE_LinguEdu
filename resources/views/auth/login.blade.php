@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-blue-50">
    <div class="bg-white p-8 rounded-xl w-full max-w-md shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

        <div id="alert" class="hidden mb-4 text-red-600 text-center"></div>

        <form id="loginForm">
            @csrf

            <input id="email" type="email" placeholder="Email"
                class="w-full p-3 border mb-4 rounded" required>

            <input id="password" type="password" placeholder="Password"
                class="w-full p-3 border mb-4 rounded" required>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded">
                Login
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const api = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: { Accept: "application/json" }
});

document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        const res = await api.post("/login", { email, password });

        // ✅ simpan session frontend DENGAN BENAR
        await fetch("/frontend-login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                user: res.data.user
            })
        });

        // ✅ MASUK KE DASHBOARD (BUKAN PROFILE)
        window.location.href = "/member/dashboard";

    } catch (err) {
        document.getElementById("alert").classList.remove("hidden");
        alert.innerText = "Login gagal";
    }
});
</script>


@endpush
