@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Lokasi Transport ✈️🚢</h1>
            <p class="text-gray-500 text-sm">Kelola airport & port.</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
            ⬅ Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4" id="formTitle">Tambah Lokasi Transport</h2>

        <form id="locationForm" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" id="location_id">

            <div>
                <label class="block text-sm font-medium">Tipe</label>
                <select id="type" class="w-full border rounded-lg">
                    <option value="airport">Airport</option>
                    <option value="port">Port</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Nama Lokasi</label>
                <input type="text" id="name" class="w-full border rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Kode</label>
                <input type="text" id="code" class="w-full border rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium">Kota</label>
                <input type="text" id="city" class="w-full border rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Foto</label>
                <input type="file" id="photo" accept="image/*">
                <img id="previewImage" class="mt-3 w-32 rounded-lg hidden">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">💾 Simpan</button>
                <button type="button" id="btnReset"
                    class="px-4 py-2 bg-gray-300 rounded-lg">🔄 Reset</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Daftar Lokasi</h2>

        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">ID</th>
                    <th class="border px-3 py-2">Tipe</th>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Kode</th>
                    <th class="border px-3 py-2">Kota</th>
                    <th class="border px-3 py-2">Foto</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="locationTableBody"></tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/transport/transport-location.js') }}"></script>
@endsection
