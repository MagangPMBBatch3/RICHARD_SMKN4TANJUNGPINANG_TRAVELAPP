const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    loadTransports();

    document.getElementById("transportForm").addEventListener("submit", saveTransport);
    document.getElementById("btnReset").addEventListener("click", resetForm);
    document.getElementById("photo").addEventListener("change", previewPhoto);
});

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute("content");
}

function previewPhoto(e) {
    const file = e.target.files[0];
    const preview = document.getElementById("previewImage");

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove("hidden");
    } else {
        preview.classList.add("hidden");
    }
}

async function loadTransports() {
    const query = `
        query {
            transports {
                id
                type
                name
                code
                capacity
                price_per_seat
                photo
            }
        }
    `;

    try {
        const res = await fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: JSON.stringify({ query }),
        });

        const json = await res.json();
        if (json.errors) throw new Error(json.errors[0].message);

        const tbody = document.getElementById("transportTableBody");
        tbody.innerHTML = "";

        json.data.transports.forEach(t => {
            const row = document.createElement("tr");
            row.innerHTML = `
        <td class="border px-3 py-2">${t.name}</td>
            <td class="border px-3 py-2">${t.type}</td>
            <td class="border px-3 py-2">${t.capacity || "-"}</td>
            <td class="border px-3 py-2">
            ${t.price_per_seat ? "Rp " + Number(t.price_per_seat).toLocaleString("id-ID") : "-"}
        </td>
            <td class="border px-3 py-2">
            ${t.photo ? `<img src="/storage/${t.photo}" class="w-16 rounded">` : "-"}
        </td>
            <td class="border px-3 py-2 space-x-1">
            <button onclick="editTransport(${t.id})" class="bg-yellow-400 text-white px-2 py-1 rounded">✏️</button>
            <button onclick="deleteTransport(${t.id})" class="bg-red-500 text-white px-2 py-1 rounded">🗑️</button>
        </td>
            `;
            tbody.appendChild(row);
        });
    } catch (e) {
        alert("❌ Gagal load transport: " + e.message);
    }
}

async function saveTransport(e) {
    e.preventDefault();

    const id = document.getElementById("transport_id").value || null;
    const type = document.getElementById("type").value;
    const name = document.getElementById("name").value;
    const code = document.getElementById("code").value;
    const capacity = document.getElementById("capacity").value;
    const price_per_seat = document.getElementById("price_per_seat").value;
    const photo = document.getElementById("photo").files[0];

    const query = id
        ? `
        mutation ($id: ID!, $type: String, $name: String, $code: String, $capacity: Int, $price_per_seat: Float, $photo: Upload) {
            updateTransport(
                id: $id
                type: $type
                name: $name
                code: $code
                capacity: $capacity
                price_per_seat: $price_per_seat
                photo: $photo
            ) { id }
        }`
        : `
        mutation ($type: String!, $name: String!, $code: String, $capacity: Int, $price_per_seat: Float, $photo: Upload) {
            createTransport(
                type: $type
                name: $name
                code: $code
                capacity: $capacity
                price_per_seat: $price_per_seat
                photo: $photo
            ) { id }
        }`;

    const variables = {
        id,
        type,
        name,
        code,
        capacity: capacity ? parseInt(capacity) : null,
        price_per_seat: price_per_seat ? parseFloat(price_per_seat) : null,
        photo: null,
    };

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
            headers: { "X-CSRF-TOKEN": csrfToken() },
            body: formData,
        });

        const json = await res.json();
        if (json.errors) throw new Error(json.errors[0].message);

        alert("✅ Transport berhasil disimpan");
        resetForm();
        loadTransports();
    } catch (e) {
        alert("❌ Gagal simpan: " + e.message);
    }
}

async function editTransport(id) {
    const query = `
        query ($id: ID!) {
            transport(id: $id) {
                id
                type
                name
                code
                capacity
                price_per_seat
            }
        }
    `;

    const res = await fetch(API_URL, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken(),
        },
        body: JSON.stringify({ query, variables: { id } }),
    });

    const data = (await res.json()).data.transport;

    document.getElementById("transport_id").value = data.id;
    document.getElementById("type").value = data.type;
    document.getElementById("name").value = data.name;
    document.getElementById("code").value = data.code || "";
    document.getElementById("capacity").value = data.capacity || "";
    document.getElementById("price_per_seat").value = data.price_per_seat || "";

    document.getElementById("formTitle").innerText = "Edit Transport";
    window.scrollTo({ top: 0, behavior: "smooth" });
}

async function deleteTransport(id) {
    if (!confirm("Yakin hapus transport ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            deleteTransport(id: $id)
        }
    `;

    const res = await fetch(API_URL, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken(),
        },
        body: JSON.stringify({ query: mutation, variables: { id } }),
    });

    const json = await res.json();
    if (json.errors) {
        alert("❌ Gagal hapus");
        return;
    }

    loadTransports();
}

function resetForm() {
    document.getElementById("transportForm").reset();
    document.getElementById("transport_id").value = "";
    document.getElementById("formTitle").innerText = "Tambah Transport";
    document.getElementById("previewImage").classList.add("hidden");
}
