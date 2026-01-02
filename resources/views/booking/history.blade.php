@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Booking</h1>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th>#</th>
                <th>Kode Booking</th>
                <th>Hotel</th>
                <th>Tamu</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="bookingHistoryBody">
            </tbody>
        </table>
    </div>

    <div id="bookingHistory" class="mt-4"></div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{ asset('js/booking/booking-history.js') }}"></script>
@endsection
