<?php

namespace App\GraphQL\Transport\Queries;

use App\Models\Transport\Transport;

class TransportQuery
{
    public function list()
    {
        return Transport::latest()->get();
    }

    public function detail($_, array $args)
    {
        return Transport::find($args['id']);
    }
}
