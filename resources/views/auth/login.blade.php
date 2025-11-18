@extends('layouts.main')
@section('title', 'Login Admin - LinguEdu')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-blue-50 py-16 px-4">
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-2">Login Admin</h1>
            <p class="text-center text-gray-500 mb-8">Silakan masuk untuk mengelola sistem.</p>

            <div id="alert" class="hidden mb-4 text-center px-4 py-2 rounded-lg"></div>

            <form id="loginForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" placeholder="adminlinguedu@gmail.com" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" placeholder="••••••••" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>

                <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Masuk Sekarang
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
            headers: {
                Accept: "application/json",
            }
        });

        const loginForm = document.getElementById("loginForm");
        const alertBox = document.getElementById("alert");

        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            try {
                const response = await api.post("/login", {
                    email: email,
                    password: password,
                });

                localStorage.setItem("token", response.data.token);

                if (response.data.user.role === 'admin')
                    window.location = "/admin/dashboard";
                else if (response.data.user.role === 'member')
                    window.location = "/member/dashboard";

            } catch (error) {
                alertBox.classList.remove("hidden");
                alertBox.classList.add("bg-red-100", "text-red-700", "border", "border-red-400");
                alertBox.innerText = "Email atau password salah!";
            }
        });
    </script>
@endpush
