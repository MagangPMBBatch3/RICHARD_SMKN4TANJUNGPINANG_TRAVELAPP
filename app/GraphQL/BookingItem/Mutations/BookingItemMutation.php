<?php

namespace App\GraphQL\BookingItem\Mutations;

use App\Models\Booking\Booking;
use App\Models\BookingItem\BookingItem;
use App\Models\HotelRoom\HotelRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingItemMutation
{
    public function create($_, array $args)
    {
        $input = $args['input'];

        $booking = Booking::find($input['booking_id']);
        if (! $booking) {
            throw new \Exception("Booking tidak ditemukan");
        }

        $hotelRoom = HotelRoom::find($input['hotel_room_id']);
        if (! $hotelRoom) {
            throw new \Exception("Hotel room tidak ditemukan");
        }

        $checkIn = Carbon::parse($input['check_in']);
        $checkOut = Carbon::parse($input['check_out']);
        if ($checkOut->lte($checkIn)) {
            throw new \Exception("Tanggal check_out harus lebih besar dari check_in");
        }

        $nights = $checkOut->diffInDays($checkIn);
        $quantity = isset($input['quantity']) ? intval($input['quantity']) : 1;

        $pricePerNight = $hotelRoom->price;
        $subtotal = $pricePerNight * $nights * $quantity;

        return DB::transaction(function () use ($booking, $hotelRoom, $input, $checkIn, $checkOut, $nights, $quantity, $pricePerNight, $subtotal) {
            $item = BookingItem::create([
                'booking_id' => $booking->id,
                'item_type' => 'hotel_room',
                'reference_id' => $hotelRoom->id,
                'quantity' => $quantity,
                'price' => $pricePerNight,
                'subtotal' => $subtotal
            ]);

            $booking->total_price = ($booking->total_price ?? 0) + $subtotal;
            $booking->save();

            return $item;
        });
    }
}
