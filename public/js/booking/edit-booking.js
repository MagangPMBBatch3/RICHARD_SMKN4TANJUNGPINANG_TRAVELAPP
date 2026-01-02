document.addEventListener("DOMContentLoaded", () => {
    loadBooking();

    document
        .getElementById("editBookingForm")
        .addEventListener("submit", submitEdit);
});

const bookingId = document.getElementById("booking_id").value;

function toDateInput(value) {
    return value ? value.substring(0, 10) : '';
}

async function loadBooking() {
    const query = `
        query ($id: ID!) {
            getBooking(id: $id) {
                id
                items {
                    reference_id
                    quantity
                    check_in
                    check_out
                }
            }
        }
    `;

    const res = await fetch("/graphql", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            query,
            variables: { id: bookingId }
        })
    });

    const json = await res.json();

    if (json.errors) {
        alert(json.errors[0].message);
        return;
    }

    const items = json.data.getBooking.items;

    if (!items.length) {
        alert("Booking tidak memiliki item.");
        return;
    }

    const item = items[0];

    document.getElementById("room_id").value = item.reference_id;
    document.getElementById("quantity").value = item.quantity;
    document.getElementById("check_in").value = toDateInput(item.check_in);
    document.getElementById("check_out").value = toDateInput(item.check_out);
}

async function submitEdit(e) {
    e.preventDefault();

    const mutation = `
        mutation ($id: ID!, $items: [UpdateBookingItemInput!]!) {
            updateMyBooking(id: $id, items: $items) {
                id
            }
        }
    `;

    const variables = {
        id: bookingId,
        items: [
            {
                reference_id: document.getElementById("room_id").value,
                quantity: Number(document.getElementById("quantity").value),
                check_in: document.getElementById("check_in").value,
                check_out: document.getElementById("check_out").value
            }
        ]
    };

    const res = await fetch("/graphql", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ query: mutation, variables })
    });

    const json = await res.json();

    if (json.errors) {
        alert(json.errors[0].message);
        return;
    }

    alert("Booking berhasil diupdate ✅");
    window.location.href = "/booking/history";
}
