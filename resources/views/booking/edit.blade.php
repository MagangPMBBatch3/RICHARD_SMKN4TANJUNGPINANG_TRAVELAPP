@extends('layouts.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Edit Booking</h1>

    <form id="editBookingForm">
        <input type="hidden" id="booking_id" value="{{ $id }}">
        <input type="hidden" id="room_id">

        <div class="mb-4">
            <label class="block font-medium mb-1">Check In</label>
            <input type="date" id="check_in" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Check Out</label>
            <input type="date" id="check_out" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Jumlah Kamar</label>
            <input type="number" id="quantity" min="1" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit"
            id="submitBtn"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Simpan Perubahan
        </button>


        <a href="/booking/history"
           class="ml-3 text-gray-600 hover:underline">
           Batal
        </a>
    </form>
</div>
<script src="{{ asset('js/booking/edit-booking.js') }}"></script>
@endsection
