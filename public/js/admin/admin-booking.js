document.addEventListener("DOMContentLoaded", () => {
    loadBookings();

    // Realtime search
    document.getElementById("searchInput").addEventListener("input", loadBookings);

    // Filter status
    document.getElementById("statusFilter").addEventListener("change", loadBookings);
});

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

async function loadBookings() {
    const search = document.getElementById("searchInput").value;
    const status = document.getElementById("statusFilter").value;

    const query = `
        query ($search: String, $status: String) {
            allBookings(search: $search, status: $status) {
                id
                booking_code
                total_price
                status
                created_at
                user { name }
            }
        }
    `;

    const data = await gql(query, { search, status });
    const body = document.getElementById("bookingBody");
    body.innerHTML = "";

    if (!data.allBookings.length) {
        body.innerHTML = `
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">
                    Tidak ada data
                </td>
            </tr>
        `;
        return;
    }

    data.allBookings.forEach(b => {
        body.innerHTML += `
            <tr>
                <td class="border p-2">${b.booking_code}</td>
                <td class="border p-2">${b.user?.name ?? "-"}</td>
                <td class="border p-2">Rp ${Number(b.total_price).toLocaleString("id-ID")}</td>
                <td class="border p-2">${b.status}</td>
                <td class="border p-2">${new Date(b.created_at).toLocaleDateString("id-ID")}</td>
                <td class="border p-2 text-center">
                    <a href="/booking/${b.id}" class="px-3 py-1 bg-blue-600 text-white rounded">
                        Detail
                    </a>
                </td>
            </tr>
        `;
    });
}
