@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Hotel 🏨</h1>
            <p class="text-gray-500 text-sm">Kelola data hotel — tambah, ubah, dan hapus.</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
            ⬅ Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Tambah/Edit Hotel -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4" id="formTitle">Tambah Hotel</h2>
        <form id="hotelForm" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" id="hotel_id">

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Hotel</label>
                <input type="text" id="name" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                <input type="text" id="location" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" class="w-full border-gray-300 rounded-lg"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Hotel</label>
                <input type="file" id="photo" accept="image/*" class="w-full border-gray-300 rounded-lg">
                <img id="previewImage" class="mt-3 w-32 rounded-lg hidden">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">💾 Simpan</button>
                <button type="button" id="btnReset"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">🔄 Reset</button>
            </div>
        </form>
    </div>

    <!-- Tabel Data Hotel -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Daftar Hotel</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700 border">
                <input type="text" 
                id="searchHotel" 
                placeholder="Cari hotel..." 
                class="border rounded-lg px-4 py-2 w-80 mb-4"/>
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Lokasi</th>
                        <th class="px-4 py-2 border">Deskripsi</th>
                        <th class="px-4 py-2 border">Foto</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="hotelTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/hotel/hotel-crud.js') }}"></script>
@endsection
