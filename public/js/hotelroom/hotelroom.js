const API_URL = "/graphql";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

function graphqlFetch(body) {
    return fetch(API_URL, {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(body)
    });
}

document.addEventListener("DOMContentLoaded", () => {
    init();
});

async function init() {
    await loadDropdowns();
    await loadHotelRooms();
    bindEvents();
    preselectHotelFromUrl();
}

function bindEvents() {
    const form = document.getElementById("createRoomForm") || document.getElementById("hotelRoomForm");
    if (form) form.addEventListener("submit", handleSubmit);

    const btnReset = document.getElementById("btnReset");
    if (btnReset) btnReset.addEventListener("click", resetForm);

    const btnAdd = document.getElementById("btnAddPhoto");
    if (btnAdd) btnAdd.addEventListener("click", addPhotoInput);

    document.body.addEventListener("click", e => {
        if (e.target?.classList.contains("removePhoto")) {
            e.target.parentElement.remove();
        }
    });

    document.body.addEventListener("change", e => {
        if (e.target?.classList.contains("photoInput")) {
            previewPhotoInput(e.target);
        }
    });
}

async function loadDropdowns() {
    const query = `
        query {
            allHotels { id name }
            allRoomTypes { id name }
        }
    `;

    const res = await graphqlFetch({ query });
    const { data } = await res.json();

    const hotelSelect = document.getElementById("hotel_id");
    const typeSelect = document.getElementById("room_type_id");

    if (hotelSelect) hotelSelect.innerHTML = `<option value="">-- Pilih Hotel --</option>`;
    if (typeSelect) typeSelect.innerHTML = `<option value="">-- Pilih Tipe Kamar --</option>`;

    data?.allHotels?.forEach(h => {
        hotelSelect.innerHTML += `<option value="${h.id}">${h.name}</option>`;
    });

    data?.allRoomTypes?.forEach(t => {
        typeSelect.innerHTML += `<option value="${t.id}">${t.name}</option>`;
    });
}

async function loadHotelRooms() {
    const query = `
        query {
            allHotelRooms {
                id
                name
                price
                quantity
                hotel { name }
                room_type { name }
                photos { photo }
            }
        }
    `;

    const res = await graphqlFetch({ query });
    const { data } = await res.json();

    const tbody = document.getElementById("hotelRoomTableBody");
    if (!tbody) return;

    tbody.innerHTML = "";

    data?.allHotelRooms?.forEach(room => {
        const img = room.photos?.[0]?.photo
            ? `<img src="/storage/${room.photos[0].photo}" class="w-16 h-12 object-cover rounded">`
            : "-";

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="border px-3 py-2">${room.id}</td>
            <td class="border px-3 py-2">${room.hotel?.name}</td>
            <td class="border px-3 py-2">${room.room_type?.name}</td>
            <td class="border px-3 py-2">${room.name}</td>
            <td class="border px-3 py-2">Rp ${Number(room.price).toLocaleString()}</td>
            <td class="border px-3 py-2">${room.quantity}</td>
            <td class="border px-3 py-2">${img}</td>
            <td class="border px-3 py-2">
                <button onclick="editHotelRoom(${room.id})" class="bg-yellow-400 px-2 py-1 text-white rounded">✏️</button>
                <button onclick="deleteHotelRoom(${room.id})" class="bg-red-500 px-2 py-1 text-white rounded">🗑️</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function handleSubmit(e) {
    e.preventDefault();

    const id = document.getElementById("hotel_room_id")?.value;
    const hotel_id = +document.getElementById("hotel_id").value;
    const room_type_id = +document.getElementById("room_type_id").value;
    const name = document.getElementById("name").value.trim();
    const price = +document.getElementById("price").value;
    const quantity = +document.getElementById("quantity").value;

    if (!hotel_id || !room_type_id || !name || price <= 0) {
        return alert("Data tidak valid");
    }

    if (!id) {
        const photos = await collectAllPhotosAsBase64();

        const res = await graphqlFetch({
            query: `
                mutation($input: CreateHotelRoomWithPhotosInput!) {
                    createHotelRoomWithPhotos(input: $input) { id }
                }
            `,
            variables: { input: { hotel_id, room_type_id, name, price, quantity, photos } }
        });

        const json = await res.json();
        if (json.errors) return alert(json.errors[0].message);

        alert("Kamar berhasil dibuat");
    } else {
        const res = await graphqlFetch({
            query: `
                mutation($id: ID!, $input: UpdateHotelRoomInput!) {
                    updateHotelRoom(id: $id, input: $input) { id }
                }
            `,
            variables: { id, input: { hotel_id, room_type_id, name, price, quantity } }
        });

        const json = await res.json();
        if (json.errors) return alert(json.errors[0].message);

        alert("Kamar berhasil diupdate");
    }

    resetForm();
    loadHotelRooms();
}

async function collectAllPhotosAsBase64() {
    const files = document.querySelectorAll(".photoInput");
    const arr = [];
    for (let f of files) {
        if (!f.files.length) continue;
        arr.push(await readFileAsDataURL(f.files[0]));
    }
    return arr;
}

function readFileAsDataURL(file) {
    return new Promise(resolve => {
        const r = new FileReader();
        r.onload = () => resolve(r.result);
        r.readAsDataURL(file);
    });
}

function addPhotoInput() {
    const div = document.createElement("div");
    div.innerHTML = `
        <input type="file" class="photoInput border p-2 mr-2">
        <button type="button" class="removePhoto text-red-500">✖</button>
    `;
    document.getElementById("photoWrapper").appendChild(div);
}

function previewPhotoInput(input) {
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.createElement("img");
        img.src = e.target.result;
        img.className = "w-20 h-20 mr-2 rounded object-cover";
        document.getElementById("photoPreview").appendChild(img);
    };
    reader.readAsDataURL(input.files[0]);
}

function resetForm() {
    document.querySelector("form")?.reset();
    document.getElementById("hotel_room_id").value = "";
    document.getElementById("photoPreview").innerHTML = "";
}

async function editHotelRoom(id) {
    const res = await graphqlFetch({
        query: `
            query($id: ID!) {
                getHotelRoom(id: $id) {
                    id hotel_id room_type_id name price quantity
                }
            }
        `,
        variables: { id }
    });

    const room = (await res.json()).data.getHotelRoom;

    Object.entries(room).forEach(([k, v]) => {
        const el = document.getElementById(k);
        if (el) el.value = v;
    });

    window.scrollTo({ top: 0, behavior: "smooth" });
}

async function deleteHotelRoom(id) {
    if (!confirm("Hapus kamar ini?")) return;

    const res = await graphqlFetch({
        query: `mutation($id: ID!){ deleteHotelRoom(id:$id){ id } }`,
        variables: { id }
    });

    if ((await res.json()).errors) {
        return alert("Gagal menghapus");
    }

    loadHotelRooms();
}

function preselectHotelFromUrl() {
    const hid = new URLSearchParams(window.location.search).get("hotel_id");
    if (hid) document.getElementById("hotel_id").value = hid;
}
