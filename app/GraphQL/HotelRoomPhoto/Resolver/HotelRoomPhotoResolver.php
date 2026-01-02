<?php

namespace App\GraphQL\HotelRoomPhoto\Resolver;

use App\Models\HotelRoomPhoto\HotelRoomPhoto;

class HotelRoomPhotoResolver
{
    public function photoUrl(HotelRoomPhoto $photo)
    {
        return $photo->photo
            ? asset('storage/' . $photo->photo)
            : asset('images/no-image.jpg');
    }
}
