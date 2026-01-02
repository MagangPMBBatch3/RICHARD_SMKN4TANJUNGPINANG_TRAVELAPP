document.addEventListener("DOMContentLoaded", () => {

    const proofInput = document.getElementById("proof");
    const preview    = document.getElementById("preview");
    const btnUpload  = document.getElementById("btnUpload");

    proofInput.addEventListener("change", () => {
        const file = proofInput.files[0];
        if (!file) return;

        preview.src = URL.createObjectURL(file);
        preview.classList.remove("hidden");
    });

    function csrf() {
        return document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
    }

    async function upload() {

        const file = proofInput.files[0];
        const method = document.getElementById("payment_method").value;
        const amount = document.getElementById("amount").value;

        if (!file) {
            alert("Pilih bukti pembayaran");
            return;
        }

        if (!method) {
            alert("Pilih metode pembayaran");
            return;
        }

        const mutation = `
            mutation ($file: Upload!) {
                uploadPaymentProof(
                    booking_id: ${BOOKING_ID}
                    amount: ${amount}
                    payment_method: "${method}"
                    proof: $file
                ) {
                    id
                    status
                }
            }
        `;

        const formData = new FormData();
        formData.append(
            "operations",
            JSON.stringify({
                query: mutation,
                variables: { file: null }
            })
        );

        formData.append(
            "map",
            JSON.stringify({ "0": ["variables.file"] })
        );

        formData.append("0", file);

        btnUpload.disabled = true;
        btnUpload.innerText = "Uploading...";

        const res = await fetch("/graphql", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "X-CSRF-TOKEN": csrf()
            },
            body: formData
        });

        const json = await res.json();

        if (json.errors) {
            console.error(json.errors);
            alert(json.errors[0].message);
            btnUpload.disabled = false;
            btnUpload.innerText = "Upload Bukti Pembayaran";
            return;
        }

        alert("✅ Bukti pembayaran berhasil dikirim!");
        window.location.href = `/booking/${BOOKING_ID}`;
    }

    btnUpload.addEventListener("click", upload);
});
