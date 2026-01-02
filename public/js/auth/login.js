document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        if (!email || !password) {
            alert("Email dan password wajib diisi!");
            return;
        }

        try {
            const response = await fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ email, password }),
            });

            if (response.redirected) {
                window.location.href = response.url;                return;
            }

            const result = await response.json();

            if (result.errors) {
                alert(result.errors.email || "Login gagal.");
            } else {
                alert("Login berhasil!");
                window.location.href = "/dashboard";
            }
        } catch (err) {
            console.error("Error:", err);
            alert("Terjadi kesalahan pada server.");
        }
    });
});
