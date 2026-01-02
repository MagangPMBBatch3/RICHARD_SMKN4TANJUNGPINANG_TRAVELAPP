<?php

namespace App\GraphQL\Booking\Queries;

use App\Models\Booking\Booking;
use Illuminate\Support\Facades\Auth;

class MyBookingQuery
{
    public function __invoke($_, array $args)
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('Unauthenticated');
        }

        return Booking::with([
            'items.hotelRoom.hotel',
            'items.transportSchedule.transport',
            'items.transportSchedule.originLocation',
            'items.transportSchedule.destinationLocation',
            'guests',
        ])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function detail($_, array $args)
{
    return Booking::with([
        'items.hotelRoom.hotel',
        'items.transportSchedule.transport',
        'items.transportSchedule.originLocation',
        'items.transportSchedule.destinationLocation',
        'guests',
    ])
    ->where('id', $args['id'])
    ->where('user_id', auth()->id())
    ->firstOrFail();
}

}
