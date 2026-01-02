<?php

namespace App\GraphQL\TransportLocation\Mutations;

use App\Models\TransportLocation\TransportLocation;
use Illuminate\Support\Facades\Storage;

class TransportLocationMutation
{
    public function create($_, array $args)
    {
        $photoPath = null;

        if (!empty($args['photo'])) {
            $photoPath = $args['photo']->store(
                'transport-locations',
                'public'
            );
        }

        return TransportLocation::create([
            'type'  => $args['type'],
            'name'  => $args['name'],
            'code'  => $args['code'] ?? null,
            'city'  => $args['city'],
            'photo' => $photoPath,
        ]);
    }

    public function update($_, array $args)
    {
        $location = TransportLocation::findOrFail($args['id']);

        if (!empty($args['photo'])) {
            if ($location->photo) {
                Storage::disk('public')->delete($location->photo);
            }

            $location->photo = $args['photo']->store(
                'transport-locations',
                'public'
            );
        }

        $location->update([
            'type' => $args['type'] ?? $location->type,
            'name' => $args['name'] ?? $location->name,
            'code' => $args['code'] ?? $location->code,
            'city' => $args['city'] ?? $location->city,
        ]);

        return $location;
    }

    public function delete($_, array $args)
    {
        $location = TransportLocation::findOrFail($args['id']);

        if ($location->photo) {
            Storage::disk('public')->delete($location->photo);
        }

        return (bool) $location->delete();
    }
}
