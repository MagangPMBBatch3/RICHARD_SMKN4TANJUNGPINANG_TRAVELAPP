const API_URL = "/graphql";

document.addEventListener("DOMContentLoaded", () => {
    loadBooking();

    document.getElementById("btnDeleteBooking")?.addEventListener("click", deleteBooking);
    document.getElementById("btnEditBooking")?.addEventListener("click", goToEdit);
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

function formatDate(date) {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
}

async function loadBooking() {
    const query = IS_ADMIN
        ? `
            query ($id: ID!) {
                adminGetBooking(id: $id) {
                    id
                    booking_code
                    status
                    total_price

                    items {
                        item_type
                        check_in
                        check_out
                        subtotal

                        hotel_room {
                            hotel { name }
                        }

                        transport_schedule {
                            departure_time
                            transport { name }
                            originLocation { name city }
                            destinationLocation { name city }
                        }
                    }

                    guests {
                        full_name
                        is_primary
                    }
                }
            }
        `
        : `
            query ($id: ID!) {
                getBooking(id: $id) {
                    id
                    booking_code
                    status
                    total_price

                    items {
                        item_type
                        check_in
                        check_out
                        subtotal

                        hotel_room {
                            hotel { name }
                        }

                        transport_schedule {
                            departure_time
                            transport { name }
                            originLocation { name city }
                            destinationLocation { name city }
                        }
                    }

                    guests {
                        full_name
                        is_primary
                    }
                }
            }
        `;

    const data = await gql(query, { id: BOOKING_ID });

    const booking = IS_ADMIN ? data.adminGetBooking : data.getBooking;

    document.getElementById("bookingCode").innerText = booking.booking_code;
    document.getElementById("bookingStatus").innerText = booking.status;
    document.getElementById("totalPrice").innerText =
        "Rp " + Number(booking.total_price).toLocaleString("id-ID");

    const hotelItem = booking.items.find(i => i.item_type === "hotel_room");

    if (hotelItem) {
        document.getElementById("hotelSection").classList.remove("hidden");

        document.getElementById("hotelName").innerText = hotelItem.hotel_room.hotel.name;
        document.getElementById("checkIn").innerText = formatDate(hotelItem.check_in);
        document.getElementById("checkOut").innerText = formatDate(hotelItem.check_out);
    }

    const transportItems = booking.items.filter(i => i.item_type === "transport_schedule");

    if (transportItems.length > 0) {
        document.getElementById("transportSection").classList.remove("hidden");

        const tbody = document.getElementById("transportBody");
        tbody.innerHTML = "";

        transportItems.forEach(item => {
            const s = item.transport_schedule;
            if (!s) return;

            const transportName = s.transport?.name ?? "-";
            const route = `${s.originLocation?.name ?? "-"} → ${s.destinationLocation?.name ?? "-"}`;
            const depart = s.departure_time
                ? new Date(s.departure_time).toLocaleString("id-ID")
                : "-";

            tbody.insertAdjacentHTML("beforeend", `
                <tr>
                    <td class="p-3 border">${route}</td>
                    <td class="p-3 border">${transportName}</td>
                    <td class="p-3 border">${depart}</td>
                    <td class="p-3 border">
                        Rp ${Number(item.subtotal).toLocaleString("id-ID")}
                    </td>
                </tr>
            `);
        });
    }

    const guestBody = document.getElementById("guestBody");
    guestBody.innerHTML = "";

    if (!booking.guests.length) {
        guestBody.innerHTML = `
            <tr>
                <td colspan="3" class="p-4 text-center text-gray-500">
                    Tidak ada tamu
                </td>
            </tr>
        `;
    } else {
        booking.guests.forEach((g, i) => {
            guestBody.innerHTML += `
                <tr>
                    <td class="p-3 border">${i + 1}</td>
                    <td class="p-3 border">${g.full_name}</td>
                    <td class="p-3 border text-center">${g.is_primary ? "✔" : "-"}</td>
                </tr>
            `;
        });
    }
}

async function deleteBooking() {
    if (!confirm("Yakin ingin menghapus booking ini?")) return;

    const mutation = `
        mutation ($id: ID!) {
            deleteBooking(id: $id)
        }
    `;

    try {
        await gql(mutation, { id: BOOKING_ID });
        alert("Booking berhasil dihapus");
        window.location.href = "/booking/history";
    } catch (e) {
        alert(e.message);
    }
}

function goToEdit() {
    window.location.href = `/booking/${BOOKING_ID}/edit`;
}
