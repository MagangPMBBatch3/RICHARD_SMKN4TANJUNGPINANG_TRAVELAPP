document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#registerForm");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const name = document.querySelector("#name").value.trim();
        const email = document.querySelector("#email").value.trim();
        const password = document.querySelector("#password").value.trim();

        if (!name || !email || !password) {
            alert("Semua field wajib diisi!");
            return;
        }

        try {
            const res = await fetch("/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ name, email, password })
            });

            const data = await res.json();

            if (data.status === "success") {
                alert("Akun berhasil dibuat! Silakan login.");
                window.location.href = "/login";
            } else {
                alert(data.message || "Gagal membuat akun!");
            }

        } catch (error) {
            console.error("Error:", error);
            alert("Terjadi kesalahan, coba lagi.");
        }
    });
});
