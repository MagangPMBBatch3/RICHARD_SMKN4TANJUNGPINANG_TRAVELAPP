document.addEventListener("DOMContentLoaded", loadDetail);

async function gql(query, variables = {}) {
    const res = await fetch("/graphql", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query, variables })
    });

    const json = await res.json();
    if (json.errors) {
        console.error(json.errors);
        throw new Error(json.errors[0].message);
    }
    return json.data;
}

/* =========================
   LOAD PAYMENT DETAIL
========================= */
async function loadDetail() {
    const query = `
        query ($id: ID!) {
            paymentDetail(id: $id) {
                id
                amount
                proof
                status
                booking {
                    booking_code
                    user {
                        name
                    }
                }
            }
        }
    `;

    const data = await gql(query, { id: PAYMENT_ID });
    const p = data.paymentDetail;

    document.getElementById("bookingCode").innerText =
        p.booking?.booking_code ?? "-";

    document.getElementById("userName").innerText =
        p.booking?.user?.name ?? "-";

    document.getElementById("amount").innerText =
        "Rp " + Number(p.amount).toLocaleString("id-ID");

    document.getElementById("proofImage").src =
        p.proof ? "/storage/" + p.proof : "";
}

/* =========================
   CONFIRM PAYMENT
========================= */
async function confirmPayment() {
    if (!confirm("Konfirmasi pembayaran ini?")) return;

    const mutation = `
        mutation ($id: ID!, $status: String!) {
            confirmPayment(
                payment_id: $id,
                status: $status
            ) {
                id
                status
            }
        }
    `;

    await gql(mutation, {
        id: PAYMENT_ID,
        status: "confirmed"
    });

    alert("Pembayaran berhasil dikonfirmasi");
    window.location.href = "/admin/payments";
}

/* =========================
   REJECT PAYMENT
========================= */
async function rejectPayment() {
    if (!confirm("Yakin ingin reject pembayaran ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            rejectPayment(payment_id: $id) {
                id
                status
            }
        }
    `;

    await gql(mutation, { id: PAYMENT_ID });

    alert("Pembayaran berhasil direject");
    window.location.href = "/admin/payments";
}
