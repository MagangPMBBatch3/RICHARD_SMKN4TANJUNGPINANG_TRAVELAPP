<?php

namespace App\GraphQL\BookingPassenger\Queries;

use App\Models\BookingPassenger\BookingPassenger;

class BookingPassengerQuery
{
    public function getByBooking($_, array $args)
    {
        return BookingPassenger::where('booking_id', $args['booking_id'])->get();
    }
}
