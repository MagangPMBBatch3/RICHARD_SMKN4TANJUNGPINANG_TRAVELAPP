@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Detail Pembayaran</h1>

    <div class="bg-white shadow rounded p-6 mb-6">
        <p><b>Kode Booking:</b> <span id="bookingCode">-</span></p>
        <p><b>User:</b> <span id="userName">-</span></p>
        <p><b>Total:</b> <span id="amount">-</span></p>
    </div>

    <div class="bg-white shadow rounded p-6 mb-6">
        <p class="font-semibold mb-2">Bukti Pembayaran</p>
        <img id="proofImage" class="max-w-full rounded border" />
    </div>

    <div class="flex gap-3">
        <button onclick="confirmPayment()"
            class="px-4 py-2 bg-green-600 text-white rounded">
            ✔ Konfirmasi
        </button>

        <button onclick="rejectPayment()"
            class="px-4 py-2 bg-red-600 text-white rounded">
            ✖ Reject
        </button>

        <a href="{{ route('admin.payment.index') }}"
           class="px-4 py-2 bg-gray-500 text-white rounded">
            Kembali
        </a>
    </div>
</div>

<script>
    const PAYMENT_ID = {{ $id }};
</script>
<script src="{{ asset('js/admin/payment-detail.js') }}"></script>
@endsection
