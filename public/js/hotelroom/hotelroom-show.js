document.addEventListener("DOMContentLoaded", async () => {

    /* ================= ELEMENT ================= */
    const roomPhoto = document.getElementById("roomPhoto");
    const roomName = document.getElementById("roomName");
    const hotelName = document.getElementById("hotelName");
    const roomPrice = document.getElementById("roomPrice");
    const roomQuantity = document.getElementById("roomQuantity");
    const roomDescription = document.getElementById("roomDescription");

    const checkIn = document.getElementById("checkIn");
    const checkOut = document.getElementById("checkOut");
    const roomCount = document.getElementById("roomCount");
    const totalPriceEl = document.getElementById("totalPrice");
    const btnBooking = document.getElementById("btnBooking");

    let PRICE = 0;
    let MAX_QTY = 0;
    let ROOM = null;

    /* ================= GRAPHQL HELPER ================= */
    async function graphql(query, variables = {}) {
        const res = await fetch("/graphql", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ query, variables }),
        });
        return res.json();
    }

    /* ================= LOAD ROOM ================= */
    const query = `
        query GetHotelRoom($id: ID!) {
            getHotelRoom(id: $id) {
                id
                name
                price
                quantity
                description
                photos { photo }
                hotel { id name }
            }
        }
    `;

    const res = await graphql(query, { id: ROOM_ID });

    if (res.errors || !res.data?.getHotelRoom) {
        alert("Gagal memuat data kamar");
        return;
    }

    ROOM = res.data.getHotelRoom;

    roomName.textContent = ROOM.name;
    hotelName.textContent = `Hotel: ${ROOM.hotel.name}`;
    roomPrice.textContent = `Rp ${Number(ROOM.price).toLocaleString()} / malam`;
    roomQuantity.textContent = `Tersisa ${ROOM.quantity} kamar`;
    roomDescription.textContent = ROOM.description || "-";

    roomPhoto.src = ROOM.photos?.[0]?.photo
        ? `/storage/${ROOM.photos[0].photo}`
        : "https://via.placeholder.com/800x400";

    PRICE = Number(ROOM.price);
    MAX_QTY = Number(ROOM.quantity);
    roomCount.max = MAX_QTY;

    /* ================= HITUNG TOTAL ================= */
    function calculateTotal() {
    if (!totalPriceEl) return;

    if (!checkIn.value || !checkOut.value) {
        totalPriceEl.textContent = "-";
        return;
    }

    const start = new Date(checkIn.value);
    const end = new Date(checkOut.value);
    const nights = (end - start) / 86400000;

    if (nights <= 0) {
        totalPriceEl.textContent = "Tanggal tidak valid";
        return;
    }

    const qty = Math.min(parseInt(roomCount.value || 1), MAX_QTY);
    roomCount.value = qty;

    const total = PRICE * nights * qty;
    totalPriceEl.textContent = `Rp ${total.toLocaleString()}`;
}

    checkIn.addEventListener("change", calculateTotal);
    checkOut.addEventListener("change", calculateTotal);
    roomCount.addEventListener("input", calculateTotal);

 
    btnBooking.addEventListener("click", () => {

    const checkInVal  = checkIn.value;
    const checkOutVal = checkOut.value;
    const qty         = parseInt(roomCount.value);

    if (!checkInVal || !checkOutVal) {
        alert("Tanggal check-in dan check-out wajib diisi");
        return;
    }

    if (!qty || qty < 1) {
        alert("Jumlah kamar tidak valid");
        return;
    }

    if (checkOutVal <= checkInVal) {
        alert("Tanggal check-out harus setelah check-in");
        return;
    }

    window.location.href =
        `/booking/create`
        + `?room_id=${ROOM.id}`
        + `&check_in=${checkInVal}`
        + `&check_out=${checkOutVal}`
        + `&quantity=${qty}`;
});


});
