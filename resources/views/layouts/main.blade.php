<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <div class="font-bold text-xl text-blue-600">TravelApp</div>
    <a href="{{ route('hotel.index') }}" class="text-gray-700 hover:text-blue-600">
        🏨 Hotel
    </a>
    <a href="{{ route('transport.index') }}" class="text-gray-700 hover:text-blue-600">
        ✈️ Transport
    </a>
    <a href="{{ route('booking.history') }}" class="text-gray-700 hover:text-blue-600">
        📖 Booking History
    </a>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.payment.index') }}"
       class="px-3 py-2 bg-purple-600 text-white rounded text-sm">
        🛠 Admin Panel
    </a>
    <a href="{{ route('admin.booking.index') }}"
    class="px-3 py-2 bg-purple-600 text-white rounded text-sm">
    📖 🛠 Booking Admin
    </a>
    @endif

    <div class="flex items-center gap-4">
        <span>Hi, {{ Auth::user()->name ?? 'Guest' }}</span>

        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-3 py-1 rounded">
                Logout
            </button>
        </form>
        @endauth
    </div>
</nav>

<main class="flex-1 p-6">
    @yield('content')
</main>

<footer class="bg-white text-center py-4 text-sm text-gray-500">
    © {{ date('Y') }} TravelApp
</footer>

<script>
async function graphql(query, variables = {}) {
    const res = await fetch('/graphql', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
        },
        body: JSON.stringify({ query, variables })
    });

    return res.json();
}
</script>

</body>
</html>
