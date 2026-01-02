<?php

namespace App\GraphQL\TransportSchedule\Mutations;

use App\Models\TransportSchedule\TransportSchedule;

class TransportScheduleMutation
{
    public function create($_, array $args)
    {
        if ($args['origin_location_id'] === $args['destination_location_id']) {
            throw new \Exception('Origin dan destination tidak boleh sama');
        }

        return TransportSchedule::create([
            'transport_id'            => $args['transport_id'],
            'origin_location_id'      => $args['origin_location_id'],
            'destination_location_id' => $args['destination_location_id'],
            'departure_time'          => $args['departure_time'],
            'arrival_time'            => $args['arrival_time'] ?? null,
            'price'                   => $args['price'],
        ]);
    }

    public function update($_, array $args)
    {
        $schedule = TransportSchedule::findOrFail($args['id']);

        if (
            isset($args['origin_location_id'], $args['destination_location_id']) &&
            $args['origin_location_id'] === $args['destination_location_id']
        ) {
            throw new \Exception('Origin dan destination tidak boleh sama');
        }

        $schedule->update([
            'origin_location_id'      => $args['origin_location_id'] ?? $schedule->origin_location_id,
            'destination_location_id' => $args['destination_location_id'] ?? $schedule->destination_location_id,
            'departure_time'          => $args['departure_time'] ?? $schedule->departure_time,
            'arrival_time'            => $args['arrival_time'] ?? $schedule->arrival_time,
            'price'                   => $args['price'] ?? $schedule->price,
        ]);

        return $schedule;
    }

    public function delete($_, array $args)
    {
        $schedule = TransportSchedule::findOrFail($args['id']);
        $schedule->delete();

        return true;
    }
}
