@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Konfirmasi Pembayaran</h1>

    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 border">Kode Booking</th>
                <th class="p-3 border">User</th>
                <th class="p-3 border">Jumlah</th>
                <th class="p-3 border">Bukti</th>
                <th class="p-3 border">Aksi</th>
            </tr>
        </thead>
        <tbody id="paymentBody">
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">
                    Memuat data...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script src="{{ asset('js/admin/payment.js') }}"></script>
@endsection
