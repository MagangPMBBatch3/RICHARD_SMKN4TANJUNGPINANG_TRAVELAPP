document.addEventListener("DOMContentLoaded", loadPayments);

async function gql(query, variables = {}) {
    const res = await fetch("/graphql", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query, variables })
    });

    const json = await res.json();
    if (json.errors) throw new Error(json.errors[0].message);
    return json.data;
}

async function loadPayments() {
    const tbody = document.getElementById("paymentBody");

    const query = `
        query {
            pendingPayments {
                id
                amount
                proof
                booking {
                    booking_code
                    user { name }
                }
            }
        }
    `;

    const data = await gql(query);
    tbody.innerHTML = "";

    if (!data.pendingPayments.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">
                    Tidak ada pembayaran pending
                </td>
            </tr>
        `;
        return;
    }

    data.pendingPayments.forEach(p => {

    const bookingCode = p.booking?.booking_code ?? "-";
    const userName = p.booking?.user?.name ?? "-";
    const amount = Number(p.amount || 0).toLocaleString("id-ID");
    const proofLink = p.proof
        ? `<a href="/storage/${p.proof}" target="_blank"
              class="text-blue-600 underline">Lihat Bukti</a>`
        : "-";

    tbody.innerHTML += `
    <tr>
        <td class="p-3 border">${bookingCode}</td>
        <td class="p-3 border">${userName}</td>
        <td class="p-3 border">Rp ${amount}</td>
        <td class="p-3 border text-center">${proofLink}</td>

        <td class="p-3 border text-center">
            <a href="/admin/payments/${p.id}"
                class="px-3 py-1 bg-blue-600 text-white rounded">
                Detail
                </a>
            </td>
        </tr>
    `;

});

}

async function confirmPayment(id) {
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
        id,
        status: "confirmed"
    });

    loadPayments();
}

async function rejectPayment(id) {
    if (!confirm("Yakin reject pembayaran ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            confirmPayment(
                payment_id: $id,
                status: "failed"
            ) {
                id
            }
        }
    `;

    await gql(mutation, { id });

    alert("Pembayaran direject");
    loadPayments();
}
