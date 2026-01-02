@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Hotel</h1>
            <p class="text-gray-500 text-sm">Temukan hotel terbaik untuk perjalanan Anda ✈️</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-xl hover:bg-gray-600 transition">
                ⬅ Kembali ke Dashboard
            </a>
            @if(auth()->user()->is_admin)
            <a href="{{ route('hotel.crud') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                ⚙️ Kelola Hotel
            </a>
            <a href="{{ route('hotelroom.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition">
                ➕ Tambah Kamar
            </a>
            @endif
        </div>
    </div>
    <div id="hotelContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    </div>
    <div id="loadingIndicator" class="text-center text-gray-500 mt-6 hidden">
        Memuat data hotel...
    </div>
</div>

<script src="{{ asset('js/hotel/hotel-index.js') }}"></script>
@endsection
