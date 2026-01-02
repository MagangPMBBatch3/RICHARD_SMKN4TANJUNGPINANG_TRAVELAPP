document.addEventListener("DOMContentLoaded", () => {

    /* =============================
     * AMBIL PARAMETER URL
     * ============================= */
    const params = new URLSearchParams(window.location.search);

    const ROOM_ID   = params.get("room_id");
    const CHECK_IN  = params.get("check_in");
    const CHECK_OUT = params.get("check_out");
    const QTY       = parseInt(params.get("qty") || 1);

    if (!ROOM_ID || !CHECK_IN || !CHECK_OUT) {
        alert("Data booking tidak lengkap");
        return;
    }

    /* =============================
     * ELEMENT
     * ============================= */
    const bookingFor = document.getElementById("bookingFor");
    const guestForm  = document.getElementById("guestForm");
    const btnSubmit  = document.getElementById("btnSubmit");
    const summary    = document.getElementById("summary");

    /* =============================
     * CSRF
     * ============================= */
    function csrf() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    /* =============================
     * GRAPHQL HELPER
     * ============================= */
    async function graphql(query, variables = {}) {
        const res = await fetch("/graphql", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrf(),
            },
            body: JSON.stringify({ query, variables }),
        });

        const json = await res.json();

        if (json.errors) {
            console.error(json.errors);
            throw new Error(json.errors[0].message);
        }

        return json.data;
    }

    /* =============================
     * TOGGLE TAMU
     * ============================= */
    bookingFor?.addEventListener("change", () => {
        guestForm.classList.toggle(
            "hidden",
            bookingFor.value !== "other"
        );
    });

    /* =============================
     * SUMMARY
     * ============================= */
    summary.innerHTML = `
        <p><b>Check-in:</b> ${CHECK_IN}</p>
        <p><b>Check-out:</b> ${CHECK_OUT}</p>
        <p><b>Jumlah kamar:</b> ${QTY}</p>
    `;

    /* =============================
     * SUBMIT BOOKING
     * ============================= */
    btnSubmit.addEventListener("click", async () => {

        btnSubmit.disabled = true;
        btnSubmit.innerText = "Memproses...";

        try {
            const input = {
                items: [
                    {
                        item_type: "hotel_room",
                        reference_id: ROOM_ID,
                        quantity: QTY,
                        check_in: CHECK_IN,
                        check_out: CHECK_OUT,
                    }
                ]
            };

            // 🔹 booking untuk orang lain
            if (bookingFor?.value === "other") {

                const name  = document.getElementById("guestName")?.value.trim();
                const phone = document.getElementById("guestPhone")?.value.trim();
                const email = document.getElementById("guestEmail")?.value.trim();

                if (!name || !phone) {
                    alert("Nama dan nomor HP wajib diisi");
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = "Booking Sekarang";
                    return;
                }

                input.passengers = [
                    {
                        full_name: name,
                        phone: phone,
                        email: email || null,
                    }
                ];
            }

            const mutation = `
                mutation ($input: BookingInput!) {
                    createBooking(input: $input) {
                        id
                        booking_code
                        status
                    }
                }
            `;

            const data = await graphql(mutation, { input });

            alert(
                "✅ Booking berhasil!\nKode Booking: " +
                data.createBooking.booking_code
            );

            window.location.href = "/booking/history";

        } catch (e) {
            alert(e.message || "Gagal melakukan booking");
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.innerText = "Booking Sekarang";
        }
    });
});
