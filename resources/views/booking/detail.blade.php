@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Detail Booking</h1>

    <div class="bg-white shadow rounded mb-6">
        <div class="grid grid-cols-3 gap-4 p-6 text-sm">
            <div>
                <p class="text-gray-500">Kode Booking</p>
                <p class="font-mono font-semibold" id="bookingCode">-</p>
            </div>

            <div>
                <p class="text-gray-500">Status</p>
                <p id="bookingStatus">-</p>
            </div>

            <div>
                <p class="text-gray-500">Total Harga</p>
                <p class="font-semibold" id="totalPrice">-</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded mb-6 hidden" id="hotelSection">
        <h2 class="font-semibold px-6 pt-4">Hotel</h2>

        <div class="p-6 text-sm space-y-2">
            <p><b>Hotel:</b> <span id="hotelName">-</span></p>
            <p><b>Check-in:</b> <span id="checkIn">-</span></p>
            <p><b>Check-out:</b> <span id="checkOut">-</span></p>
        </div>
    </div>

<div class="bg-white shadow rounded mb-6 hidden" id="transportSection">
    <h2 class="font-semibold px-6 pt-4">Transport</h2>

    <table class="w-full text-sm mt-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 border">Rute</th>
                <th class="p-3 border">Transport</th>
                <th class="p-3 border">Berangkat</th>
                <th class="p-3 border">Subtotal</th>
            </tr>
        </thead>
        <tbody id="transportBody">
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    Memuat data transport...
                </td>
            </tr>
        </tbody>
    </table>
</div>


    <div class="bg-white shadow rounded mb-6">
        <h2 class="font-semibold px-6 pt-4">Tamu / Penumpang</h2>

        <table class="w-full text-sm mt-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">Primary</th>
                </tr>
            </thead>
            <tbody id="guestBody"></tbody>
        </table>
    </div>

    <div class="flex gap-3">
        <button
            id="btnEditBooking"
            class="px-4 py-2 bg-blue-600 text-white rounded">
            Edit
        </button>

        <button
            id="btnDeleteBooking"
            class="px-4 py-2 bg-red-600 text-white rounded">
            Hapus
        </button>
        @if($booking->status === 'pending')
        <a href="{{ route('booking.payment.upload', $booking->id) }}"
        class="px-4 py-2 bg-green-600 text-white rounded">
        💳 Upload Pembayaran
            </a>
        @endif

        <a href="/booking/history"
           class="px-4 py-2 bg-gray-500 text-white rounded">
            Kembali
        </a>
    </div>
</div>

<script>
    const IS_ADMIN = {{ auth()->user()->is_admin ? 'true' : 'false' }};
    const BOOKING_ID = {{ $id }};
</script>
<script src="{{ asset('js/booking/booking-detail.js') }}"></script>
@endsection
