const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    loadTransports();
    loadLocations();

    document
        .getElementById("transport_id")
        .addEventListener("change", loadSchedules);

    document
        .getElementById("scheduleForm")
        .addEventListener("submit", saveSchedule);

    document
        .getElementById("btnReset")
        .addEventListener("click", resetForm);
});

function toLaravelDateTime(value) {
    if (!value) return null;
    return value.replace("T", " ") + ":00";
}

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



async function loadTransports() {
    const query = `
        query {
            transports {
                id
                name
            }
        }
    `;

    const data = await gql(query);
    const select = document.getElementById("transport_id");

    select.innerHTML = `<option value="">Pilih Pesawat</option>`;

    data.transports.forEach(t => {
        select.innerHTML += `
            <option value="${t.id}">${t.name}</option>
        `;
    });

    if (data.transports.length > 0) {
        select.value = data.transports[0].id;
        loadSchedules();
    }
}

async function loadLocations() {
    const query = `
        query {
            transportLocations {
                id
                name
                city
            }
        }
    `;

    const data = await gql(query);

    const origins = document.getElementById("origin_location_id");
    const destinations = document.getElementById("destination_location_id");

    origins.innerHTML = `<option value="">Pilih Origin</option>`;
    destinations.innerHTML = `<option value="">Pilih Destination</option>`;

    data.transportLocations.forEach(loc => {
        const option = `
            <option value="${loc.id}">
                ${loc.name} (${loc.city})
            </option>
        `;
        origins.innerHTML += option;
        destinations.innerHTML += option;
    });
}

async function loadSchedules() {
    const transportId = document.getElementById("transport_id").value;
    const tbody = document.getElementById("scheduleTableBody");

    tbody.innerHTML = "";
    if (!transportId) return;

    const query = `
        query ($transport_id: ID!) {
            transportSchedulesByTransport(transport_id: $transport_id) {
                id
                departure_time
                arrival_time
                price
                originLocation {
                    name
                    city
                }
                destinationLocation {
                    name
                    city
                }
            }
        }
    `;

    const data = await gql(query, { transport_id: transportId });

    // ✅ SATU-SATUNYA ARRAY YANG DIPAKAI
    const schedules = data?.transportSchedulesByTransport ?? [];

    if (schedules.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">
                    Belum ada schedule
                </td>
            </tr>
        `;
        return;
    }

    schedules.forEach(s => {
        tbody.innerHTML += `
            <tr>
                <td class="border p-2">
                    ${document.querySelector('#transport_id option:checked').textContent}
                </td>
                <td class="border p-2">
                    ${s.originLocation.name} (${s.originLocation.city})
                </td>
                <td class="border p-2">
                    ${s.destinationLocation.name} (${s.destinationLocation.city})
                </td>
                <td class="border p-2">
                    ${new Date(s.departure_time).toLocaleString("id-ID")}
                </td>
                <td class="border p-2">
                    Rp ${Number(s.price).toLocaleString("id-ID")}
                </td>
                <td class="border p-2 text-center space-x-1">
                    <button onclick="editSchedule(${s.id})"
                        class="px-2 py-1 bg-yellow-400 text-white rounded">✏️</button>
                    <button onclick="deleteSchedule(${s.id})"
                        class="px-2 py-1 bg-red-500 text-white rounded">🗑️</button>
                </td>
            </tr>
        `;
    });
}


async function saveSchedule(e) {
    e.preventDefault();

    const scheduleId = document.getElementById("schedule_id").value || null;

    const transportId = transport_id.value;
    const originId = origin_location_id.value;
    const destinationId = destination_location_id.value;
    const departure = departure_time.value;
    const arrival = arrival_time.value;
    const priceVal = price.value;

    if (!transportId || !originId || !destinationId || !departure || !priceVal) {
        alert("Lengkapi semua field wajib");
        return;
    }

    if (originId === destinationId) {
        alert("Origin dan destination tidak boleh sama");
        return;
    }

    const mutation = scheduleId
        ? `
            mutation (
                $id: ID!
                $origin_location_id: ID!
                $destination_location_id: ID!
                $departure_time: DateTime!
                $arrival_time: DateTime
                $price: Float!
            ) {
                updateTransportSchedule(
                    id: $id
                    origin_location_id: $origin_location_id
                    destination_location_id: $destination_location_id
                    departure_time: $departure_time
                    arrival_time: $arrival_time
                    price: $price
                ) { id }
            }
        `
        : `
            mutation (
                $transport_id: ID!
                $origin_location_id: ID!
                $destination_location_id: ID!
                $departure_time: DateTime!
                $arrival_time: DateTime
                $price: Float!
            ) {
                createTransportSchedule(
                    transport_id: $transport_id
                    origin_location_id: $origin_location_id
                    destination_location_id: $destination_location_id
                    departure_time: $departure_time
                    arrival_time: $arrival_time
                    price: $price
                ) { id }
            }
        `;

    await gql(mutation, {
        id: scheduleId,
        transport_id: transportId,
        origin_location_id: originId,
        destination_location_id: destinationId,
        departure_time: toLaravelDateTime(departure),
        arrival_time: arrival ? toLaravelDateTime(arrival) : null,
        price: parseFloat(priceVal),
    });

    resetForm();
    loadSchedules();
}



async function deleteSchedule(id) {
    if (!confirm("Hapus schedule ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            deleteTransportSchedule(id: $id)
        }
    `;

    await gql(mutation, { id });
    loadSchedules();
}

async function editSchedule(id) {
    const query = `
        query ($id: ID!) {
            transportSchedule(id: $id) {
                id
                transport_id
                origin_location_id
                destination_location_id
                departure_time
                arrival_time
                price
            }
        }
    `;

    const data = await gql(query, { id });
    const s = data.transportSchedule;

    document.getElementById("schedule_id").value = s.id;
    document.getElementById("transport_id").value = s.transport_id;
    document.getElementById("origin_location_id").value = s.origin_location_id;
    document.getElementById("destination_location_id").value = s.destination_location_id;
    document.getElementById("departure_time").value = s.departure_time.replace(" ", "T").slice(0,16);
    document.getElementById("arrival_time").value = s.arrival_time
        ? s.arrival_time.replace(" ", "T").slice(0,16)
        : "";
    document.getElementById("price").value = s.price;

    document.getElementById("formTitle").innerText = "Edit Schedule";
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function resetForm() {
    document.getElementById("schedule_id").value = "";
    document.getElementById("origin_location_id").value = "";
    document.getElementById("destination_location_id").value = "";
    document.getElementById("departure_time").value = "";
    document.getElementById("arrival_time").value = "";
    document.getElementById("price").value = "";

    document.getElementById("formTitle").innerText = "Tambah Schedule";
}


