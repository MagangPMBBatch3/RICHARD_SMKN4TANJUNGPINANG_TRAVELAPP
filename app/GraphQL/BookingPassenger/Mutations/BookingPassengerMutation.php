<?php

namespace App\GraphQL\BookingPassenger\Mutations;

use App\Models\BookingPassenger\BookingPassenger;
use Illuminate\Support\Facades\DB;

class BookingPassengerMutation
{
    public function addPassengers($_, array $args)
    {
        return DB::transaction(function () use ($args) {

            $result = [];

            foreach ($args['passengers'] as $passenger) {
                $result[] = BookingPassenger::create([
                    'booking_id' => $args['booking_id'],
                    'user_id' => $passenger['user_id'] ?? null,
                    'full_name' => $passenger['full_name'],
                    'birth_date' => $passenger['birth_date'] ?? null,
                    'identity_number' => $passenger['identity_number'] ?? null,
                ]);
            }

            return $result;
        });
    }
}
