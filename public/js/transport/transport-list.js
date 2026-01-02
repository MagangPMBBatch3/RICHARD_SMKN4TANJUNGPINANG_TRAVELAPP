const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    loadTransportLocations();
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

async function loadTransportLocations() {
    const container = document.getElementById("locationContainer");
    const loading = document.getElementById("locationLoading");

    if (!container) return;

    loading.classList.remove("hidden");

    const query = `
        query {
            transportLocations {
                id
                type
                name
                city
                photo
            }
        }
    `;

    try {
        const data = await gql(query);

        container.innerHTML = "";

        if (data.transportLocations.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center text-gray-500">
                    Belum ada lokasi transport
                </div>
            `;
            return;
        }

        data.transportLocations.forEach(loc => {
            container.innerHTML += `
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition cursor-pointer"
                     onclick="goToLocation(${loc.id})">
                    <div class="h-40 bg-gray-100 rounded-t-xl overflow-hidden">
                        ${
                            loc.photo
                                ? `<img src="/storage/${loc.photo}"
                                       class="w-full h-full object-cover">`
                                : `<div class="flex items-center justify-center h-full text-gray-400">
                                       No Image
                                   </div>`
                        }
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-lg">
                            ${loc.name}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            ${loc.city}
                        </p>

                        <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full
                            ${loc.type === "airport" ? "bg-blue-100 text-blue-600" : "bg-green-100 text-green-600"}">
                            ${loc.type === "airport" ? "✈️ Bandara" : "🚢 Pelabuhan"}
                        </span>
                    </div>
                </div>
            `;
        });
    } catch (e) {
        container.innerHTML = `
            <div class="col-span-full text-center text-red-500">
                Gagal memuat lokasi
            </div>
        `;
    } finally {
        loading.classList.add("hidden");
    }
}

function goToLocation(id) {
    window.location.href = `/transport/locations/${id}`;
}
