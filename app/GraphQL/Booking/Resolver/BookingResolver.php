<?php

namespace App\GraphQL\Booking\Resolvers;

use App\Models\Booking\Booking;
use App\Models\HotelRoom\HotelRoom;
use App\Models\TransportSchedule\TransportSchedule;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BookingResolver
{
    private function userId(GraphQLContext $context): int
    {
        $user = $context->user();

        if (!$user) {
            throw new \Exception('Unauthenticated');
        }

        return (int) $user->getAuthIdentifier();
    }

    public function bookings($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $userId = $this->userId($context);

        return Booking::with(['items', 'payment', 'hotel', 'transportSchedule'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function booking($root, array $args, GraphQLContext $context)
    {
        $userId = $this->userId($context);

        return Booking::with(['items', 'payment', 'hotel', 'transportSchedule'])
            ->where('user_id', $userId)
            ->findOrFail($args['id']);
    }

    public function cancelBooking($root, array $args, GraphQLContext $context)
    {
        $userId = $this->userId($context);

        $booking = Booking::where('user_id', $userId)
            ->findOrFail($args['id']);

        foreach ($booking->items as $item) {
            if ($item->item_type === 'hotel_room') {
                HotelRoom::where('id', $item->reference_id)
                    ->increment('quantity', $item->quantity);
            }

            if ($item->item_type === 'transport') {
                TransportSchedule::where('id', $item->reference_id)
                    ->increment('seat_available', $item->quantity);
            }
        }

        $booking->update(['status' => 'cancelled']);

        return $booking;
    }

    public function updatePaymentStatus($root, array $args, GraphQLContext $context)
    {
        $userId = $this->userId($context);

        $booking = Booking::where('user_id', $userId)
            ->findOrFail($args['id']);

        $booking->payment()->update(['status' => $args['status']]);

        $booking->update([
            'status' => $args['status'] === 'paid'
                ? 'confirmed'
                : 'pending'
        ]);

        return $booking;
    }

    public function __invoke($root, array $args, GraphQLContext $context)
    {
        $userId = $this->userId($context);

        return Booking::with(['hotel', 'items'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
}
