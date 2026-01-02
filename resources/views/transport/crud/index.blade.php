@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Transport</h1>
            <p class="text-sm text-gray-500">Tambah, ubah, dan hapus data transport</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="font-semibold mb-4" id="formTitle">Tambah Transport</h2>

        <form id="transportForm" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" id="transport_id">

            <div>
                <label class="text-sm font-medium">Tipe</label>
                <select id="type" class="w-full rounded-lg border" required>
                    <option value="">Pilih</option>
                    <option value="airplane">Pesawat</option>
                    <option value="boat">Kapal</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text" id="name" class="w-full rounded-lg border" required>
            </div>

            <div>
                <label class="text-sm font-medium">Kode</label>
                <input type="text" id="code" class="w-full rounded-lg border">
            </div>

            <div>
                <label class="text-sm font-medium">Kapasitas</label>
                <input type="number" id="capacity" class="w-full rounded-lg border">
            </div>

            <div>
                <label class="text-sm font-medium">Harga per Kursi</label>
                <input type="number" id="price_per_seat" class="w-full rounded-lg border">
            </div>

            <div>
                <label class="text-sm font-medium">Foto</label>
                <input type="file" id="photo" class="w-full rounded-lg border">
                <img id="previewImage" class="w-32 mt-3 rounded hidden">
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                <button type="button" id="btnReset" class="px-4 py-2 bg-gray-300 rounded-lg">Reset</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Tipe</th>
                    <th class="border p-2">Kapasitas</th>
                    <th class="border p-2">Harga</th>
                    <th class="border p-2">Foto</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="transportTableBody"></tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/transport/transport-crud.js') }}"></script>
@endsection
