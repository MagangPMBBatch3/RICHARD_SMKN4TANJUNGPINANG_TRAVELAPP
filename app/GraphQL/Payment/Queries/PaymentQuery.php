<?php

namespace App\GraphQL\Payment\Queries;

use App\Models\Payment\Payment;

class PaymentQuery
{
    public function pending()
    {
        return Payment::with([
                'booking.user'
            ])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function detail($_, array $args)
    {
        return Payment::with([
            'booking.items',
            'booking.user'
        ])
        ->where('id', $args['id'])
        ->firstOrFail();
    }

}
