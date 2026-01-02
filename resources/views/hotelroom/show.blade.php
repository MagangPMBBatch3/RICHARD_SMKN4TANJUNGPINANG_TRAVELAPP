@extends('layouts.main')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    <a href="javascript:history.back()"
       class="text-blue-600 mb-4 inline-block">⬅ Kembali</a>

    <div class="bg-white rounded-xl shadow p-6">

        <!-- Foto -->
        <img id="roomPhoto"
             class="w-full h-80 object-cover rounded-xl mb-6"
             src="https://via.placeholder.com/800x400">

        <!-- Info -->
        <h1 id="roomName" class="text-2xl font-bold mb-1">Memuat...</h1>
        <p id="hotelName" class="text-gray-500 mb-2"></p>

        <p id="roomPrice"
           class="text-xl text-blue-600 font-semibold mb-2"></p>

        <p id="roomQuantity"
           class="text-sm text-gray-600 mb-4"></p>

        <p id="roomDescription"
           class="text-gray-700 mb-6"></p>


        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 max-w-xl">

    <!-- Check In -->
    <div>
        <label class="block text-sm font-medium mb-1">
            Check-in
        </label>
        <input
            type="date"
            id="checkIn"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <!-- Check Out -->
    <div>
        <label class="block text-sm font-medium mb-1">
            Check-out
        </label>
        <input
            type="date"
            id="checkOut"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <!-- Jumlah -->
    <div>
        <label class="block text-sm font-medium mb-1">
            Jumlah Kamar
        </label>
        <input
            type="number"
            id="roomCount"
            min="1"
            value="1"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div class="mt-4 text-lg font-semibold">
        Total: <span id="totalPrice">-</span>
    </div>
</div>
        <button
    id="btnBooking"
    class="mt-6 bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700">
    Booking Sekarang
</button>

    <script>
    function goToBooking() {
        window.location.href = `/booking/create?room_id={{ $id }}`;
    }
    </script>

        </div>

    </div>
</div>

<script>
    const ROOM_ID = {{ $id }};
</script>
<script src="{{ asset('js/hotelroom/hotelroom-show.js') }}"></script>
@endsection
