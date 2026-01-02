const API_URL = "/graphql";
let allHotels = []; 

document.addEventListener("DOMContentLoaded", () => {
    loadHotels();

    document.getElementById("hotelForm").addEventListener("submit", saveHotel);
    document.getElementById("btnReset").addEventListener("click", resetForm);
    document.getElementById("photo").addEventListener("change", previewPhoto);

    document.getElementById("searchHotel")?.addEventListener("input", function () {
        const keyword = this.value.toLowerCase();

        const filtered = allHotels.filter(hotel =>
            hotel.name.toLowerCase().includes(keyword) ||
            hotel.location.toLowerCase().includes(keyword) ||
            (hotel.description && hotel.description.toLowerCase().includes(keyword))
        );

        renderHotels(filtered);
    });
});

function csrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
}

function previewPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("previewImage");

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove("hidden");
    } else {
        preview.classList.add("hidden");
    }
}

async function loadHotels() {
    const query = `
        query {
            allHotels {
                id
                name
                location
                description
                photo_url
            }
        }
    `;

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: JSON.stringify({ query }),
        });

        const json = await res.json();
        if (json.errors) throw new Error(json.errors[0].message);

        allHotels = json.data.allHotels; 
        renderHotels(allHotels); 
    } catch (e) {
        alert("❌ Gagal memuat hotel: " + e.message);
    }
}

function renderHotels(list) {
    const tbody = document.getElementById("hotelTableBody");
    tbody.innerHTML = "";

    list.forEach(hotel => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="border px-4 py-2">${hotel.id}</td>
            <td class="border px-4 py-2">${hotel.name}</td>
            <td class="border px-4 py-2">${hotel.location}</td>
            <td class="border px-4 py-2">${hotel.description || "-"}</td>
            <td class="border px-4 py-2">
                ${
                    hotel.photo_url
                        ? `<img src="/storage/${hotel.photo_url}" class="w-16 h-12 object-cover rounded">`
                        : "-"
                }
            </td>
            <td class="border px-4 py-2 space-x-1">
                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded"
                    onclick="editHotel(${hotel.id})">✏️ Edit</button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded"
                    onclick="deleteHotel(${hotel.id})">🗑️ Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function saveHotel(e) {
    e.preventDefault();

    const id = document.getElementById("hotel_id").value || null;
    const name = document.getElementById("name").value;
    const location = document.getElementById("location").value;
    const description = document.getElementById("description").value;
    const photo = document.getElementById("photo").files[0];

    const query = id
        ? `
            mutation ($id: ID!, $name: String, $location: String, $description: String, $photo: Upload) {
                updateHotel(
                    input: {
                        id: $id
                        name: $name
                        location: $location
                        description: $description
                        photo: $photo
                    }
                ) { id }
            }
        `
        : `
            mutation ($name: String!, $location: String!, $description: String, $photo: Upload) {
                createHotel(
                    input: {
                        name: $name
                        location: $location
                        description: $description
                        photo: $photo
                    }
                ) { id }
            }
        `;

    const formData = new FormData();
    const variables = { id, name, location, description, photo: null };

    formData.append("operations", JSON.stringify({ query, variables }));

    if (photo) {
        formData.append("map", JSON.stringify({ "0": ["variables.photo"] }));
        formData.append("0", photo);
    } else {
        formData.append("map", JSON.stringify({}));
    }

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: formData,
        });

        const json = await res.json();
        if (json.errors) throw new Error(json.errors[0].message);

        alert("✅ Hotel berhasil disimpan!");
        resetForm();
        loadHotels();
    } catch (e) {
        alert("⚠️ Gagal menyimpan: " + e.message);
    }
}

async function editHotel(id) {
    const query = `
        query ($id: ID!) {
            findHotel(id: $id) {
                id
                name
                location
                description
            }
        }
    `;

    const res = await fetch(API_URL, {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken(),
        },
        body: JSON.stringify({ query, variables: { id } }),
    });

    const json = await res.json();
    const data = json.data.findHotel;

    document.getElementById("hotel_id").value = data.id;
    document.getElementById("name").value = data.name;
    document.getElementById("location").value = data.location;
    document.getElementById("description").value = data.description || "";
    document.getElementById("formTitle").innerText = "Edit Hotel";

    window.scrollTo({ top: 0, behavior: "smooth" });
}

async function deleteHotel(id) {
    if (!confirm("Yakin ingin menghapus hotel ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            deleteHotel(id: $id)
        }
    `;

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: JSON.stringify({ query: mutation, variables: { id } }),
        });

        const json = await res.json();
        if (json.errors) throw new Error(json.errors[0].message);

        alert("🗑️ Hotel berhasil dihapus!");
        loadHotels();
    } catch (e) {
        alert("❌ Gagal menghapus: " + e.message);
    }
}

function resetForm() {
    document.getElementById("hotelForm").reset();
    document.getElementById("hotel_id").value = "";
    document.getElementById("formTitle").innerText = "Tambah Hotel";
    document.getElementById("previewImage").classList.add("hidden");
}
