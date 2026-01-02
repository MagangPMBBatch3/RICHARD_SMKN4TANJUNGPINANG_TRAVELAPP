document.addEventListener('DOMContentLoaded', fetchBookingHistory);

/* =========================
   HELPERS
========================= */

function csrf() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');
}

function formatDate(date) {
    if (!date) return '-';

    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

/* =========================
   DATA EXTRACTORS
========================= */

// ambil item hotel dari booking_items
function getHotelItem(booking) {
    return booking.items?.find(
        item =>
            item.item_type === 'hotel_room' &&
            item.hotel_room &&
            item.hotel_room.hotel
    ) ?? null;
}

function getHotelName(booking) {
    const hotelItem = getHotelItem(booking);
    return hotelItem
        ? hotelItem.hotel_room.hotel.name
        : '-';
}

function getCheckIn(booking) {
    const hotelItem = getHotelItem(booking);
    return hotelItem
        ? formatDate(hotelItem.check_in)
        : '-';
}

function getCheckOut(booking) {
    const hotelItem = getHotelItem(booking);
    return hotelItem
        ? formatDate(hotelItem.check_out)
        : '-';
}

function getGuestName(booking) {
    if (!booking.guests?.length) return 'Saya sendiri';

    const primary =
        booking.guests.find(g => g.is_primary) ??
        booking.guests[0];

    return primary.full_name;
}

/* =========================
   MAIN FETCH
========================= */

function fetchBookingHistory() {
    fetch('/graphql', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf(),
        },
        body: JSON.stringify({
            query: `
                query {
                    myBookings {
                        id
                        booking_code
                        status
                        total_price
                        created_at

                        items {
                            item_type
                            check_in
                            check_out
                            hotel_room {
                                hotel {
                                    name
                                }
                            }
                        }

                        guests {
                            full_name
                            is_primary
                        }
                    }
                }
            `
        })
    })
    .then(res => res.json())
    .then(res => {

        if (res.errors?.length) {
            console.error(res.errors);
            alert(res.errors[0].message);
            return;
        }

        const bookings = res.data.myBookings;
        const tbody = document.getElementById('bookingHistoryBody');

        tbody.innerHTML = '';

        if (!bookings.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10"
                        class="p-4 text-center text-gray-500">
                        Belum ada booking
                    </td>
                </tr>
            `;
            return;
        }

        bookings.forEach((booking, index) => {

            const row = `
                <tr>
                    <td class="p-3 border">${index + 1}</td>

                    <td class="p-3 border font-mono">
                        ${booking.booking_code}
                    </td>

                    <td class="p-3 border">
                        ${getHotelName(booking)}
                    </td>

                    <td class="p-3 border">
                        ${getGuestName(booking)}
                    </td>

                    <td class="p-3 border">
                        ${getCheckIn(booking)}
                    </td>

                    <td class="p-3 border">
                        ${getCheckOut(booking)}
                    </td>

                    <td class="p-3 border">
                        Rp ${Number(booking.total_price)
                            .toLocaleString('id-ID')}
                    </td>

                    <td class="p-3 border capitalize">
                        ${booking.status}
                    </td>

                    <td class="p-3 border">
                        ${formatDate(booking.created_at)}
                    </td>

                    <td class="p-3 border text-center">
                        <button
                            onclick="viewDetail(${booking.id})"
                            class="px-3 py-1 bg-blue-600 text-white rounded">
                            Detail
                        </button>
                    </td>
                </tr>
            `;

            tbody.insertAdjacentHTML('beforeend', row);
        });
    })
    .catch(err => {
        console.error(err);
        alert('Gagal memuat booking');
    });
}

/* =========================
   NAVIGATION
========================= */

function viewDetail(id) {
    window.location.href = `/booking/${id}`;
}
