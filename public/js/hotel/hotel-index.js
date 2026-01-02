document.addEventListener("DOMContentLoaded", () => {
    const hotelContainer = document.getElementById("hotelContainer");
    const loadingIndicator = document.getElementById("loadingIndicator");

    async function loadHotels() {
        loadingIndicator.classList.remove("hidden");

        const query = `
            query {
                allHotels {
                    id
                    name
                    location
                    description
                    photo
                    hotel_rooms {
                        price
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
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ query }),
            });

            const resData = await response.json();

            if (resData.errors) {
                console.error(resData.errors);
                throw new Error(resData.errors[0].message);
            }

            const hotels = resData.data?.allHotels || [];
            hotelContainer.innerHTML = "";

            if (!hotels.length) {
                hotelContainer.innerHTML = `
                    <p class="text-gray-500 col-span-3 text-center">
                        Belum ada hotel tersedia.
                    </p>`;
                return;
            }

            hotels.forEach(hotel => {
                const photoUrl = hotel.photo
                    ? `/storage/${hotel.photo}`
                    : "https://via.placeholder.com/400x250?text=No+Image";

                const maxPrice = hotel.hotel_rooms?.length
                    ? Math.max(...hotel.hotel_rooms.map(r => r.price || 0))
                    : 0;

                const card = document.createElement("div");
                card.className =
                    "bg-white rounded-xl shadow-md hover:shadow-lg transition overflow-hidden";

                card.innerHTML = `
                    <img src="${photoUrl}" alt="${hotel.name}" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col justify-between h-40">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">${hotel.name}</h3>
                            <p class="text-sm text-gray-500 mb-2">${hotel.location}</p>
                            <p class="text-blue-600 font-semibold">
                                ${maxPrice > 0
                                    ? "Mulai dari Rp " + maxPrice.toLocaleString()
                                    : "Harga belum tersedia"}
                            </p>
                        </div>
                        <div class="mt-3">
                            <a href="/hotel/${hotel.id}"
                               class="block text-center w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                `;

                hotelContainer.appendChild(card);
            });

        } catch (error) {
            console.error("Error memuat data hotel:", error);
            hotelContainer.innerHTML = `
                <p class="text-red-500 col-span-3 text-center">
                    Gagal memuat data hotel.
                </p>`;
        } finally {
            loadingIndicator.classList.add("hidden");
        }
    }

    loadHotels();
});
