@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Kamar Hotel</h1>
        <a href="{{ url('/hotel') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            ← Kembali
        </a>
    </div>

    <form id="createRoomForm" class="bg-white p-6 rounded-xl shadow mb-8">
        <h2 id="formTitle" class="text-lg font-semibold mb-4">Tambah Kamar Baru</h2>

        <input type="hidden" id="hotel_room_id">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Hotel</label>
                <select id="hotel_id" class="w-full border rounded-lg p-2">
                    <option value="">-- Pilih Hotel --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe Kamar</label>
                <select id="room_type_id" class="w-full border rounded-lg p-2">
                    <option value="">-- Pilih Tipe Kamar --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Kamar</label>
                <input type="text" id="name" class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" id="price" class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" id="quantity" class="w-full border rounded-lg p-2">
            </div>
        </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Deskripsi Kamar</label>
                <textarea name="description"
                class="w-full border rounded p-2"
                rows="4"
                placeholder="Masukkan deskripsi kamar..."></textarea>
            </div>


        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Foto Kamar</label>
            <div id="photoWrapper" class="mt-2">
                <input type="file" name="photos[]" accept="image/*" class="photoInput border p-2 rounded">
            </div>
            <button type="button" id="btnAddPhoto" class="mt-2 bg-gray-200 text-sm px-3 py-1 rounded hover:bg-gray-300">
                + Tambah Foto
            </button>
        </div>

        <div id="photoPreview" class="mt-3 flex flex-wrap"></div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" id="btnReset" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>

    <div>
        <h2 class="text-lg font-semibold mb-3">Daftar Kamar</h2>
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2 text-left">#</th>
                        <th class="border px-3 py-2 text-left">Hotel</th>
                        <th class="border px-3 py-2 text-left">Tipe</th>
                        <th class="border px-3 py-2 text-left">Nama</th>
                        <th class="border px-3 py-2 text-left">Harga</th>
                        <th class="border px-3 py-2 text-left">Jumlah</th>
                        <th class="border px-3 py-2 text-left">Foto</th>
                        <th class="border px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="hotelRoomTableBody">
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Memuat data kamar...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/hotelroom/hotelroom.js') }}"></script>
@endsection
