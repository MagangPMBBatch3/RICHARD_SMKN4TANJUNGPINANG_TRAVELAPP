<?php

namespace App\GraphQL\Booking\Mutations;

use App\Models\Booking\Booking;
use App\Models\BookingItem\BookingItem;
use App\Models\HotelRoom\HotelRoom;
use App\Models\BookingGuest\BookingGuest;
use App\Models\TransportSchedule\TransportSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Carbon\Carbon;

class BookingMutation
{
    public function createBooking($_, array $args, GraphQLContext $context)
{
    $user = $context->user();
    if (!$user) {
        throw new \Exception('Unauthenticated');
    }

    $input = $args['input'];

    return DB::transaction(function () use ($user, $input) {

        $types = collect($input['items'])
            ->pluck('item_type')
            ->unique()
            ->values();

        $bookingType = match (true) {
            $types->count() === 1 && $types[0] === 'hotel_room' => 'hotel',
            $types->count() === 1 && $types[0] === 'transport_schedule' => 'transport',
            default => 'mixed',
        };

        $booking = Booking::create([
            'booking_code' => 'BOOK-' . strtoupper(uniqid()),
            'user_id'      => $user->getAuthIdentifier(),
            'booking_type' => $bookingType,
            'status'       => 'pending',
            'total_price'  => 0, 
        ]);

        $total = 0;

        foreach ($input['items'] as $item) {

            if ($item['item_type'] === 'hotel_room') {

                $room = HotelRoom::lockForUpdate()
                    ->findOrFail($item['reference_id']);

                if ($room->quantity < $item['quantity']) {
                    throw new \Exception('Stok kamar tidak mencukupi');
                }

                $checkIn  = Carbon::parse($item['check_in']);
                $checkOut = Carbon::parse($item['check_out']);

                if ($checkOut <= $checkIn) {
                    throw new \Exception('Check-out harus setelah check-in');
                }

                $nights = max($checkIn->diffInDays($checkOut), 1);

                $subtotal = $room->price * $item['quantity'] * $nights;

                BookingItem::create([
                    'booking_id'  => $booking->id,
                    'item_type'   => 'hotel_room',
                    'reference_id'=> $room->id,
                    'quantity'    => $item['quantity'],
                    'price'       => $room->price,
                    'subtotal'    => $subtotal,
                    'check_in'    => $checkIn,
                    'check_out'   => $checkOut,
                ]);

                $room->decrement('quantity', $item['quantity']);
                $total += $subtotal;
            }

            if ($item['item_type'] === 'transport_schedule') {

                $schedule = TransportSchedule::findOrFail($item['reference_id']);

                $qty = (int) $item['quantity'];

                if ($qty < 1) {
                    throw new \Exception('Qty transport minimal 1');
                }

                $subtotal = $schedule->price * $qty;

                BookingItem::create([
                    'booking_id'  => $booking->id,
                    'item_type'   => 'transport_schedule',
                    'reference_id'=> $schedule->id,
                    'quantity'    => $qty,
                    'price'       => $schedule->price,
                    'subtotal'    => $subtotal,
                ]);

                $total += $subtotal;
            }
        }

        foreach ($input['passengers'] ?? [] as $i => $guest) {
            BookingGuest::create([
                'booking_id' => $booking->id,
                'full_name'  => $guest['full_name'],
                'phone'      => $guest['phone'] ?? null,
                'email'      => $guest['email'] ?? null,
                'is_primary' => $i === 0
            ]);
        }

        $booking->update([
            'total_price' => $total
        ]);

        return $booking;
    });
}


    public function deleteBooking($_, array $args)
{
    $userId = auth()->id();

    $booking = Booking::where('id', $args['id'])
        ->where('user_id', $userId)
        ->where('status', 'pending')
        ->first();

    if (!$booking) {
        throw new \Exception('Booking tidak ditemukan atau tidak bisa dihapus');
    }

    $booking->delete();

    return true;
}

public function update($_, array $args)
{
    $userId = Auth::id();
    if (!$userId) {
        throw new \Exception('Unauthenticated');
    }

    $booking = Booking::with('items')
        ->where('id', $args['id'])
        ->where('user_id', $userId)
        ->where('status', 'pending')
        ->where('booking_type', 'hotel')
        ->first();

    if (!$booking) {
        throw new \Exception('Booking tidak ditemukan atau tidak bisa diedit');
    }

    return DB::transaction(function () use ($booking, $args) {

        foreach ($booking->items as $item) {
            if ($item->item_type === 'hotel_room') {
                HotelRoom::where('id', $item->reference_id)
                    ->increment('quantity', $item->quantity);
            }
        }

        $booking->items()->delete();

        $total = 0;

        foreach ($args['items'] as $item) {

            $room = HotelRoom::lockForUpdate()
                ->findOrFail($item['reference_id']);

            if ($room->quantity < $item['quantity']) {
                throw new \Exception('Stok kamar tidak mencukupi');
            }

            $checkIn  = Carbon::parse($item['check_in']);
            $checkOut = Carbon::parse($item['check_out']);

            if ($checkOut->lessThanOrEqualTo($checkIn)) {
                throw new \Exception('Check-out harus setelah check-in');
            }

            $nights   = max($checkIn->diffInDays($checkOut), 1);
            $subtotal = $room->price * $item['quantity'] * $nights;

            $booking->items()->create([
                'item_type'    => 'hotel_room', 
                'reference_id' => $room->id,
                'quantity'     => $item['quantity'],
                'price'        => $room->price,
                'subtotal'     => $subtotal,
                'check_in'     => $checkIn,
                'check_out'    => $checkOut,
            ]);

            $room->decrement('quantity', $item['quantity']);
            $total += $subtotal;
        }

        $booking->update([
            'total_price' => $total
        ]);

        return $booking;
    });
}


}

