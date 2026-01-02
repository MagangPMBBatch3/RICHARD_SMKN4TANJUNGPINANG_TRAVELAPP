<?php

namespace App\GraphQL\HotelRoomPhoto\Mutations;

use App\Models\HotelRoomPhoto\HotelRoomPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HotelRoomPhotoMutation
{
    public function upload($_, array $args)
    {
        /** @var UploadedFile $file */
        $file = $args['photo'];
        $path = $file->store('hotel-room-photos', 'public');

        return HotelRoomPhoto::create([
            'hotel_room_id' => $args['hotel_room_id'],
            'photo' => $path
        ]);
    }
}
