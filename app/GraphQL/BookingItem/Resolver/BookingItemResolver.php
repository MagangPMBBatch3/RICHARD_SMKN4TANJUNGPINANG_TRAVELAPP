<?php

namespace App\GraphQL\BookingItem\Resolvers;

use App\Models\BookingItem\BookingItem;
use App\Models\HotelRoom\HotelRoom;
use App\Models\TransportSchedule\TransportSchedule;
use Illuminate\Support\Facades\DB;

class BookingItemResolver
{
    public function itemsByBooking($root, array $args)
    {
        return BookingItem::where('booking_id', $args['booking_id'])
            ->with(['hotelRoom', 'transportSchedule'])
            ->get();
    }

    public function updateItem($root, array $args)
    {
        $item = BookingItem::findOrFail($args['id']);

        DB::transaction(function () use ($item, $args) {

            if (isset($args['quantity'])) {

                // restore previous
                if ($item->item_type === 'hotel_room') {
                    HotelRoom::where('id', $item->reference_id)
                        ->increment('available_room', $item->quantity);
                } else {
                    TransportSchedule::where('id', $item->reference_id)
                        ->increment('seat_available', $item->quantity);
                }

                // apply new
                if ($item->item_type === 'hotel_room') {
                    HotelRoom::where('id', $item->reference_id)
                        ->decrement('available_room', $args['quantity']);
                } else {
                    TransportSchedule::where('id', $item->reference_id)
                        ->decrement('seat_available', $args['quantity']);
                }

                $item->quantity = $args['quantity'];
                $item->subtotal = $args['quantity'] * $item->price;
                $item->save();
            }
        });

        return $item;
    }

    public function deleteItem($root, array $args)
    {
        $item = BookingItem::findOrFail($args['id']);

        // restore seat / room
        if ($item->item_type === 'hotel_room') {
            HotelRoom::where('id', $item->reference_id)
                ->increment('available_room', $item->quantity);
        } else {
            TransportSchedule::where('id', $item->reference_id)
                ->increment('seat_available', $item->quantity);
        }

        $item->delete();
        return $item;
    }
}
