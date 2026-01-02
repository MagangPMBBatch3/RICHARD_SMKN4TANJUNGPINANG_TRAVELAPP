<?php

namespace App\GraphQL\Payment\Mutations;

use App\Models\Payment\Payment;
use App\Models\Booking\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentMutation
{
    /* ============================
     * USER UPLOAD BUKTI BAYAR
     * ============================ */
    public function uploadProof($_, array $args)
    {
        $userId = Auth::id();
        if (!$userId) {
            throw new \Exception('Unauthenticated');
        }

        return DB::transaction(function () use ($args, $userId) {

            $booking = Booking::where('id', $args['booking_id'])
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->firstOrFail();

            // upload file
            $path = $args['proof']->store(
                'payment_proofs',
                'public'
            );

            $payment = Payment::create([
                'booking_id'     => $booking->id,
                'amount'         => $args['amount'],
                'payment_method' => $args['payment_method'] ?? null,
                'proof'          => $path,
                'paid_at'        => Carbon::now(),
                'status'         => 'pending',
            ]);

            return $payment;
        });
    }

    /* ============================
     * ADMIN CONFIRM / REJECT
     * ============================ */
    public function confirm($_, array $args)
    {
        // pastikan admin (simple)
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            throw new \Exception('Unauthorized');
        }

        return DB::transaction(function () use ($args) {

            $payment = Payment::with('booking')
                ->findOrFail($args['payment_id']);

            if (!in_array($args['status'], ['confirmed', 'failed'])) {
                throw new \Exception('Status tidak valid');
            }

            $payment->update([
                'status' => $args['status'],
            ]);

            // kalau approved → booking confirmed
            if ($args['status'] === 'confirmed') {
                $payment->booking->update([
                    'status' => 'confirmed',
                ]);
            }

            return $payment;
        });
    }

    public function reject($_, array $args)
    {
    $payment = Payment::with('booking')
        ->where('id', $args['payment_id'])
        ->where('status', 'pending')
        ->firstOrFail();

    $payment->update([
        'status' => 'failed'
    ]);

    return $payment;
    }

}
