@extends('layouts.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">Konfirmasi Booking</h1>

    <label class="block mb-3 font-medium">Booking untuk</label>
    <select id="bookingFor" class="border p-2 rounded w-full mb-4">
        <option value="self">Saya sendiri</option>
        <option value="other">Orang lain</option>
    </select>

    <div id="guestForm" class="hidden border p-4 rounded mb-6">
        <h3 class="font-semibold mb-2">Data Tamu</h3>
        <input id="guestName" class="border p-2 w-full mb-3" placeholder="Nama Lengkap">
        <input id="guestPhone" class="border p-2 w-full mb-3" placeholder="No HP">
        <input id="guestEmail" class="border p-2 w-full" placeholder="Email (opsional)">
    </div>

    <div id="summary" class="mb-6 text-gray-700"></div>

    <button id="btnSubmit"
        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl">
        Konfirmasi Booking
    </button>

</div>

<script src="{{ asset('js/booking/booking-create.js') }}"></script>
@endsection
