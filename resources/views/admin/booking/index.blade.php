@extends('layouts.main')

@section('content')

<div class="p-6 max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">📘 Semua Booking</h1>

    <div class="flex gap-3 mb-4">

        <input type="text"
               id="searchInput"
               placeholder="Cari booking code atau nama user..."
               class="px-4 py-2 border rounded w-80">

        <select id="statusFilter" class="px-3 py-2 border rounded">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
        </select>

    </div>

    <div class="bg-white shadow rounded p-4">
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Kode Booking</th>
                    <th class="p-2 border">User</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="bookingBody">
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script src="{{ asset('js/admin/admin-booking.js') }}"></script>

@endsection
