@extends('layouts.auth')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-blue-600">Daftar Akun Baru</h2>

        <form method="POST" action="{{ url('/register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="password" id="password"
                       class="mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
            </div>

        <form id="registerForm">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Daftar</button>
        </form>

<script src="{{ asset('js/auth/register.js') }}"></script>

        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login di sini</a>
        </p>
    </div>
</div>
@endsection
