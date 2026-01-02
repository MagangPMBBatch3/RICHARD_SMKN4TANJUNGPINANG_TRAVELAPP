@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Daftar Lokasi Bandara/Pelabuhan
            </h1>
            <p class="text-gray-500 text-sm">
                Pilih transport dan waktu keberangkatan Anda ✈️🚢
            </p>
        </div>

        <div class="flex space-x-2">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-xl hover:bg-gray-600 transition">
                ⬅ Kembali ke Dashboard
            </a>
            @if(auth()->user()->is_admin)
            <a href="{{ route('transport.locations.index') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                📍 Kelola Transport Location
            </a>

            <a href="{{ route('transport.schedule.crud') }}"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                🗓️ Kelola Transport Schedule
            </a>

            <a href="{{ route('transport.crud') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition">
                ⚙️ Kelola Transport
            </a>
            @endif
        </div>
    </div>

    <div id="locationContainer"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    </div>

    <div id="locationLoading"
        class="text-center text-gray-500 mt-6 hidden">
        Memuat lokasi transport...
    </div>

</div>
<script src="{{ asset('js/transport/transport-list.js') }}"></script>
@endsection
