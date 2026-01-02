@extends('layouts.main')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 id="locationName"
                class="text-2xl font-bold text-gray-800">
                Schedule
            </h1>
            <p id="locationCity"
               class="text-gray-500 text-sm"></p>
        </div>

        <a href="{{ route('transport.index') }}"
           class="px-4 py-2 bg-gray-500 text-white text-sm rounded-xl hover:bg-gray-600">
            ⬅ Kembali
        </a>
    </div>

    <!-- Route -->
    <div id="loadingIndicator"
         class="text-center text-gray-500 my-8">
        Memuat rute...
    </div>

    <div id="routeContainer"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    </div>
</div>
<div id="scheduleContainer"></div>


<script>
    const LOCATION_ID = "{{ $id }}";
</script>
<script src="{{ asset('js/transport/location-detail.js') }}"></script>
@endsection
