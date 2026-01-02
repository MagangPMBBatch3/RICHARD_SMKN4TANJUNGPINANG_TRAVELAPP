@extends('layouts.main')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Manajemen Schedule Transport</h1>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="font-semibold mb-4" id="formTitle">Tambah Schedule</h2>

        <form id="scheduleForm" class="space-y-4">
            <input type="hidden" id="schedule_id">

            <div>
                <label class="text-sm font-medium">Pesawat</label>
                <select id="transport_id" class="w-full border rounded-lg" required></select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Origin Location</label>
                    <select id="origin_location_id" class="w-full border rounded-lg"></select>
                </div>

                <div>
                    <label class="text-sm font-medium">Destination Location</label>
                    <select id="destination_location_id" class="w-full border rounded-lg"></select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="datetime-local" id="departure_time" class="border rounded-lg p-2" required>
                <input type="datetime-local" id="arrival_time" class="border rounded-lg p-2">
            </div>

            <input type="number" id="price" class="border rounded-lg p-2" placeholder="Harga" required>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                <button type="button" id="btnReset"
                        class="px-4 py-2 bg-gray-300 rounded-lg">Reset</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Pesawat</th>
                <th class="border p-2">Dari</th>
                <th class="border p-2">Ke</th>
                <th class="border p-2">Berangkat</th>
                <th class="border p-2">Harga</th>
                <th class="border p-2">Aksi</th>
            </tr>
            </thead>

            <tbody id="scheduleTableBody"></tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/transport/schedule-crud.js') }}"></script>
@endsection
