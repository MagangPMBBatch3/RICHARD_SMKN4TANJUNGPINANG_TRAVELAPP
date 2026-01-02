@extends('layouts.main')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white shadow rounded-xl p-5 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">
                Halo, {{ Auth::user()->name ?? 'Admin' }}
            </h1>
            <p class="text-gray-500 text-sm">Selamat datang kembali 👋</p>
        </div>

        <div class="flex items-center space-x-3 relative">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=0D8ABC&color=fff"
                 class="w-10 h-10 rounded-full border">

            <button class="text-gray-700 font-medium hover:text-blue-600" onclick="toggleMenu()">
                {{ Auth::user()->name ?? 'Admin' }} ▼
            </button>

            <!-- Dropdown Menu -->
            <div id="menuDropdown" class="hidden absolute right-0 top-12 w-44 bg-white rounded-lg shadow-lg border">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="bg-gray-50 p-6 rounded-xl shadow-inner border border-gray-100">
        <h2 class="text-lg font-bold mb-2">Selamat datang di Dashboard</h2>
        <p class="text-gray-600">
            Anda login sebagai 
            <span class="font-semibold text-blue-600">
                {{ Auth::user()->role ?? 'Admin' }}
            </span>
        </p>
    </div>
</div>

<script>
function toggleMenu() {
    document.getElementById('menuDropdown').classList.toggle('hidden');
}
</script>
@endsection
