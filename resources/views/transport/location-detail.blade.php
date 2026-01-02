@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Jadwal Keberangkatan
            </h1>
            <p class="text-gray-500 text-sm">
                Dari lokasi ini
            </p>
        </div>

        <a href="{{ route('transport.index') }}"
           class="px-4 py-2 bg-gray-500 text-white rounded-xl">
            ⬅ Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div id="loadingIndicator" class="text-gray-500 mb-4">
            Memuat jadwal...
        </div>

        <div id="scheduleContainer" class="space-y-4"></div>
    </div>
</div>

<script>
    const LOCATION_ID = {{ $id }};
</script>
<script src="{{ asset('js/transport/location-detail.js') }}"></script>
@endsection
