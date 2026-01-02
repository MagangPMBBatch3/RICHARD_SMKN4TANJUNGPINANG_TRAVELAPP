<?php

namespace App\GraphQL\Transport\Mutations;

use App\Models\Transport\Transport;
use Illuminate\Support\Facades\Storage;

class TransportMutation
{
    public function create($_, array $args)
    {
        $photoPath = null;

        if (!empty($args['photo'])) {
            $photoPath = $args['photo']->store(
                'transports',
                'public'
            );
        }

        return Transport::create([
            'type' => $args['type'],
            'name' => $args['name'],
            'code' => $args['code'] ?? null,
            'capacity' => $args['capacity'] ?? null,
            'price_per_seat' => $args['price_per_seat'] ?? null,
            'photo' => $photoPath,
        ]);
    }

    public function update($_, array $args)
    {
        $transport = Transport::findOrFail($args['id']);

        if (!empty($args['photo'])) {
            if ($transport->photo) {
                Storage::disk('public')->delete($transport->photo);
            }

            $transport->photo = $args['photo']->store(
                'transports',
                'public'
            );
        }

        $transport->update([
            'type' => $args['type'] ?? $transport->type,
            'name' => $args['name'] ?? $transport->name,
            'code' => $args['code'] ?? $transport->code,
            'capacity' => $args['capacity'] ?? $transport->capacity,
            'price_per_seat' => $args['price_per_seat'] ?? $transport->price_per_seat,
        ]);

        return $transport;
    }

    public function delete($_, array $args)
    {
        $transport = Transport::findOrFail($args['id']);

        if ($transport->photo) {
            Storage::disk('public')->delete($transport->photo);
        }

        return (bool) $transport->delete();
    }
}
