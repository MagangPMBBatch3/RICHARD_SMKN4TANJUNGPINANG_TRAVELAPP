document.addEventListener("DOMContentLoaded", async () => {
    const hotelName = document.getElementById("hotelName");
    const hotelLocation = document.getElementById("hotelLocation");
    const hotelDescription = document.getElementById("hotelDescription");
    const hotelPhoto = document.getElementById("hotelPhoto");
    const roomList = document.getElementById("roomList");
    const loadingIndicator = document.getElementById("loadingIndicator");

    function csrfToken() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    function resolveImageUrl(path, fallback) {
        if (!path) return fallback;
        if (path.startsWith("http")) return path;
        return `/storage/${path}`;
    }

    async function loadHotelDetail() {
        loadingIndicator.classList.remove("hidden");

        const query = `
            query FindHotel($id: ID!) {
                findHotel(id: $id) {
                    id
                    name
                    location
                    description
                    photo_url
                    hotel_rooms {
                        id
                        name
                        price
                        quantity
                        room_type { name }
                        photos { photo }
                    }
                }
            }
        `;

        try {
            const response = await fetch("/graphql", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
                body: JSON.stringify({
                    query,
                    variables: { id: HOTEL_ID },
                }),
            });

            const json = await response.json();

            if (json.errors) throw new Error(json.errors[0].message);

            const hotel = json.data?.findHotel;
            if (!hotel) {
                hotelName.textContent = "Data hotel tidak ditemukan.";
                return;
            }

            hotelName.textContent = hotel.name;
            hotelLocation.textContent = hotel.location;
            hotelDescription.textContent =
                hotel.description || "Tidak ada deskripsi.";

            hotelPhoto.src = resolveImageUrl(
                hotel.photo_url,
                "https://via.placeholder.com/800x400?text=No+Image"
            );

            roomList.innerHTML = "";

            if (!hotel.hotel_rooms?.length) {
                roomList.innerHTML = `
                    <p class="text-gray-500 col-span-3">
                        Belum ada kamar tersedia.
                    </p>
                `;
            } else {
                hotel.hotel_rooms.forEach(room => {
                    const photoPath = room.photos?.[0]?.photo;

                    const card = document.createElement("div");
                    card.className =
                        "bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden";

                    card.innerHTML = `
                        <img src="${resolveImageUrl(
                            photoPath,
                            'https://via.placeholder.com/300x200?text=No+Photo'
                        )}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold">${room.name}</h3>
                            <p class="text-sm text-gray-500 mb-1">
                                ${room.room_type?.name || "Tipe tidak diketahui"}
                            </p>
                            <p class="text-sm text-gray-500 mb-2">
                                Sisa kamar: ${room.quantity}
                            </p>
                            <p class="text-blue-600 font-semibold mb-3">
                                Rp ${Number(room.price).toLocaleString()}
                            </p>
                            <button
                                data-room-id="${room.id}"
                                class="view-room w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                Lihat Detail
                            </button>
                        </div>
                    `;

                    roomList.appendChild(card);
                });

                document.querySelectorAll(".view-room").forEach(btn => {
                    btn.addEventListener("click", () => {
                        window.location.href =
                            `/hotel-room/${btn.dataset.roomId}`;
                    });
                });
            }
        } catch (err) {
            console.error(err);
            hotelName.textContent = "Gagal memuat data hotel.";
        } finally {
            loadingIndicator.classList.add("hidden");
        }
    }

    loadHotelDetail();
});
