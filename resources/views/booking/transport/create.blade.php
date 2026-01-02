@extends('layouts.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-xl font-bold mb-4">Booking Transport</h1>

    <div id="summary" class="mb-4 text-sm text-gray-700"></div>

    <button
        id="btnSubmit"
        class="w-full bg-blue-600 text-white py-2 rounded">
        Konfirmasi Booking
    </button>
</div>

<script src="{{ asset('js/booking/booking-transport.js') }}"></script>
@endsection
