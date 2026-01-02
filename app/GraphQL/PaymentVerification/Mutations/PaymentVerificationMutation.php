<?php

namespace App\GraphQL\PaymentVerification\Mutations;

use App\Models\PaymentVerification\PaymentVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentVerificationMutation
{

public function verify($_, array $args)
{
    $adminId = Auth::id();

    $verification = PaymentVerification::with('payment.booking')
        ->findOrFail($args['id']);

    return DB::transaction(function () use ($verification, $args, $adminId) {

        $verification->update([
            'status'   => $args['status'],
            'note'     => $args['note'] ?? null,
            'admin_id' => $adminId,
        ]);

        if ($args['status'] === 'approved') {
            $verification->payment->update([
                'status' => 'confirmed',
                'paid_at' => now(),
            ]);

            $verification->payment->booking->update([
                'status' => 'confirmed',
            ]);
        }

        if ($args['status'] === 'rejected') {
            $verification->payment->update([
                'status' => 'failed',
            ]);
        }

        return $verification;
    });
}
}