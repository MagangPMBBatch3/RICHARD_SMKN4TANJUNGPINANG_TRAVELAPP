@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4">
        Upload Bukti Pembayaran
    </h1>

    <p class="text-gray-600 mb-6">
        Booking Code:
        <span class="font-mono font-semibold">
            {{ $booking->booking_code }}
        </span>
    </p>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="bg-white shadow rounded p-6 space-y-4">

        <div>
            <label class="text-sm font-medium">Total Pembayaran</label>
            <input
                type="number"
                id="amount"
                class="w-full border rounded p-2"
                value="{{ $booking->total_price }}"
                readonly
            >
        </div>

        <div>
            <label class="text-sm font-medium">Metode Pembayaran</label>
            <select id="payment_method" class="w-full border rounded p-2">
                <option value="">Pilih Metode</option>
                <option value="Transfer BCA">Transfer BCA</option>
                <option value="Transfer Mandiri">Transfer Mandiri</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Bukti Pembayaran</label>
            <input
                type="file"
                id="proof"
                accept="image/*"
                class="w-full border rounded p-2"
            >
        </div>

        <img id="preview"
             class="hidden mt-3 rounded border max-h-60">

        <button
            id="btnUpload"
            class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Upload Bukti Pembayaran
        </button>
    </div>

    <a href="/booking/{{ $booking->id }}"
       class="inline-block mt-4 text-sm text-gray-600 hover:underline">
        ⬅ Kembali ke Detail Booking
    </a>

</div>

<script>
    const BOOKING_ID = {{ $booking->id }};
</script>
<script src="{{ asset('js/booking/payment-upload.js') }}"></script>
@endsection
