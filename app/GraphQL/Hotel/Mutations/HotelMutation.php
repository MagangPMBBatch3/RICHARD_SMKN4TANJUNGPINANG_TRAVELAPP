<?php

namespace App\GraphQL\Hotel\Mutations;

use App\Models\Hotel\Hotel;

class HotelMutation
{
    public function restore($_, array $args): ?Hotel
    {
        return Hotel::withTrashed()->find($args['id'])?->restore()
            ? Hotel::find($args['id'])
            : null;
    }

    public function forceDelete($_, array $args): ?Hotel
    {
        $hotel = Hotel::withTrashed()->find($args['id']);
        if ($hotel) {
            $hotel->forceDelete();
            return $hotel;
        }
        return null;
    }
}
