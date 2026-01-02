@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 id="hotelName" class="text-2xl font-bold text-gray-800">Memuat...</h1>
            <p id="hotelLocation" class="text-gray-500 text-sm"></p>
        </div>
        <a href="{{ route('hotel.index') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
            ⬅ Kembali
        </a>
    </div>
    <div class="mb-6">
        <img id="hotelPhoto" class="w-full h-64 object-cover rounded-xl shadow-md" alt="Foto Hotel">
    </div>
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Deskripsi</h2>
        <p id="hotelDescription" class="text-gray-600"></p>
    </div>
    <h2 class="text-xl font-semibold mb-4">Tipe Kamar</h2>
    <div id="roomList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

    <div id="loadingIndicator" class="text-center text-gray-500 mt-6 hidden">
        Memuat detail hotel...
    </div>
</div>

<script>
    const HOTEL_ID = {{ $id }};
</script>
<script src="{{ asset('js/hotel/hotel-show.js') }}"></script>
@endsection
