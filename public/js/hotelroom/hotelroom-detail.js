document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('bookingForm');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const roomId   = document.getElementById('room_id').value;
        const checkin  = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;
        const qty      = parseInt(document.getElementById('qty').value);

        if (!checkin || !checkout) {
            alert('Tanggal check-in & check-out wajib diisi');
            return;
        }

        if (qty <= 0) {
            alert('Jumlah kamar tidak valid');
            return;
        }

        const csrf = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        try {
            const res = await fetch('/graphql', {
                method: 'POST',
                credentials: 'same-origin', 
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf 
                },
                body: JSON.stringify({
                    query: `
                        mutation CreateBooking($input: CreateBookingInput!) {
                            createBookingWithItems(input: $input) {
                                id
                                status
                                total_price
                            }
                        }
                    `,
                    variables: {
                        input: {
                            items: [
                                {
                                    item_type: "hotel_room",
                                    reference_id: roomId,
                                    quantity: qty,
                                    check_in: checkin,
                                    check_out: checkout
                                }
                            ]
                        }
                    }
                })
            });

            const result = await res.json();

            if (result.errors) {
                alert(result.errors[0].message);
                return;
            }

            alert('✅ Booking berhasil');
            window.location.href = '/booking/history';

        } catch (err) {
            console.error(err);
            alert('❌ Booking gagal');
        }
    });
});
