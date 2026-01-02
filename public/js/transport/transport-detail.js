const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    loadLocationDetail();
});

async function gql(query, variables = {}) {
    const res = await fetch(API_URL, {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
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

async function loadLocationDetail() {
    const query = `
        query ($id: ID!) {
            transportLocation(id: $id) {
                id
                name
                city
                type

                departureSchedules {
                    id
                    departure_time
                    price
                    transport {
                        name
                    }
                    destinationLocation {
                        name
                        city
                    }
                }
            }
        }
    `;

    try {
        const data = await gql(query, { id: LOCATION_ID });
        const loc = data.transportLocation;

        document.getElementById("locationName").innerText =
            `${loc.name} (${loc.type === "airplane" ? "✈️ Bandara" : "🚢 Pelabuhan"})`;

        document.getElementById("locationCity").innerText = loc.city;

        renderSchedules(loc.departureSchedules);
    } catch (e) {
        document.getElementById("scheduleTableBody").innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-red-500 p-4">
                    Gagal memuat data
                </td>
            </tr>
        `;
    }
}

function renderSchedules(schedules) {
    const tbody = document.getElementById("scheduleTableBody");
    tbody.innerHTML = "";

    if (schedules.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-gray-500 p-4">
                    Belum ada jadwal dari lokasi ini
                </td>
            </tr>
        `;
        return;
    }

    schedules.forEach(s => {
        tbody.innerHTML += `
            <tr>
                <td class="border p-2">
                    ${s.destinationLocation.name} (${s.destinationLocation.city})
                </td>
                <td class="border p-2">
                    ${s.transport.name}
                </td>
                <td class="border p-2">
                    ${new Date(s.departure_time).toLocaleString("id-ID")}
                </td>
                <td class="border p-2">
                    Rp ${Number(s.price).toLocaleString("id-ID")}
                </td>
                <td class="border p-2 text-center">
                    <a href="/booking/create?schedule=${s.id}"
                       class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Pilih
                    </a>
                </td>
            </tr>
        `;
    });
}
