<?php

namespace App\GraphQL\TransportSchedule\Queries;

use App\Models\TransportSchedule\TransportSchedule;

class TransportScheduleQuery
{
    public function byTransport($_, array $args)
    {
        return TransportSchedule::with([
            'transport',
            'originLocation',
            'destinationLocation',
        ])
        ->where('transport_id', $args['transport_id'])
        ->orderBy('departure_time')
        ->get();
    }

    public function byOrigin($_, array $args)
    {
        return TransportSchedule::with([
            'transport',
            'originLocation',
            'destinationLocation',
        ])
        ->where('origin_location_id', $args['location_id'])
        ->orderBy('departure_time')
        ->get();
    }

    public function byDestination($_, array $args)
    {
        return TransportSchedule::with([
            'transport',
            'originLocation',
            'destinationLocation',
        ])
        ->where('destination_location_id', $args['location_id'])
        ->orderBy('departure_time')
        ->get();
    }

    public function find($_, array $args)
    {
        return TransportSchedule::with([
            'transport',
            'originLocation',
            'destinationLocation',
        ])->findOrFail($args['id']);
    }
}
