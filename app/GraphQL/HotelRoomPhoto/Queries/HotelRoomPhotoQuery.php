<?php

namespace App\GraphQL\HotelRoomPhoto\Queries;

use App\Models\HotelRoomPhoto\HotelRoomPhoto;

final class HotelRoomPhotoQuery
{
    public function __invoke($_, array $args)
    {
        return HotelRoomPhoto::where('hotel_room_id', $args['hotel_room_id'])->get();
    }
}
