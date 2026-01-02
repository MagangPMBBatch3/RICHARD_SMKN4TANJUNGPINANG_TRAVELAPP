<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            @yield('heading', 'Authentication')
        </h1>

        @yield('content')
    </div>

</body>
</html>
