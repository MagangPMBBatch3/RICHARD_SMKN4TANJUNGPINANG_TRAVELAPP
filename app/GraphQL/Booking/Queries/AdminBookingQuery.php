<?php

namespace App\GraphQL\Booking\Queries;

use App\Models\Booking\Booking;

class AdminBookingQuery
{
public function index($_, array $args)
{
    $query = Booking::with('user');

    if (!empty($args['search'])) {
        $term = $args['search'];

        $query->where(function ($q) use ($term) {
            $q->where('booking_code', 'LIKE', "%$term%")
              ->orWhereHas('user', function ($u) use ($term) {
                  $u->where('name', 'LIKE', "%$term%");
              });
        });
    }

    if (!empty($args['status']) && $args['status'] !== "all") {
        $query->where('status', $args['status']);
    }

    return $query->orderBy('created_at', 'desc')->get();
}


    public function detail($_, array $args)
    {
        return Booking::with(['items', 'guests', 'user'])->findOrFail($args['id']);
    }

}

