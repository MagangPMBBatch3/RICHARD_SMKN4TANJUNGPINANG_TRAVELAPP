@extends('layouts.auth')

@section('title', 'Login')
@section('heading', 'Login ke Akun Anda')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<form id="loginForm" class="space-y-4">
    <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <input type="email" id="email" placeholder="Masukkan email" required
               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-1">Password</label>
        <input type="password" id="password" placeholder="Masukkan password" required
               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
        Login
    </button>

    <p class="text-center text-sm text-gray-600 mt-3">
        Belum punya akun? <a href="/register" class="text-blue-600 font-medium">Daftar di sini</a>
    </p>
</form>

<script src="{{ asset('js/auth/login.js') }}"></script>
@endsection
