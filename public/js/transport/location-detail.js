const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    console.log("LOCATION_ID =", LOCATION_ID);
    loadSchedules();
});

async function gql(query, variables = {}) {
    const res = await fetch(API_URL, {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query, variables }),
    });

    const json = await res.json();

    if (json.errors) {
        console.error("GraphQL Error:", json.errors);
        throw new Error(json.errors[0].message);
    }

    return json.data;
}

async function loadSchedules() {
    const container = document.getElementById("scheduleContainer");
    const loading = document.getElementById("loadingIndicator");

    if (!container) {
        console.error("scheduleContainer NOT FOUND");
        return;
    }

    const query = `
        query ($id: ID!) {
            transportSchedulesByOrigin(location_id: $id) {
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
    `;

    try {
        const data = await gql(query, { id: LOCATION_ID });

        console.log("SCHEDULE DATA =", data.transportSchedulesByOrigin);

        loading.style.display = "none";
        container.innerHTML = "";

        if (!data.transportSchedulesByOrigin.length) {
            container.innerHTML = `
                <p class="text-gray-500">
                    Tidak ada jadwal dari lokasi ini
                </p>
            `;
            return;
        }

        data.transportSchedulesByOrigin.forEach(s => {
            const transportName = s.transport?.name ?? "-";
            const destName = s.destinationLocation?.name ?? "-";
            const destCity = s.destinationLocation?.city ?? "-";

            container.innerHTML += `
                <div class="border rounded-xl p-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-lg">
                            ✈️ ${transportName}
                        </p>

                        <p class="text-sm text-gray-600">
                            Ke ${destName} (${destCity})
                        </p>

                        <p class="text-sm">
                            🕒 ${new Date(s.departure_time)
                                .toLocaleString("id-ID")}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="font-semibold text-blue-600">
                            Rp ${Number(s.price).toLocaleString("id-ID")}
                        </p>

                        <button
                            onclick="bookTransport(${s.id})"
                            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg">
                            Booking
                        </button>   
                    </div>
                </div>
            `;
        });

    } catch (e) {
        console.error("LOAD FAILED:", e);
        loading.innerText = "Gagal memuat jadwal";
    }
}
function bookTransport(scheduleId) {
    // redirect ke halaman booking transport (konfirmasi)
    window.location.href =
        `/booking/transport?schedule_id=${scheduleId}&qty=1`;
}
