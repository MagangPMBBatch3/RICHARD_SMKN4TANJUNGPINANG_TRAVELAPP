document.addEventListener("DOMContentLoaded", () => {

    const params = new URLSearchParams(window.location.search);

    const SCHEDULE_ID = params.get("schedule_id");
    const QTY = parseInt(params.get("qty") || 1);

    const btnSubmit = document.getElementById("btnSubmit");
    const summary   = document.getElementById("summary");

    if (!SCHEDULE_ID) {
        alert("Schedule tidak ditemukan");
        return;
    }

    function csrf() {
        return document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
    }

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

        return res.json();
    }

    /* =====================
       SUMMARY
    ===================== */
    summary.innerHTML = `
        <p><b>Schedule ID:</b> ${SCHEDULE_ID}</p>
        <p><b>Jumlah:</b> ${QTY}</p>
    `;

    /* =====================
       SUBMIT BOOKING
    ===================== */
    btnSubmit.addEventListener("click", async () => {

        const input = {
            items: [
                {
                    item_type: "transport_schedule",
                    reference_id: SCHEDULE_ID,
                    quantity: QTY
                }
            ]
        };

        const mutation = `
            mutation CreateBooking($input: BookingInput!) {
                createBooking(input: $input) {
                    id
                    booking_code
                    booking_type
                }
            }
        `;

        const res = await graphql(mutation, { input });

        if (res.errors) {
            alert(res.errors[0].message);
            console.error(res.errors);
            return;
        }

        const booking = res.data.createBooking;

        alert(
            "✅ Booking transport berhasil\n" +
            "Kode: " + booking.booking_code
        );

        window.location.href = `/booking/${booking.id}`;
    });
});
