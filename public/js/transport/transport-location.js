const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("locationForm");
    const table = document.getElementById("locationTableBody");

    if (!form || !table) return;

    loadLocations();

    form.addEventListener("submit", saveLocation);
    document.getElementById("btnReset").addEventListener("click", resetForm);
    document.getElementById("photo").addEventListener("change", previewPhoto);
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

function previewPhoto(e) {
    const file = e.target.files[0];
    const preview = document.getElementById("previewImage");

    if (!preview) return;

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove("hidden");
    } else {
        preview.classList.add("hidden");
    }
}

async function loadLocations() {
    const query = `
        query {
            transportLocations {
                id
                type
                name
                code
                city
                photo
            }
        }
    `;

    try {
        const data = await gql(query);
        const tbody = document.getElementById("locationTableBody");

        tbody.innerHTML = "";

        if (data.transportLocations.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">
                        Belum ada lokasi
                    </td>
                </tr>
            `;
            return;
        }

        data.transportLocations.forEach(loc => {
            tbody.innerHTML += `
                <tr>
                    <td class="border px-3 py-2">${loc.id}</td>
                    <td class="border px-3 py-2">${loc.type}</td>
                    <td class="border px-3 py-2">${loc.name}</td>
                    <td class="border px-3 py-2">${loc.code || "-"}</td>
                    <td class="border px-3 py-2">${loc.city}</td>
                    <td class="border px-3 py-2">
                        ${
                            loc.photo
                                ? `<img src="/storage/${loc.photo}" class="w-16 h-12 object-cover rounded">`
                                : "-"
                        }
                    </td>
                    <td class="border px-3 py-2 space-x-1">
                        <button onclick="editLocation(${loc.id})"
                            class="bg-yellow-400 px-2 py-1 text-white rounded">✏️</button>
                        <button onclick="deleteLocation(${loc.id})"
                            class="bg-red-500 px-2 py-1 text-white rounded">🗑️</button>
                    </td>
                </tr>
            `;
        });
    } catch (e) {
        alert("Gagal load lokasi: " + e.message);
    }
}

async function saveLocation(e) {
    e.preventDefault();

    const id = document.getElementById("location_id").value || null;
    const type = document.getElementById("type").value;
    const name = document.getElementById("name").value;
    const code = document.getElementById("code").value;
    const city = document.getElementById("city").value;
    const photo = document.getElementById("photo").files[0];

    const query = id
        ? `
            mutation ($id: ID!, $type: String, $name: String, $code: String, $city: String, $photo: Upload) {
                updateTransportLocation(
                    id: $id
                    type: $type
                    name: $name
                    code: $code
                    city: $city
                    photo: $photo
                ) { id }
            }
        `
        : `
            mutation ($type: String!, $name: String!, $code: String, $city: String!, $photo: Upload) {
                createTransportLocation(
                    type: $type
                    name: $name
                    code: $code
                    city: $city
                    photo: $photo
                ) { id }
            }
        `;

    const variables = { id, type, name, code, city, photo: null };
    const formData = new FormData();

    formData.append("operations", JSON.stringify({ query, variables }));

    if (photo) {
        formData.append("map", JSON.stringify({ 0: ["variables.photo"] }));
        formData.append("0", photo);
    } else {
        formData.append("map", JSON.stringify({}));
    }

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            credentials: "same-origin",
            body: formData,
        });

        const json = await res.json();
        if (json.errors) throw new Error(json.errors[0].message);

        resetForm();
        loadLocations();
    } catch (e) {
        alert("Gagal simpan: " + e.message);
    }
}

async function editLocation(id) {
    const query = `
        query ($id: ID!) {
            transportLocation(id: $id) {
                id
                type
                name
                code
                city
            }
        }
    `;

    const data = await gql(query, { id });
    const d = data.transportLocation;

    document.getElementById("location_id").value = d.id;
    document.getElementById("type").value = d.type;
    document.getElementById("name").value = d.name;
    document.getElementById("code").value = d.code || "";
    document.getElementById("city").value = d.city;

    document.getElementById("formTitle").innerText = "Edit Lokasi Transport";
    window.scrollTo({ top: 0, behavior: "smooth" });
}

async function deleteLocation(id) {
    if (!confirm("Hapus lokasi ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            deleteTransportLocation(id: $id)
        }
    `;

    await gql(mutation, { id });
    loadLocations();
}

function resetForm() {
    document.getElementById("locationForm").reset();
    document.getElementById("location_id").value = "";
    document.getElementById("formTitle").innerText = "Tambah Lokasi Transport";
    document.getElementById("previewImage").classList.add("hidden");
}
