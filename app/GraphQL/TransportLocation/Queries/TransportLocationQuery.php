<?php

namespace App\GraphQL\TransportLocation\Queries;

use App\Models\TransportLocation\TransportLocation;

class TransportLocationQuery
{
    public function list($_, array $args)
    {
        return TransportLocation::query()
            ->when($args['type'] ?? null, function ($q, $type) {
                $q->where('type', $type);
            })
            ->latest()
            ->get();
    }

    public function detail($_, array $args)
    {
        return TransportLocation::find($args['id']);
    }
}
