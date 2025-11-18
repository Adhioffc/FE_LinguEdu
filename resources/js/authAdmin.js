import api from "./api";

document.getElementById("btnLogin").addEventListener("click", async () => {
    const alertBox = document.getElementById("alert");
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        const res = await axios.post("http://127.0.0.1:8000/api/admin/login", {
            email: email,
            password: password,
        });

        localStorage.setItem("token", res.data.token);

        window.location.href = "/admin/dashboard";  // langsung redirect
    } catch (err) {
        alertBox.classList.remove("hidden");
        alertBox.innerText = "Email atau password salah!";
    }
});
