<?php

namespace App\GraphQL\HotelRoom\Queries;

use App\Models\HotelRoom\HotelRoom;
use App\Models\BookingItem\BookingItem;

class HotelRoomQuery
{
    /**
     * Get rooms by hotel id
     */
    public function getByHotel($_, array $args)
    {
        return HotelRoom::where('hotel_id', $args['hotel_id'])->get();
    }

    /**
     * Check room availability (based on booking items)
     */
    public function checkAvailability($_, array $args)
    {
        $roomId = $args['room_id'];
        $checkIn = $args['check_in'];
        $checkOut = $args['check_out'];

        // Hitung total kamar yang sudah dibooking pada tanggal tersebut
        $bookedQty = BookingItem::where('reference_id', $roomId)
            ->where('item_type', 'hotel_room')
            ->whereHas('booking', function($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                  ->where('check_out', '>', $checkIn);
            })
            ->sum('quantity');

        $room = HotelRoom::findOrFail($roomId);

        return $bookedQty < $room->quantity;
    }
}
